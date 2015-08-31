<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_Admin_Agents class
 */
class WPSight_Admin_Agents {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_filter( 'user_contactmethods', array( $this, 'profile_contact_fields' ) );
		
		add_action( 'show_user_profile', array( $this, 'profile_agent_logo' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_agent_logo' ) );
		
		add_action( 'personal_options_update', array( $this, 'profile_agent_logo_save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_agent_logo_save' ) );
		
		add_action( 'show_user_profile', array( $this, 'profile_agent_exclude' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_agent_exclude' ) );
		
		add_action( 'personal_options_update', array( $this, 'profile_agent_exclude_save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_agent_exclude_save' ) );
		
		add_action( 'show_user_profile', array( $this, 'profile_agent_update' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_agent_update' ) );
		
		add_action( 'personal_options_update', array( $this, 'profile_agent_update_save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_agent_update_save' ) );
		
		add_action( 'pre_get_posts', array( $this, 'filter_media_files' ) );
		add_filter( 'wp_count_attachments', array( $this, 'recount_attachments' ) );

	}
	
	/**
	 * profile_contact_fields()
	 *
	 * Add custom user profile fields
	 * using user_contactmethods filter hook.
	 *
	 * @since 1.0.0
	 */
	public function profile_contact_fields( $fields ) {
		
		// Add custom fields
		
		foreach( wpsight_profile_contact_fields() as $k => $v )
			$fields[$k]	= $v['label'];
		
		return apply_filters( 'wpsight_do_profile_contact_fields', $fields );
	}
	
	/**
	 * profile_agent_logo()
	 *
	 * Add agent image option to profile
	 *
	 * @param object $user The WP_User object of the user being edited
	 * @uses current_user_can()
	 * @uses get_the_author_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_logo( $user ) {
		
		if ( ! current_user_can( 'edit_user', $user->ID ) )
	        return false; ?>
	    
	    <h3>Listing Agent</h3>
	
	    <table class="form-table">
	        <tr>
	            <th><label for="agent_logo"><?php _e( 'Agent Image', 'wpsight' ); ?></label></th>
	            <td>
	            <style type="text/css">
					.agent_logo_field .field-title { 
						display: none; 
					}
					.CMB_Image_Field.postbox { 
						box-shadow: none;
						-webkit-box-shadow: none;
						border: none;
					}
	            </style>
	            	<?php 
	            	// load the existing value
	            	$field_value = get_user_meta( $user->ID, 'agent_logo_id', true );
	            	// intialize an image field for the agent image
	            	$agent_image_field = new CMB_Image_Field( 'agent_logo_id', 'Agent Image', array( $field_value ) );
	            	// enque scripts of the field
	            	$agent_image_field->enqueue_scripts(); ?>

	            	<div class="CMB_Image_Field postbox agent_logo_field">
	            		<?php // render the image field uploader
	            		$agent_image_field->display() ?>
	            	</div>
	            	<p><?php _e( 'Setting the agent image here will serve as a default image for every listing of that agent. ', 'wpsight' ); ?></p>
	            	<p><?php _e( 'Associating an image at the listing itself will override the default image.', 'wpsight' ); ?></p>
	            </td>
	        </tr>
	    </table><?php
	    
	}
	
	/**
	 * profile_agent_logo_save()
	 *
	 * Save agent image option on profile pages
	 *
	 * @param interger $user_id The user ID of the user being edited
	 * @uses current_user_can()
	 * @uses update_user_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_logo_save( $user_id ) {
	
	    if ( ! current_user_can( 'edit_user', $user_id ) )
	        return false;
	        
		$agent_logo_id = isset( $_POST['agent_logo_id']['cmb-field-0'] ) ? $_POST['agent_logo_id']['cmb-field-0'] : false;
		$agent_logo = (array) wp_get_attachment_image_src( $agent_logo_id, 'full' );
	
	    update_user_meta( $user_id, 'agent_logo', current( $agent_logo ) );
	    update_user_meta( $user_id, 'agent_logo_id', sanitize_text_field( $agent_logo_id ) );
	
	}
	
	/**
	 * profile_agent_exclude()
	 *
	 * Add exclude agent from lists option to profile
	 *
	 * @param object $user The WP_User object of the user being edited
	 * @uses get_the_author_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_exclude( $user ) {
		
		if ( ! current_user_can( 'listing_admin' ) && ! current_user_can( 'administrator' ) )
	        return false; ?>
	
	    <table class="form-table">
	        <tr>
	            <th><label for="agent_exclude"><?php _e( 'Agent Lists', 'wpsight' ); ?></label></th>
	            <td>
	                <input type="checkbox" value="1" name="agent_exclude" id="agent_exclude" style="margin-right:5px" <?php checked( get_the_author_meta( 'agent_exclude', $user->ID ), 1 ); ?>> <?php _e( 'Hide this user from agent lists', 'wpsight' ); ?>
	            </td>
	        </tr>
	    </table><?php
	    
	}
	
	/**
	 * profile_agent_exclude_save()
	 *
	 * Save exclude agent option on profile pages
	 *
	 * @param interger $user_id The user ID of the user being edited
	 * @uses current_user_can()
	 * @uses update_user_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_exclude_save( $user_id ) {
	
	    if ( ! current_user_can( 'listing_admin' ) && ! current_user_can( 'administrator' ) )
	        return false;
	        
		$_POST['agent_exclude'] = isset( $_POST['agent_exclude'] ) ? $_POST['agent_exclude'] : false;
	
	    update_user_meta( $user_id, 'agent_exclude', $_POST['agent_exclude'] );
	
	}
	
	/**
	 * profile_agent_update()
	 *
	 * Add update agent data in listings option to profile
	 *
	 * @param object $user The WP_User object of the user being edited
	 * @uses get_the_author_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_update( $user ) {
		
		if ( ! current_user_can( 'listing_admin' ) && ! current_user_can( 'administrator' ) )
	        return false; ?>
	
	    <table class="form-table">
	        <tr>
	            <th><label for="agent_exclude"><?php _e( 'Agent Update', 'wpsight' ); ?></label></th>
	            <td>
	                <input type="checkbox" value="1" name="agent_update" id="agent_update" style="margin-right:5px">
	                <?php _e( 'Update agent info in all listings created by this user', 'wpsight' ); ?>
	            </td>
	        </tr>
	    </table><?php
	    
	}
	
	/**
	 * profile_agent_update_save()
	 *
	 * Save update agent data in listings option on profile pages.
	 * When the option is checked, the agent information of all
	 * listings of this user will be updated with the profile info.
	 *
	 * @param interger $user_id The user ID of the user being edited
	 * @uses current_user_can()
	 * @uses update_user_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_update_save( $user_id ) {
	
	    if ( ! current_user_can( 'listing_admin' ) && ! current_user_can( 'administrator' ) )
	        return false;
	        
		$_POST['agent_update'] = isset( $_POST['agent_update'] ) ? $_POST['agent_update'] : false;
		
		if( $_POST['agent_update'] ) {
			
			// Get all listings created by this user
			$user_listings = wpsight_get_user_posts_by_type( $user_id, wpsight_post_type() );
			
			// Map listing agent options with profile info
			
			$agent_options = array(
				'_agent_name' 		 => $_POST['display_name'],
				'_agent_company' 	 => $_POST['company'],
				'_agent_description' => $_POST['description'],
				'_agent_website' 	 => $_POST['url'],
				'_agent_twitter' 	 => $_POST['twitter'],
				'_agent_facebook' 	 => $_POST['facebook'],
				'_agent_logo' 	 	 => $_POST['agent_logo'],
				'_agent_logo_id' 	 => $_POST['agent_logo_id']
			);
			
			$agent_options = apply_filters( 'profile_agent_update_save_options', $agent_options, $user_id );
			
			// Loop through user listings
			
			foreach( (array) $user_listings as $post_id ) {
				
				// Loop through listing agent options
				
				foreach( (array) $agent_options as $option => $getpost )
					update_post_meta( $post_id, $option, $getpost );

			}
			
		}
	
	}
	
	/**
	 * media_library_restrict()
	 *
	 * Restrict media library to items uploaded
	 * by the current user if he does not have
	 * 'read_private_listings' capability.
	 *
	 * @since 1.0.0
	 */
	public function media_library_restrict( $wp_query ) {
		
	    if ( 'upload' != get_current_screen()->id )
	    	return;
		    
		if( current_user_can( 'edit_listings' ) && ! current_user_can( 'read_private_listings' ) )
			$wp_query->set( 'author', get_current_user_id() );

	}
	
	/**
	 * filter_media_files()
	 *
	 * Restrict media library to items uploaded
	 * by the current user if he does not have
	 * 'read_private_listings' capability.
	 *
	 * @author Damir Calusic
	 * @see https://wordpress.org/plugins/wp-users-media/
	 *
	 * @since 1.0.0
	 */

	public function filter_media_files( $wp_query ) {
		global $current_user;
	
		if( ! current_user_can( 'read_private_listings' ) && ( is_admin() && $wp_query->query['post_type'] === 'attachment' ) )
			$wp_query->set( 'author', $current_user->ID );

	}
	
	/**
	 * recount_attachments()
	 *
	 * Recount attachments on media
	 * library screen and elsewhere.
	 *
	 * @author Damir Calusic
	 * @see https://wordpress.org/plugins/wp-users-media/
	 *
	 * @since 1.0.0
	 */
	
	function recount_attachments( $_counts ) {
		global $wpdb, $current_user;
	
		$and = wp_post_mime_type_where(''); //Default mime type //AND post_author = {$current_user->ID}
		$count = $wpdb->get_results( "SELECT post_mime_type, COUNT( * ) AS num_posts FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_author = {$current_user->ID} $and GROUP BY post_mime_type", ARRAY_A );
	
		$counts = array();
		foreach( (array) $count as $row )
			$counts[ $row['post_mime_type'] ] = $row['num_posts'];
	
		$counts['trash'] = $wpdb->get_var( "SELECT COUNT( * ) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_author = {$current_user->ID} AND post_status = 'trash' $and" );

		return $counts;

	}

}
