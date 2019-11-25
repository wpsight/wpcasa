<?php
/*
Plugin Name: WPCasa Admin Map UI
Plugin URI: https://wpcasa.com/downloads/wpcasa-admin-map-ui
Description: Set the listing location by a click or drag & drop on the map and make individual map settings in the listing editor.
Version: 1.0.2
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.7.3
Text Domain: wpcasa-admin-map-ui
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Admin_Map_UI class
 */
class WPSight_Admin_Map_UI {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );
		
		if ( ! defined( 'WPSIGHT_SHOP_URL' ) )
			define( 'WPSIGHT_SHOP_URL', 'https://wpcasa.com' );

		if ( ! defined( 'WPSIGHT_AUTHOR' ) )
			define( 'WPSIGHT_AUTHOR', 'WPSight' );

		define( 'WPSIGHT_ADMIN_MAP_UI_NAME', 'WPCasa Admin Map UI' );
		define( 'WPSIGHT_ADMIN_MAP_UI_DOMAIN', 'wpcasa-admin-map-ui' );
		define( 'WPSIGHT_ADMIN_MAP_UI_VERSION', '1.0.2' );
		define( 'WPSIGHT_ADMIN_MAP_UI_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_ADMIN_MAP_UI_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		if ( is_admin() ){
			include( WPSIGHT_ADMIN_MAP_UI_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-map-ui-admin.php' );
			$this->admin = new WPSight_Admin_Map_UI_Admin();
		}

		// Actions		
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

	}

	/**
	 *	init()
	 *
	 *  Initialize the plugin when WPCasa is loaded
	 *
	 *  @param  object  $wpsight
	 *	@uses	do_action_ref_array()
	 *  @return object
	 *
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		if ( ! isset( $wpsight->admin_map_ui ) ) {
			$wpsight->admin_map_ui = new self();
		}
		do_action_ref_array( 'wpsight_init_admin_map_ui', array( &$wpsight ) );

		return $wpsight->admin_map_ui;
	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since 1.0.0
	 */

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-admin-map-ui', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
}

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Admin_Map_UI', 'init' ) );
