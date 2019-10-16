<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Meta_Boxes class
 */
class WPSight_Meta_Boxes_Butterbean {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_action( 'plugins_loaded', array( $this, 'load' ) );
		
		add_action( 'butterbean_register', array( $this, 'register' ), 10, 2 );
		add_action( 'butterbean_register', array( $this, 'meta_box_listing_price' ), 10 );
		add_action( 'butterbean_register', array( $this, 'meta_box_listing_details' ), 10 );
		add_action( 'butterbean_register', array( $this, 'meta_box_listing_location' ), 10 );
		add_action( 'butterbean_register', array( $this, 'meta_box_listing_agent' ), 10 );

	}

	public function load() {
	
		require_once( WPSIGHT_PLUGIN_DIR . '/vendor/justintadlock/butterbean/butterbean.php' );
		
	}
	
	public function register( $butterbean, $post_type ) {
	
		// Bail if not our post type.
		if ( wpsight_post_type() !== $post_type )
			return;
	
		$butterbean->register_manager(
			'data',
			array(
				'label'     => esc_html__( 'Listing Data', 'wpcasa' ),
				'post_type' => wpsight_post_type(),
				'context'   => 'normal',
				'priority'  => 'high'
			)
		);
		
//		$manager = $butterbean->get_manager( 'details' );
//		
//		$manager->register_section(
//			'urls',
//			array(
//				'label' => esc_html__( 'URLs', 'demokit' ),
//				'icon'  => 'dashicons-admin-links'
//			)
//		);
//	
//		$manager->register_setting(
//			'type',
//			array(
//				'sanitize_callback' => 'wp_filter_nohtml_kses'
//			)
//		);
//	
//		$manager->register_control(
//			'item_url',
//			array(
//				'type'    => 'text',
//				'section' => 'urls',
//				'label'   => esc_html__( 'Item URL', 'demokit' ),
//				'attr'    => array( 'class' => 'widefat' )
//			)
//		);
//		
//		$manager->register_setting(
//			'item_url',
//			array(
//				'sanitize_callback' => 'wp_filter_nohtml_kses'
//			)
//		);
//	
//		$manager->register_control(
//			'demo_url',
//			array(
//				'type'    => 'text',
//				'section' => 'urls',
//				'label'   => esc_html__( 'Demo URL', 'demokit' ),
//				'attr'    => array( 'class' => 'widefat' )
//			)
//		);
//		
//		$manager->register_setting(
//			'demo_url',
//			array(
//				'sanitize_callback' => 'wp_filter_nohtml_kses'
//			)
//		);
//	
//		$manager->register_control(
//			'test_url',
//			array(
//				'type'    => 'text',
//				'section' => 'urls',
//				'label'   => esc_html__( 'Test URL', 'demokit' ),
//				'attr'    => array( 'class' => 'widefat' )
//			)
//		);
//		
//		$manager->register_setting(
//			'test_url',
//			array(
//				'sanitize_callback' => 'wp_filter_nohtml_kses'
//			)
//		);
	
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
	public static function meta_box_listing_price( $butterbean ) {
		
		// Set section args
		$args = array(
			'label'		=> esc_html__( 'Price', 'wpcasa' ),
			'manager'	=> 'data',
			'section'	=> 'price',
			'icon'		=> 'dashicons-admin-generic'
		);

		// Set meta box fields
		$fields['fields'] = array(
			'price' => array(
				'name'      => esc_html__( 'Price', 'wpcasa' ) . ' (' . wpsight_get_currency() . ')',
				'id'        => '_price',
				'type'      => 'text',
				'desc'      => __( 'No currency symbols or thousands separators', 'wpcasa' ),
				'dashboard' => true,
				'priority'  => 10
			),
			'offer' => array(
				'name'      => esc_html__( 'Offer', 'wpcasa' ),
				'id'        => '_price_offer',
				'type'      => 'radio',
				'options'   => wpsight_offers(),
				'default'   => 'sale',
				'dashboard' => true,
				'priority'  => 20
			),
			'period' => array(
				'name'      => esc_html__( 'Period', 'wpcasa' ),
				'id'        => '_price_period',
				'type'      => 'select',
				'options'   => array_merge( array( '' => __( 'None', 'wpcasa' ) ), array_filter( wpsight_rental_periods() ) ),
				'dashboard' => true,
				'priority'  => 30
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_price_fields', $fields ) );
		
		// Merge args and fields
		$args = array_merge( $args, $fields );
		
		$mb = new WPSight_Meta_Boxes_Butterbean();
		$mb->register_metabox( $butterbean, $args );

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
	public static function meta_box_listing_details( $butterbean ) {
		
		// Set section args
		$args = array(
			'label'		=> esc_html__( 'Details', 'wpcasa' ),
			'manager'	=> 'data',
			'section'	=> 'details',
			'icon'		=> 'dashicons-admin-generic'
		);

		// Set meta box fields
		$fields['fields'] = array(
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

					$fields['fields'][$detail] = array(
						'name'      => $value['label'] . $unit,
						'id'        => '_' . $detail,
						'type'      => 'select',
						'options'   => $value['data'],
						'desc'      => $value['description'],
						'dashboard'	=> $value['dashboard'],
						'priority'  => $prio
					);

				} else {

					$fields['fields'][$detail] = array(
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

		// Merge args and fields
		$args = array_merge( $args, $fields );
		
		$mb = new WPSight_Meta_Boxes_Butterbean();
		$mb->register_metabox( $butterbean, $args );

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
	public static function meta_box_listing_location( $butterbean ) {
		
		// Set section args
		$args = array(
			'label'		=> esc_html__( 'Location', 'wpcasa' ),
			'manager'	=> 'data',
			'section'	=> 'location',
			'icon'		=> 'dashicons-location-alt'
		);

		// Create map fields
		$fields['fields'] = array(
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
		$fields		= wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_location_fields', $fields ) );

		// Merge args and fields
		$args = array_merge( $args, $fields );
		
		$mb = new WPSight_Meta_Boxes_Butterbean();
		$mb->register_metabox( $butterbean, $args );

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
	public static function meta_box_listing_agent( $butterbean ) {

		// Set section args
		$args = array(
			'label'		=> esc_html__( 'Agent', 'wpcasa' ),
			'manager'	=> 'data',
			'section'	=> 'agent',
			'icon'		=> 'dashicons-admin-users'
		);

		// Set meta box fields
		$fields['fields'] = array(
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

		// Merge args and fields
		$args = array_merge( $args, $fields );
		
		$mb = new WPSight_Meta_Boxes_Butterbean();
		$mb->register_metabox( $butterbean, $args );

	}
	
	
	public function register_metabox( $butterbean, $args = array() ) {
	
		$manager = $butterbean->get_manager( $args['manager'] );
	
		$manager->register_section(
			$args['section'],
			array(
				'label' => $args['label'],
				'icon'  => $args['icon']
			)
		);
		
		foreach( $args['fields'] as $field => $v ) {
							
			$atts = array(
				'section' => $args['section'],
				'attr'    => array( 'class' => 'widefat' )
			);
			
			$atts['type']			= isset( $v['type'] )		? $v['type']		: null;
			$atts['description']	= isset( $v['desc'] )		? $v['desc']		: null;
			$atts['label']			= isset( $v['name'] )		? $v['name']		: null;
			$atts['default']		= isset( $v['default'] )	? $v['default']		: null;
			
			if( $v['type'] == 'radio' || $v['type'] == 'select' ) {
				$choices = $v['options'];
				$atts['choices'] = $choices;
			}

			$manager->register_control( $field, $atts );
			
			$manager->register_setting(
				$field,
				array(
					'sanitize_callback' => 'wp_filter_nohtml_kses'
				)
			);

		}
		
		return;
		
	}

}
