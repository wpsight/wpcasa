<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Meta_Boxes class
 */
class WPSight_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Uncomment the following line if necessary
		// add_filter( 'cmb2_meta_box_url', array( $this, 'cmb2_meta_box_url' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 20 );

		// Add custom meta boxes
		add_action( 'cmb2_admin_init', array( $this, 'admin_meta_boxes' ) );

		// Set default listing ID
		add_action( 'wp_insert_post', array( $this, 'admin_listing_id_default' ), null, 2 );

		// Fire action when a listing is saved
		add_action( 'save_post', array( $this, 'admin_save_post' ), 1, 2 );

		// Add action when a listing is saved
		add_action( 'wpsight_save_listing', array( $this, 'admin_save_listing_data' ), 20, 2 );

		// Update geolocation data
		add_action( 'update_post_meta', array( $this, 'maybe_generate_geolocation_data' ), 10, 4 );

		// Update some listing post meta data
		add_action( 'add_meta_boxes_listing', array( $this, 'admin_post_meta_update' ) );

	}

	/**
	 * cmb2_meta_box_url()
	 *
	 * Make sure CMB2 works in unusual environments such as symlinking the plugin
	 *
	 * @param string $url
	 * @return string
	 * @see https://github.com/WebDevStudios/CMB2/issues/432
	 *
	 * @since 1.0.0
	 */
	public function cmb2_meta_box_url( $url ) {
		return plugins_url( 'cmb2/', $url );
	}

	/**
	 * admin_enqueue_scripts()
	 *
	 * Enqueue meta box CSS on corresponding admin pages.
	 *
	 * @access public
	 * @uses get_current_screen()
	 * @uses wp_enqueue_style()
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		
		$screen		= get_current_screen();		
		$post_type	= wpsight_post_type();

		if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type ) ) )
			wp_enqueue_style( 'wpsight-meta-boxes', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-meta-boxes' . $suffix . '.css' );

	}

	/**
	 * admin_meta_boxes()
	 *
	 * Merging arrays of all meta boxes to be
	 * sent to cmb2_admin_init filter of Custom Meta Box API.
	 *
	 * @access public
	 * @uses wpsight_meta_boxes()
	 * @uses new_cmb2_box()
	 * @uses $cmb->add_field()
	 * @uses $cmb->add_group_field()
	 * @return array
	 * @see /functions/wpsight-meta-boxes.php
	 *
	 * @since 1.0.0
	 */
	public function admin_meta_boxes( ) {

		$meta_boxes = wpsight_meta_boxes();

		foreach ( $meta_boxes as $metabox ) {
			if( $metabox ) {
				$cmb = new_cmb2_box( $metabox );
				foreach ( $metabox['fields'] as $field ) {
					if ( 'group' == $field['type'] ) {
						$group_field_id = $cmb->add_field( $field );
						foreach ( $field['group_fields'] as $group_field ) {
							$cmb->add_group_field( $group_field_id, $group_field );
						}
					} else {
						$cmb->add_field( $field );
					}
				}
			}
		}	

	}

	/**
	 * admin_listing_id_default()
	 *
	 * Save a default listing ID when
	 * creating a new listing (post-new.php)
	 * by getting auto-draft post id.
	 *
	 * @access public
	 * @param integer $post_id
	 * @param mixed $post
	 * @uses wpsight_post_type()
	 * @uses get_post_meta()
	 * @uses update_post_meta()
	 *
	 * @since 1.0.0
	 */
	public function admin_listing_id_default( $post_id, $post ) {

		if ( $post->post_status != 'auto-draft' || $post->post_type != wpsight_post_type() )
			return;

		$listing_id = get_post_meta( $post->ID, '_listing_id', true );

		if ( ! $listing_id )
			update_post_meta( $post->ID, '_listing_id', wpsight_get_listing_id( $post->ID ) );

	}

	/**
	 * admin_save_post()
	 *
	 * Fire action wpsight_save_listing when a listing
	 * is saved and meets some conditions.
	 *
	 * @access public
	 * @param integer $post_id
	 * @param mixed   $post
	 * @uses wp_is_post_revision()
	 * @uses wp_is_post_autosave()
	 * @uses current_user_can()
	 * @uses do_action()
	 *
	 * @since 1.0.0
	 */
	public function admin_save_post( $post_id, $post ) {

		// Stop when no post ID or object is given
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;

		// Stop if this is only a revision
		if ( is_int( wp_is_post_revision( $post ) ) ) return;

		// Stop if this is only an autosave
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;

		// Stop if current user is not allowed
		if ( ! current_user_can( 'edit_listing', $post_id ) ) return;

		// Stop if other post type
		if ( $post->post_type != wpsight_post_type() ) return;

		// Fire wpsight_save_listing action
		do_action( 'wpsight_save_listing', $post_id, $post );

	}

	/**
	 * admin_save_listing_data()
	 *
	 * Update listing data when saved.
	 *
	 * @access public
	 * @param integer $post_id
	 * @param mixed $post
	 * @uses update_post_meta()
	 * @uses apply_filters()
	 * @uses WPSight_Geocode::has_location_data()
	 * @uses WPSight_Geocode::generate_location_data()
	 * @uses sanitize_text_field()
	 *
	 * @since 1.0.0
	 */
	public function admin_save_listing_data( $post_id, $post ) {
		
		if( ! is_admin() )
			return;

		// Update listing location data

		$value = array_values( (array) $_POST[ '_map_address' ] );

		if ( update_post_meta( $post_id, '_map_address', sanitize_text_field( $value[0] ) ) ) {
			// Location data will be updated by maybe_generate_geolocation_data method
		} elseif ( apply_filters( 'wpsight_geolocation_enabled', true ) && ! WPSight_Geocode::has_location_data( $post_id ) ) {
			WPSight_Geocode::generate_location_data( $post_id, sanitize_text_field( $value[0] ) );
		}

	}

	/**
	 * maybe_generate_geolocation_data()
	 *
	 * Generate location data if a post is saved
	 *
	 * @since 1.0.0
	 */
	public function maybe_generate_geolocation_data( $meta_id, $object_id, $meta_key, $_meta_value ) {
		if ( '_map_address' !== $meta_key || wpsight_post_type() !== get_post_type( $object_id ) ) {
			return;
		}
		do_action( 'wpsight_listing_location_edited', $object_id, $_meta_value );
	}

	/**
	 * admin_post_meta_update()
	 *
	 * Rename and update some post meta to
	 * ensure backwards compability with
	 * older WPCasa versions.
	 *
	 * @access public
	 * @uses get_the_id()
	 * @uses update_post_meta()
	 * @uses delete_post_meta()
	 * @uses wpsight_maybe_update_gallery()
	 *
	 * @since 1.0.0
	 */
	public function admin_post_meta_update( $post ) {

		// Post ID
		$post_id = $post->ID;

		// Rename _price_sold_rented post meta

		$sold_rented = $post->_price_sold_rented;

		if ( ! empty( $sold_rented ) ) {

			// Update new field with old field value
			update_post_meta( $post_id, '_listing_not_available', $sold_rented );

			// Remove old field
			delete_post_meta( $post_id, '_price_sold_rented' );

		}

		// Rename _price_status post meta

		// Get old _price_status value
		$status = $post->_price_status;

		if ( ! empty( $status ) ) {

			// Update new field with old field value
			update_post_meta( $post_id, '_price_offer', $status );

			// Remove old field
			delete_post_meta( $post_id, '_price_status' );

		}

		// Update gallery information
		wpsight_maybe_update_gallery( $post_id );
		
		// Update post meta title
		
		if( $post->post_title != $post->_listing_title )
			update_post_meta( $post_id, '_listing_title', $post->post_title );

	}

	/**
	 * meta_boxes()
	 *
	 * Merging arrays of all WPSight meta boxes
	 *
	 * @uses self::meta_box_listing_*()
	 * @uses self::meta_box_user()
	 * @return array Array of all listing meta boxes
	 *
	 * @since 1.0.0
	 */
	public static function meta_boxes() {

		// Merge all meta box arrays

		$meta_boxes = array(
			'listing_attributes'	=> self::meta_box_listing_attributes(),
			'listing_price'			=> self::meta_box_listing_price(),
			'listing_details'		=> self::meta_box_listing_details(),
			'listing_location'		=> self::meta_box_listing_location(),
			'listing_agent'			=> self::meta_box_listing_agent(),
			'user_agent'			=> self::meta_box_user_agent()
		);

		// Add custom spaces if any

		foreach ( wpsight_meta_box_spaces() as $key => $space )
			$meta_boxes[ $key ] = $space;

		return apply_filters( 'wpsight_meta_boxes', $meta_boxes );

	}

	/**
	 * meta_box_listing_attributes()
	 *
	 * Create listing attributes meta box
	 *
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_listing_attributes() {

		// Set meta box fields

		$fields = array(
			'availability' => array(
				'name'      => __( 'Availability', 'wpcasa' ),
				'id'        => '_listing_not_available',
				'type'      => 'checkbox',
				'label_cb'  => __( 'Item not available', 'wpcasa' ),
				'desc'      => __( 'The item is currently not available as it has been sold or rented.', 'wpcasa' ),
				'dashboard' => false,
				'priority'  => 10
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_attributes_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'       => 'listing_attributes',
			'title'    => __( 'Listing Attributes', 'wpcasa' ),
			'object_types'    => array( wpsight_post_type() ),
			'context'  => 'side',
			'priority' => 'core',
			'fields'   => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_attributes', $meta_box );

	}

	/**
	 * meta_box_listing_images()
	 *
	 * Create listing images meta box
	 *
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_listing_images() {

		// Set meta box fields

		$fields = array(
			'images' => array(
				'name'       => __( 'Images', 'wpcasa' ),
				'id'         => '_gallery',
				'type'       => 'file_list',
				'preview_size' => array( 150, 150 ),
				'sortable'   => true,
				'desc'       => false,
				'dashboard'  => false
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_images_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'       => 'listing_images',
			'title'    => __( 'Listing Images', 'wpcasa' ),
			'object_types'    => array( wpsight_post_type() ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_images', $meta_box );

	}

	/**
	 * meta_box_listing_price()
	 *
	 * Create listing price meta box
	 *
	 * @uses wpsight_get_currency()
	 * @uses wpsight_offers()
	 * @uses wpsight_rental_periods()
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_listing_price() {

		// Set meta box fields

		$fields = array(
			'price' => array(
				'name'      => __( 'Price', 'wpcasa' ) . ' (' . wpsight_get_currency() . ')',
				'id'        => '_price',
				'type'      => 'text',
				'desc'      => __( 'No currency symbols or thousands separators', 'wpcasa' ),
				'dashboard' => true,
				'priority'  => 10
			),
			'offer' => array(
				'name'      => __( 'Offer', 'wpcasa' ),
				'id'        => '_price_offer',
				'type'      => 'radio',
				'options'   => wpsight_offers(),
				'default'   => 'sale',
				'dashboard' => true,
				'priority'  => 20
			),
			'period' => array(
				'name'      => __( 'Period', 'wpcasa' ),
				'id'        => '_price_period',
				'type'      => 'select',
				'options'   => array_merge( array( '' => __( 'None', 'wpcasa' ) ), array_filter( wpsight_rental_periods() ) ),
				'dashboard' => true,
				'priority'  => 30
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_price_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'       => 'listing_price',
			'title'    => __( 'Listing Price', 'wpcasa' ),
			'object_types'    => array( wpsight_post_type() ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_price', $meta_box );

	}

	/**
	 * meta_box_listing_details()
	 *
	 * Create listing details meta box
	 *
	 * @uses wpsight_user_can_edit_listing_id()
	 * @uses wpsight_measurements()
	 * @uses wpsight_details()
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_listing_details() {

		// Set meta box fields

		$fields = array(
			'id' => array(
				'name'      => __( 'Listing ID', 'wpcasa' ),
				'id'        => '_listing_id',
				'type'      => 'text',
				'dashboard' => wpsight_user_can_edit_listing_id() ? true : 'disabled',
				'readonly'  => wpsight_user_can_edit_listing_id() ? false : true,
				'priority'  => 10
			)
		);

		/**
		 * Add listing details fields
		 */

		$units = wpsight_measurements();

		$prio = 20;

		foreach ( wpsight_details() as $detail => $value ) {

			if ( ! empty( $value['label'] ) ) {

				// Optionally add measurement label to title
				$unit  = '';

				if ( ! empty( $value['unit'] ) ) {
					$unit = $value['unit'];
					$unit = $units[$unit];
					$unit = ' (' . $unit . ')';
				}

				// If there is select data, create select fields else text

				if ( ! empty( $value['data'] ) ) {

					$fields[$detail] = array(
						'name'      => $value['label'] . $unit,
						'id'        => '_' . $detail,
						'type'      => 'select',
						'options'   => $value['data'],
						'desc'      => $value['description'],
						'dashboard'	=> $value['dashboard'],
						'priority'  => $prio
					);

				} else {

					$fields[$detail] = array(
						'name'      => $value['label'] . $unit,
						'id'        => '_' . $detail,
						'type'      => 'text',
						'desc'      => $value['description'],
						'dashboard'	=> $value['dashboard'],
						'priority'  => $prio
					);

				} // end if

			} // end if

			$prio +=10;

		} // end foreach

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_details_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'       => 'listing_details',
			'title'    => __( 'Listing Details', 'wpcasa' ),
			'object_types'    => array( wpsight_post_type() ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_details', $meta_box );

	}

	/**
	 * meta_box_listing_location()
	 *
	 * Create listing location meta box
	 *
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_listing_location() {

		// Create map fields

		$fields = array(
			'address' => array(
				'name'      => __( 'Address', 'wpcasa' ),
				'id'        => '_map_address',
				'type'      => 'text',
				'desc'      => __( 'e.g. <code>Marbella, Spain</code> or <code>Platz der Republik 1, 10557 Berlin</code>', 'wpcasa' ),
				'class'     => 'map-search',
				'dashboard'	=> true,
				'priority'  => 10
			),
			'note' => array(
				'name'      => __( 'Public Note', 'wpcasa' ),
				'id'        => '_map_note',
				'type'      => 'text',
				'desc'      => __( 'e.g. <code>Location is not the exact address of the listing</code>', 'wpcasa' ),
				'dashboard'	=> true,
				'priority'  => 40
			),
			'secret' => array(
				'name'      => __( 'Secret Note', 'wpcasa' ),
				'id'        => '_map_secret',
				'type'      => 'textarea',
				'desc'      => __( 'Will not be displayed on the website (e.g. complete address)', 'wpcasa' ),
				'dashboard'	=> true,
				'priority'  => 50
			),
			'hide' => array(
				'name'      => __( 'Hide Map', 'wpcasa' ),
				'id'        => '_map_hide',
				'type'      => 'checkbox',
				'desc'		=> __( 'Hide map for this listing', 'wpcasa' ),
				'dashboard'	=> true,
				'priority'  => 60
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_location_fields', $fields ) );

		// Create meta box

		$meta_box = array(
			'id'       => 'listing_location',
			'title'    => __( 'Listing Location', 'wpcasa' ),
			'object_types'    => array( wpsight_post_type() ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_location', $meta_box );

	}

	/**
	 * meta_box_listing_agent()
	 *
	 * Create listing agent box
	 *
	 * @uses wp_get_current_user()
	 * @uses get_user_meta()
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_listing_agent() {

		// Set meta box fields

		$fields = array(
			'name' => array(
				'name'      => __( 'Name', 'wpcasa' ),
				'id'        => '_agent_name',
				'type'      => 'text',
				'desc'      => false,
				'default'   => wp_get_current_user()->display_name,
				'priority'  => 10
			),
			'company' => array(
				'name'      => __( 'Company', 'wpcasa' ),
				'id'        => '_agent_company',
				'type'      => 'text',
				'desc'      => false,
				'default'   => get_user_meta( wp_get_current_user()->ID, 'company', true ),
				'priority'  => 20
			),
			'description' => array(
				'name'      => __( 'Description', 'wpcasa' ),
				'id'        => '_agent_description',
				'type'      => 'textarea',
				'desc'      => false,
				'default'   => get_user_meta( wp_get_current_user()->ID, 'description', true ),
				'priority'  => 30
			),
			'phone' => array(
				'name'      => __( 'Phone', 'wpcasa' ),
				'id'        => '_agent_phone',
				'type'      => 'text',
				'desc'      => false,
				'default'   => get_user_meta( wp_get_current_user()->ID, 'phone', true ),
				'priority'  => 40
			),
			'website' => array(
				'name'      => __( 'Website', 'wpcasa' ),
				'id'        => '_agent_website',
				'type'      => 'text_url',
				'desc'      => false,
				'default'   => wp_get_current_user()->user_url,
				'priority'  => 50
			),
			'twitter' => array(
				'name'      => __( 'Twitter', 'wpcasa' ),
				'id'        => '_agent_twitter',
				'type'      => 'text',
				'desc'      => false,
				'default'   => get_user_meta( wp_get_current_user()->ID, 'twitter', true ),
				'priority'  => 60
			),
			'facebook' => array(
				'name'      => __( 'Facebook', 'wpcasa' ),
				'id'        => '_agent_facebook',
				'type'      => 'text',
				'desc'      => false,
				'default'   => get_user_meta( wp_get_current_user()->ID, 'facebook', true ),
				'priority'  => 70
			),
			'logo' => array(
				'name'      => __( 'Logo', 'wpcasa' ),
				'id'        => '_agent_logo',
				'type'      => 'file',
				'desc'      => false,
				'preview_size' => array( 75, 75 ),
				'default'   => get_user_meta( wp_get_current_user()->ID, 'agent_logo', true ),
				'priority'  => 80
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_agent_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'           => 'listing_agent',
			'title'        => __( 'Listing Agent', 'wpcasa' ),
			'object_types' => array( wpsight_post_type() ),
			'context'      => 'normal',
			'priority'     => 'high',
			'fields'       => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_agent', $meta_box );

	}
	
	/**
	 *	meta_box_user_agent()
	 *	
	 *	Create user agent meta box
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@return	array
	 *	@see	wpsight_meta_boxes()
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_user_agent() {

		// Set meta box fields

		$fields = array(
			'general_title' => array(
				'id'        => 'general_title',
				'name'      => __( 'Agent Information', 'wpcasa' ),
				'desc'		=> __( 'Apart from the default WordPress profile information above you can add additional agent details here.', 'wpcasa' ),
				'type'      => 'title',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
				'priority'  => 5
			),
			'agent_logo'	=> array(
				'id'        => 'agent_logo',
				'name'      => __( 'Image', 'wpcasa' ),
				'type'      => 'file',
				'priority'  => 10
			),
			'company'		=> array(
				'id'        => 'company',
				'name'      => __( 'Company', 'wpcasa' ),
				'type'      => 'text',
				'priority'  => 20
			),
			'phone'			=> array(
				'id'        => 'phone',
				'name'      => __( 'Phone', 'wpcasa' ),
				'type'      => 'text',
				'priority'  => 30
			),
			'facebook'		=> array(
				'id'        => 'facebook',
				'name'      => __( 'Facebook', 'wpcasa' ),
				'type'      => 'text',
				'priority'  => 40
			),
			'twitter'		=> array(
				'id'        => 'twitter',
				'name'      => __( 'Twitter', 'wpcasa' ),
				'type'      => 'text',
				'priority'  => 50
			),
			'agent_update'	=> array(
				'name'      => __( 'Agent Update', 'wpcasa' ),
				'id'        => 'agent_update',
				'type'      => 'checkbox',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
				'label_cb'  => __( 'Agent Update', 'wpcasa' ),
				'desc'      => __( 'Update agent info of all listings created by this user', 'wpcasa' ),
				'priority'  => 60
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_user_agent_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'            => 'wpsight_agent',
			'title'			=> __( 'Agent', 'wpcasa' ),
			'object_types'  => array( 'user' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
			'fields'		=> $fields
		);
		
		return apply_filters( 'wpsight_meta_box_agent', $meta_box );

	}

	/**
	 * meta_box_spaces()
	 *
	 * Create listing spaces box(es)
	 *
	 * @uses wpsight_spaces()
	 * @uses wpsight_sort_array_by_priority()
	 * @uses wpsight_post_type()
	 * @return array $meta_box Meta box array with fields
	 * @see wpsight_meta_boxes()
	 * @see /functions/wpsight-general.php => L768
	 *
	 * @since 1.0.0
	 */
	public static function meta_box_spaces() {

		$meta_boxes = array();

		// Loop through existing spaces
		
		foreach ( wpsight_spaces() as $key => $space ) {
		
			// Check if multiple fields
		
			if ( ! isset( $space['fields'] ) || empty( $space['fields'] ) ) {
		
				// If not, set one field
		
				$fields = array(
					$key => array(
						'name'	=> $space['label'],
						'id'	=> $space['key'],
						'type'	=> $space['type'],
						'desc'	=> $space['description'],
						'rows'	=> $space['rows']
					)
				);
		
			} else {
		
				// If yes, set meta box fields
		
				$fields = $space['fields'];
		
				// Set info field as description
		
				if ( isset( $space['description'] ) && ! empty( $space['description'] ) )
					$fields['description'] = array(
						'id'		=> $space['key'] . '_desc',
						'name'		=> $space['description'],
						'type'		=> 'title',
						'priority'	=> 0
					);
		
			}
		
			// Apply filter and order fields by priority
			$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_spaces_fields', $fields, $space ) );
		
			// Set meta box
		
			$meta_boxes[ $key ] = array(
				'id'			=> $key,
				'title'			=> $space['title'],
				'object_types'	=> array( $space['post_type'] ),
				'context'		=> 'normal',
				'priority'		=> 'high',
				'fields'		=> $fields
		
			);
		
		} // endforeach
		
		return apply_filters( 'wpsight_meta_box_spaces', $meta_boxes );

	}
	
	/**
	 *	show_username()
	 *
	 *	Callback function to show username on
	 *	profile pages in readonly field
	 *	(e.g. used in dashboard add-on).
	 *	
	 *	@access	public
	 *	@param	$field_args	array
	 *	@param	$field		object	CMB2_Field
	 *	@return string
	 *
	 *	@since	1.1.0
	 */
	public static function show_username( $field_args, $field ) {
		
		$object_id = $field->object_id;
		$user_data = get_userdata( $object_id );
		
		return is_user_logged_in() ? $user_data->user_login : '';

	}
	
	/**
	 *	meta_box_field_only_front_end()
	 *	
	 *	Callback function to show meta box fields
	 *	only on front end.
	 *
	 *	@access	public
	 *	@return	bool
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_field_only_front_end( $meta_box_field ) {
		return apply_filters( 'wpsight_meta_box_field_only_front_end', ! is_admin(), $meta_box_field );
	}
	
	/**
	 *	meta_box_field_only_admin()
	 *	
	 *	Callback function to show meta box fields
	 *	only in admin area.
	 *
	 *	@access	public
	 *	@return	bool
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_field_only_admin( $meta_box_field ) {
		return apply_filters( 'meta_box_field_only_admin', is_admin(), $meta_box_field );
	}

}
