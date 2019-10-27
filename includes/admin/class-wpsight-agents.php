<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Agents class
 */
class WPSight_Admin_Agents {

	/**
	 *	Constructor
	 */
	public function __construct() {
		
		add_action( 'personal_options_update', array( $this, 'profile_agent_update_save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_agent_update_save' ) );
		
		add_action( 'pre_get_posts', array( $this, 'filter_media_files' ) );
		add_filter( 'wp_count_attachments', array( $this, 'recount_attachments' ) );

	}
	
	/**
	 *	profile_agent_update_save()
	 *	
	 *	Save update agent data in listings option on profile pages.
	 *	When the option is checked, the agent information of all
	 *	listings of this user will be updated with the profile info.
	 *	
	 *	@param	interger	$user_id	The user ID of the user being edited
	 *	@uses	current_user_can()
	 *	@uses	wpsight_post_type()
	 *	@uses	wpsight_get_user_posts_by_type()
	 *	@uses	wp_get_attachment_url()
	 *	@uses	update_user_meta()
	 *
	 * @since 1.0.0
	 */
	public function profile_agent_update_save( $user_id ) {
	
	    if ( ! current_user_can( 'listing_admin' ) && ! current_user_can( 'administrator' ) )
	        return false;
	        
		$agent_update = isset( $_POST['agent_update'] ) ? $_POST['agent_update'] : false;
		
		if( $agent_update ) {
			
			// Get all listings created by this user
			$user_listings = wpsight_get_user_posts_by_type( $user_id, wpsight_post_type() );
			
			// Map listing agent options with profile info
			
			$agent_options = array(
				'_agent_name'			=> isset( $_POST['display_name'] ) ? 	sanitize_text_field( $_POST['display_name'] ) : '',
				'_agent_company'		=> isset( $_POST['company'] ) ? 		sanitize_text_field( $_POST['company'] ) : '',
				'_agent_phone'			=> isset( $_POST['phone'] ) ? 			sanitize_text_field( $_POST['phone'] ) : '',
				'_agent_description'	=> isset( $_POST['description'] ) ? 	wp_kses_post( $_POST['description'] ) : '',
				'_agent_website'		=> isset( $_POST['url'] ) ? 			esc_url_raw( $_POST['url'] ) : '',
				'_agent_twitter'		=> isset( $_POST['twitter'] ) ? 		sanitize_text_field( $_POST['twitter'] ) : '',
				'_agent_facebook'		=> isset( $_POST['facebook'] ) ? 		sanitize_text_field( $_POST['facebook'] ) : '',
				'_agent_logo'			=> isset( $_POST['agent_logo'] ) ? 		sanitize_text_field( $_POST['agent_logo'] ) : '',
				'_agent_logo_id'		=> isset( $_POST['agent_logo_id'] ) ? 	sanitize_text_field( $_POST['agent_logo_id'] ) : ''
			);
			
			$agent_options = apply_filters( 'wpsight_profile_agent_update_save_options', $agent_options, $user_id );
			
			// Loop through user listings
			
			foreach( (array) $user_listings as $post_id ) {
				
				// Loop through listing agent options
				
				foreach( (array) $agent_options as $option => $getpost ) {
					
					// Add filter before actually saving the data
					$getpost = apply_filters( 'wpsight_profile_agent_update_post_meta', $getpost, $option, $post_id, $agent_options );

					// Update post meta
					update_post_meta( $post_id, $option, $getpost );

				}

			}
			
			// Remove update value to let checkbox unchecked

			update_user_meta( $user_id, 'agent_update', '' );
			$_POST['agent_update'] = '';
			
		}
	
	}
	
	/**
	 *	media_library_restrict()
	 *	
	 *	Restrict media library to items uploaded
	 *	by the current user if he does not have
	 *	'read_private_listings' capability.
	 *	
	 *	@param	object	WP_Query
	 *	@uses	get_current_screen()
	 *	@uses	current_user_can()
	 *	@uses	get_current_user_id()
	 *	
	 *	@since 1.0.0
	 */
	public function media_library_restrict( $wp_query ) {
		
	    if ( 'upload' != get_current_screen()->id )
	    	return;
		    
		if( current_user_can( 'edit_listings' ) && ! current_user_can( 'read_private_listings' ) && $wp_query->query['post_type'] === 'attachment' )
			$wp_query->set( 'author', get_current_user_id() );

	}
	
	/**
	 *	filter_media_files()
	 *	
	 *	Restrict media library to items uploaded
	 *	by the current user if he does not have
	 *	'read_private_listings' capability.
	 *	
	 *	@param	object	WP_Query
	 *	@uses	current_user_can()
	 *	@author	Damir Calusic
	 *	@see	https://wordpress.org/plugins/wp-users-media/
	 *	
	 *	@since 1.0.0
	 */

	public function filter_media_files( $wp_query ) {
		global $current_user;
	
		if( ! current_user_can( 'read_private_listings' ) && ( is_admin() && $wp_query->query['post_type'] === 'attachment' ) )
			$wp_query->set( 'author', $current_user->ID );

	}
	
	/**
	 *	recount_attachments()
	 *	
	 *	Recount attachments on media
	 *	library screen and elsewhere.
	 *	
	 *	@author	Damir Calusic
	 *	@see	https://wordpress.org/plugins/wp-users-media/
	 *	
	 *	@since 1.0.0
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
