<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

// Add custom meta boxes API
require_once( 'Custom-Meta-Boxes/custom-meta-boxes.php' );

/**
 * wpSight_Admin_Meta_Boxes class
 */
class WPSight_Admin_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 20 );
		
		// Add custom meta boxes
		add_filter( 'cmb_meta_boxes', array( $this, 'admin_meta_boxes' ) );
		
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
		
		// Add custom meta box field types
		add_filter( 'cmb_field_types', array( $this, 'admin_metabox_field_types' ) );
		
		// Set existing attachments as gallery meta data
		add_action( 'update_post_meta', array( $this, 'admin_gallery_default' ), 1, 4 );

	}
	
	/**
	 * admin_enqueue_scripts()
	 *
	 * @access public
	 * @uses get_current_screen()
	 * @uses wp_enqueue_style()
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {

		if ( in_array( get_current_screen()->id, array( 'edit-listing', 'listing' ) ) )
			wp_enqueue_style( 'wpsight_metaboxes', WPSIGHT_PLUGIN_URL . '/assets/css/meta-boxes.css' );

	}

	/**
	 * admin_meta_boxes()
	 *
	 * Merging arrays of all meta boxes to be
	 * sent to cmb_meta_boxes filter of Custom Meta Box API.
	 *
	 * @access public
	 * @return array
	 * @see /functions/wpsight-meta-boxes.php
	 * @see https://github.com/humanmade/Custom-Meta-Boxes/wiki/Create-a-Meta-Box
	 *
	 * @since 1.0.0
	 */
	public function admin_meta_boxes( array $meta_boxes ) {

		if( is_array( wpsight_meta_boxes() ) )
	    	$meta_boxes = array_merge( wpsight_meta_boxes(), $meta_boxes );
	
	    return $meta_boxes; 
	
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
			
		if( ! $listing_id )
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
	 * @param mixed $post
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
	 * @uses wpSight_Geocode::has_location_data()
	 * @uses wpSight_Geocode::generate_location_data()
	 * @uses sanitize_text_field()
	 *
	 * @since 1.0.0
	 */
	public function admin_save_listing_data( $post_id, $post ) {
		
		// Update listing location data
		
		$value = array_values( $_POST[ '_map_address' ] );
		
		if ( update_post_meta( $post_id, '_map_address', sanitize_text_field( $value[0] ) ) ) {
			// Location data will be updated by maybe_generate_geolocation_data method
		} elseif ( apply_filters( 'wpsight_geolocation_enabled', true ) && ! wpSight_Geocode::has_location_data( $post_id ) ) {
			wpSight_Geocode::generate_location_data( $post_id, sanitize_text_field( $value[0] ) );
		}
		
		// Update listing agent logo URL
		
		$agent_logo_id = array_values( $_POST[ '_agent_logo_id' ] );
		
		if( ! empty( $agent_logo_id[0] ) ) {
			
			if( ! $post->_agent_logo ) {
			
				$agent_logo = wp_get_attachment_url( absint( $agent_logo_id[0] ) );
				
				update_post_meta( $post_id, '_agent_logo', $agent_logo );
			
			}

		} else {
			
			delete_post_meta( $post_id, '_agent_logo' );
			
		}
		
	}
	
	/**
	 * Generate location data if a post is saved
	 * @param  int $post_id
	 * @param  array $post
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
	 * older wpCasa versions.
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
		
		// Get old _price_sold_rented value
		$sold_rented = $post->_price_sold_rented;
		
		if( ! empty( $sold_rented ) ) {
			
			// Update new field with old field value
			update_post_meta( $post_id, '_listing_not_available', $sold_rented );
			
			// Remove old field
			delete_post_meta( $post_id, '_price_sold_rented' );
			
		}
		
		// Rename _price_status post meta
		
		// Get old _price_status value
		$status = $post->_price_status;
		
		if( ! empty( $status ) ) {
			
			// Update new field with old field value
			update_post_meta( $post_id, '_price_offer', $status );
			
			// Remove old field
			delete_post_meta( $post_id, '_price_status' );
			
		}
		
		// Update gallery information
		wpsight_maybe_update_gallery( $post_id );
		
	}
	
	/**
	 * Set available image attachments
	 * as default images.
	 *
	 * @access public
	 * @uses get_current_screen()
	 * @uses get_the_id()
	 * @uses get_post_meta()
	 * @uses add_post_meta()
	 * @uses get_posts()
	 *
	 * @since 1.0.0
	 */
	public function admin_gallery_default( $meta_id, $object_id, $meta_key, $_meta_value ) {
		global $post;
		
		if( '_gallery' !== $meta_key || wpsight_post_type() !== get_post_type( $object_id ) )
			return;
			
		$post_id = $post->ID;
		
		// Check if gallery has already been imported
		$gallery_imported = $post->_gallery_imported;
		
		if( ! $gallery_imported ) {
		
			// Check existing gallery
			$gallery = get_post_meta( $post_id, '_gallery' );
			
			// Get all image attachments
			
			$attachments = get_posts(
				array(
					'post_type' 	 => 'attachment',
					'posts_per_page' => -1,
					'post_parent' 	 => $post_id,
					'post_mime_type' => 'image',
					'orderby'		 => 'menu_order'
				)
			);
			
			/**
			 * If still no gallery is available and it
			 * hasn't been imported yet, but there are
			 * attachments, create gallery custom fields
			 * with attachment IDs.
			 */
			
			if( ! $gallery && $attachments ) {
				
				// Loop through attachments
				
				foreach( $attachments as $attachment ) {
					
					// Create gallery post meta with attachment ID
					
					if( $attachment->ID != absint( $post->_agent_logo_id ) )
						add_post_meta( $post_id, '_gallery', $attachment->ID );

				}
				
				// Mark gallery as imported
				add_post_meta( $post_id, '_gallery_imported', '1' );
			
			}
		
		}
		
	}
	
	/**
	 * admin_metabox_field_types()
	 * 
	 * Add or replace CMB field types
	 * using cmb_field_types filter.
	 *
	 * @access public
	 * @param array $cmb_field_types Existing CMB field types
	 *
	 * @since 1.0.0
	 */
	public function admin_metabox_field_types( $cmb_field_types ) {

	    $cmb_field_types['checkbox'] 		= 'WPSight_Checkbox_Field';
	    $cmb_field_types['file_multiple'] 	= 'WPSight_File_Multiple_Field';
		$cmb_field_types['image_multiple'] 	= 'WPSight_Image_Multiple_Field';
		$cmb_field_types['info']			= 'WPSight_Info_Field';

	    return $cmb_field_types;

	}
	
}

/**
 * Create new WPSight_Checkbox_Field class sub class
 * to replace the original CMB checkbox field.
 *
 * @see https://github.com/humanmade/Custom-Meta-Boxes/blob/master/classes.fields.php#L886 => class CMB_Checkbox
 */
class WPSight_Checkbox_Field extends CMB_Field {

	public function html() { ?>

		<input <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> type="checkbox" <?php $this->name_attr(); ?>  value="1" <?php checked( $this->get_value() ); ?> />
		<label <?php $this->for_attr(); ?>><?php echo esc_html( $this->args['label_cb'] ); ?></label>

	<?php }

}

/**
 * Create new WPSight_File_Multiple_Field sub class
 * for our custom field type file_multiple.
 *
 * @see https://github.com/humanmade/Custom-Meta-Boxes/blob/master/classes.fields.php#L416 => class CMB_File_Field
 */

class WPSight_File_Multiple_Field extends CMB_Field {

	/**
	 * Return the default args for the File field.
	 *
	 * @return array $args
	 */
	public function get_default_args() {
		return array_merge(
			parent::get_default_args(),
			array(
				'library-type' => array( 'video', 'audio', 'text', 'application' )
			)
		);
	}

	function enqueue_scripts() {

		global $post_ID;
		$post_ID = isset($post_ID) ? (int) $post_ID : 0;

		parent::enqueue_scripts();

		wp_enqueue_media( array( 'post' => $post_ID ));
		wp_enqueue_script( 'cmb-multiple-upload', WPSIGHT_PLUGIN_URL . '/assets/js/multiple-upload.js', array( 'jquery', 'cmb-scripts' ) );
		
		wp_localize_script( 'cmb-multiple-upload', 'cmb_multiple_upload', array(
				'text_title'  => __( 'Select Files', 'wpsight' ),
				'text_button' => __( 'Insert Files', 'wpsight' )
			)
		);

	}
	
	// Enqueue styles

	function enqueue_styles() {
		wp_enqueue_style( 'cmb-multiple-upload', WPSIGHT_PLUGIN_URL . '/assets/css/multiple-upload.css' );
	}

	public function html() {

		if ( $this->get_value() ) {
			$src = wp_mime_type_icon( $this->get_value() );
			$size = getimagesize( str_replace( site_url(), ABSPATH, $src ) );
			$icon_img = '<img src="' . $src . '" ' . $size[3] . ' />';
		}

		$data_type = ( ! empty( $this->args['library-type'] ) ? implode( ',', $this->args['library-type'] ) : null ); ?>

		<div class="cmb-file-wrap" <?php echo 'data-type="' . esc_attr( $data_type ) . '"'; ?>>

			<div class="cmb-file-wrap-placeholder"></div>

			<button class="button cmb-multiple-upload <?php echo esc_attr( $this->get_value() ) ? 'hidden' : '' ?>">
				<?php esc_html_e( 'Add Files', 'cmb' ); ?>
			</button>

			<div class="cmb-file-holder type-file <?php echo $this->get_value() ? '' : 'hidden'; ?>">

				<?php if ( $this->get_value() ) : ?>

					<?php if ( isset( $icon_img ) ) echo $icon_img; ?>

					<div class="cmb-file-name">
						<strong><?php echo esc_html( basename( get_attached_file( $this->get_value() ) ) ); ?></strong>
					</div>

				<?php endif; ?>

			</div>

			<button class="cmb-remove-file button <?php echo $this->get_value() ? '' : 'hidden'; ?>">
				<?php esc_html_e( 'Remove', 'cmb' ); ?>
			</button>

			<input type="hidden"
				<?php $this->class_attr( 'cmb-file-upload-input' ); ?>
				<?php $this->name_attr(); ?>
				value="<?php echo esc_attr( $this->value ); ?>"
			/>

		</div>

	<?php }

}

/**
 * Create new WPSight_Image_Multiple_Field sub class
 * for our custom field type image_multiple.
 *
 * @see https://github.com/humanmade/Custom-Meta-Boxes/blob/master/classes.fields.php#L494 => class CMB_Image_Field
 */

class WPSight_Image_Multiple_Field extends wpSight_File_Multiple_Field {
	
	// Return default args for the field type

	public function get_default_args() {
		return array_merge(
			parent::get_default_args(),
			array(
				'size' 			=> 'thumbnail',
				'library-type' 	=> array( 'image' ),
				'show_size' 	=> false
			)
		);
	}
	
	// Create HTML output for the field type

	public function html() {

		if ( $this->get_value() )
			$image = wp_get_attachment_image_src( $this->get_value(), $this->args['size'], true );

		// Convert size arg to array of width, height, crop
		$size = $this->parse_image_size( $this->args['size'] );

		// Inline styles
		$styles              = sprintf( 'width: %1$dpx; height: %2$dpx; line-height: %2$dpx', intval( $size['width'] ), intval( $size['height'] ) );
		$placeholder_styles  = sprintf( 'width: %dpx; height: %dpx;', intval( $size['width'] ) - 8, intval( $size['height'] ) - 8 );

		$data_type           = ( ! empty( $this->args['library-type'] ) ? implode( ',', $this->args['library-type'] ) : null ); ?>

		<div class="cmb-file-wrap" style="<?php echo esc_attr( $styles ); ?>" data-type="<?php echo esc_attr( $data_type ); ?>">

			<div class="cmb-file-wrap-placeholder" style="<?php echo esc_attr( $placeholder_styles ); ?>">

				<?php if ( $this->args['show_size'] ) : ?>
					<span class="dimensions">
						<?php printf( '%dpx &times; %dpx', intval( $size['width'] ), intval( $size['height'] ) ); ?>
					</span>
				<?php endif; ?>

			</div>

			<button class="button cmb-multiple-upload <?php echo esc_attr( $this->get_value() ) ? 'hidden' : '' ?>" data-nonce="<?php echo wp_create_nonce( 'cmb-file-upload-nonce' ); ?>">
				<?php esc_html_e( 'Add Images', 'cmb' ); ?>
			</button>

			<div class="cmb-file-holder type-img <?php echo $this->get_value() ? '' : 'hidden'; ?>" data-crop="<?php echo (bool) $size['crop']; ?>">

				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image[0] ); ?>" width="<?php echo intval( $image[1] ); ?>" height="<?php echo intval( $image[2] ); ?>" />
					<a href="<?php echo get_edit_post_link( esc_attr( $this->value ) ); ?>" class="cmb-edit-file button" target="_blank"><?php _e( 'Edit', 'wpsight' ); ?></a>
				<?php endif; ?>

			</div>

			<button class="cmb-remove-file button <?php echo $this->get_value() ? '' : 'hidden'; ?>">
				<?php esc_html_e( 'Remove', 'cmb' ); ?>
			</button>

			<input type="hidden"
				<?php $this->class_attr( 'cmb-file-upload-input' ); ?>
				<?php $this->name_attr(); ?>
				value="<?php echo esc_attr( $this->value ); ?>"
			/>

		</div>

	<?php }

	/**
	 * Parse the size argument to get pixel width, pixel height and crop information.
	 *
	 * @param  string $size
	 * @return array width, height, crop
	 */
	private function parse_image_size( $size ) {

		// Handle string for built-in image sizes

		if ( is_string( $size ) && in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			return array(
				'width'  => get_option( $size . '_size_w' ),
				'height' => get_option( $size . '_size_h' ),
				'crop'   => get_option( $size . '_crop' )
			);
		}

		// Handle string for additional image sizes

		global $_wp_additional_image_sizes;
		if ( is_string( $size ) && isset( $_wp_additional_image_sizes[$size] ) ) {
			return array(
				'width'  => $_wp_additional_image_sizes[$size]['width'],
				'height' => $_wp_additional_image_sizes[$size]['height'],
				'crop'   => $_wp_additional_image_sizes[$size]['crop']
			);
		}

		// Handle default WP size format

		if ( is_array( $size ) && isset( $size[0] ) && isset( $size[1] ) )
			$size = array( 'width' => $size[0], 'height' => $size[1] );

		return wp_parse_args( $size, array(
			'width'  => get_option( 'thumbnail_size_w' ),
			'height' => get_option( 'thumbnail_size_h' ),
			'crop'   => get_option( 'thumbnail_crop' )
		) );

	}

	/**
	 * Ajax callback for outputing an image src based on post data.
	 *
	 * @return null
	 */
	static function request_image_ajax_callback() {

		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'cmb-file-upload-nonce' ) ) )
			return;

		$id = intval( $_POST['id'] );

		$size = array(
			intval( $_POST['width'] ),
			intval( $_POST['height'] ),
			'crop' => (bool) $_POST['crop']
		);

		$image = wp_get_attachment_image_src( $id, $size );
		echo reset( $image );

		die(); // this is required to return a proper result

	}

}
add_action( 'wp_ajax_cmb_request_image', array( 'wpSight_Image_Multiple_Field', 'request_image_ajax_callback' ) );

/**
 * Create new WPSight_Info_Field class sub class
 * to create a new info field.
 *
 * @see https://github.com/humanmade/Custom-Meta-Boxes/wiki/Adding-your-own-field-types
 */
class WPSight_Info_Field extends CMB_Field {

	public function title() {}

	public function html() {
		?>

		<div class="cmb_metabox_description">
			<p <?php $this->class_attr(); ?>>
				<?php echo esc_html( $this->title ); ?>
			</p>
		</div>

		<?php

	}

}
