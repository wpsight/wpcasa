<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Install
 */
class WPSight_Install {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->init_user_roles();
		$this->cron();
		delete_transient( 'wpsight_addons_html' );
		delete_transient( 'wpsight_themes_html' );
		update_option( 'wpsight_version', WPSIGHT_VERSION );
	}

	/**
	 * Init user roles
	 *
	 * @access public
	 * @return void
	 */
	public function init_user_roles() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();

		if ( is_object( $wp_roles ) ) {
			
			$agent_roles = wpsight_agent_roles();
			
			// Add listing_admin caps to administrator
			
			foreach( $agent_roles['listing_admin']['caps'] as $cap => $v )
				$wp_roles->add_cap( 'administrator', $cap );
			
			// Add agent roles with caps
			
			foreach( $agent_roles as $role ) {
				
				add_role( $role['id'], $role['name'], $role['caps'] );
				
				/**
				 * Add level_1 to caps to show custom roles in author dropdown
				 * @see https://core.trac.wordpress.org/ticket/16841
				 */
				$user_role = get_role( $role['id'] );				
				$user_role->add_cap( 'level_1' );

			}
		}

	}

	/**
	 * Setup cron jobs
	 */
	public function cron() {
		
		// Handle delete previews cron
		
		wp_clear_scheduled_hook( 'wpsight_delete_listing_previews' );
		wp_schedule_event( time(), 'daily', 'wpsight_delete_listing_previews' );

	}
}

new WPSight_Install();
