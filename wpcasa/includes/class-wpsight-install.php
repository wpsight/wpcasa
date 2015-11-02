<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Install class
 */
class WPSight_Install {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init_user_roles();
		$this->cron();
		delete_transient( 'wpsight_addons_html' );
		delete_transient( 'wpsight_themes_html' );
		update_option( 'wpsight_version', WPSIGHT_VERSION );
	}

	/**
	 * init_user_roles()
	 *
	 * Create custom agent roles.
	 *
	 * @access public
	 * @uses wpsight_agent_roles()
	 * @uses $wp_roles->add_cap()
	 * @uses add_role()
	 *
	 * @since 1.0.0
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
	 * cron()
	 *
	 * Setup custom cron jobs
	 *
	 * @uses wp_clear_scheduled_hook()
	 * @uses wp_schedule_event()
	 *
	 * @since 1.0.0
	 */
	public function cron() {
		
		// Handle delete previews cron		
		wp_clear_scheduled_hook( 'wpsight_delete_listing_previews' );
		wp_schedule_event( time(), 'daily', 'wpsight_delete_listing_previews' );

	}

}

new WPSight_Install();
