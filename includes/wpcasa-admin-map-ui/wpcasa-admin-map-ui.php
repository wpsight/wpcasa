<?php

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

        if ( ! defined( 'WPSIGHT_ADMIN_MAP_UI_PLUGIN_DIR' ) )
		define( 'WPSIGHT_ADMIN_MAP_UI_PLUGIN_DIR', WPSIGHT_PLUGIN_DIR . '/includes/wpcasa-admin-map-ui' );

        if ( ! defined( 'WPSIGHT_ADMIN_MAP_UI_PLUGIN_URL' ) )
		define( 'WPSIGHT_ADMIN_MAP_UI_PLUGIN_URL', WPSIGHT_PLUGIN_URL . '/includes/wpcasa-admin-map-ui' );

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
