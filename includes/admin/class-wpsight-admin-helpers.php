<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Helpers class
 */
class WPSight_Admin_Helpers {
	
	/**
	 *	Constructor
	 */
	public function __construct() {}
			
	/**
	 *	get_plugin_activation_link()
	 *
	 *	Generate an activation URL for a plugin like the ones
	 *	found in WordPress plugin administration screen.
	 *
	 *	@uses	get_user_option()
	 *	@uses	get_current_user_id()
	 *
	 *	@param  string	$plugin	A plugin-folder/plugin-main-file.php path (e.g. "my-plugin/my-plugin.php")
	 *
	 *	@return string	The plugin activation url
	 *
	 *	@since 1.1.0
	 */
	public function get_plugin_activation_link( $plugin ) {
		
		// the plugin might be located in the plugin folder directly
	
		if ( strpos( $plugin, '/' ) ) {
			$plugin = str_replace( '/', '%2F', $plugin );
		}
	
		$url = sprintf( admin_url('plugins.php?action=activate&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
	
		// change the plugin request to the plugin to pass the nonce check
		$_REQUEST['plugin'] = $plugin;
		$url = wp_nonce_url( $url, 'activate-plugin_' . $plugin );
	
		return $url;
		
	}
	
	public function action_link( $plugin, $action = 'activate' ) {
		
		if ( strpos( $plugin, '/' ) )
			$plugin = str_replace( '\/', '%2F', $plugin );
		
		$_REQUEST['plugin'] = $plugin;
		
		$url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
		$url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
		
		return $url;
		
	}
	
}