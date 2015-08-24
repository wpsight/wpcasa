<?php
/*
Plugin Name: WPCasa
Plugin URI: http://wpcasa.com
Description: Real estate WordPress framework.
Version: 0.0.1
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 3.8
Tested up to: 4.1.1
Text Domain: wpsight
Domain Path: /languages

	Copyright: 2015 Simon Rimkus
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * wpSight class.
 */
class WPSight_Framework {

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() {

		// Define constants

		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );
		
		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );

		define( 'WPSIGHT_VERSION', '0.0.1' );
		define( 'WPSIGHT_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Cookie constants
	
		define( 'WPSIGHT_COOKIE_SEARCH_ADVANCED', WPSIGHT_DOMAIN . '_advanced_search' );
		define( 'WPSIGHT_COOKIE_SEARCH_QUERY', WPSIGHT_DOMAIN . '_search_query' );
		define( 'WPSIGHT_COOKIE_SEARCH_MAP', WPSIGHT_DOMAIN . '_search_map' );
		define( 'WPSIGHT_COOKIE_LISTINGS_COMPARE', WPSIGHT_DOMAIN . '_listings_compare' );
		
		// Include functions

		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-general.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-template.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-listings.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-agents.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-search.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-helpers.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-admin.php' );
		include( WPSIGHT_PLUGIN_DIR . '/functions/wpsight-meta-boxes.php' );
		
		// Include classes
		
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-post-types.php' );		
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-api.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-geocode.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-listings.php' );
		
		// Include shortcodes
		include( WPSIGHT_PLUGIN_DIR . '/includes/shortcodes/class-wpsight-shortcodes.php' );
		
		// Include admin class

		if ( is_admin() ) {
			include( WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin.php' );
			$this->admin = new WPSight_Admin();
		}
		
		// Init classes
		$this->post_types = new WPSight_Post_Type_Listing();

		// Activation
		
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this->post_types, 'register_post_type_listing' ), 10 );		
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), create_function( "", "include_once( 'includes/class-wpsight-install.php' );" ), 10 );
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'flush_rewrite_rules', 15 );
		
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this, 'activation' ) );

		// Actions
		
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'switch_theme', array( $this->post_types, 'register_post_type_listing' ), 10 );
		add_action( 'switch_theme', 'flush_rewrite_rules', 15 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		
		//add_action( 'admin_init', array( $this, 'updater' ) );

		do_action_ref_array( 'wpsight_init', array( &$this ) );

	}

	/**
	 * Handle Updates
	 */
	public function updater() {
		// if ( version_compare( WPSIGHT_VERSION, get_option( 'wpsight_version' ), '>' ) )
			// include_once( 'includes/class-wpsight-install.php' );
	}

	/**
	 * Localization
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpsight', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
		
		// Enqueue jQuery
		wp_enqueue_script( 'jquery' );
		
		wp_enqueue_script( 'jquery-tiptip', WPSIGHT_PLUGIN_URL . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		wp_enqueue_script( 'jquery-cookie', WPSIGHT_PLUGIN_URL . '/assets/js/jquery.cookie.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		wp_enqueue_script( 'wpsight-listings-search', WPSIGHT_PLUGIN_URL . '/assets/js/listings-search.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		
		// Localize scripts
	
		$data = array(
			'cookie_path' 			   => COOKIEPATH,
			'cookie_search_advanced'   => WPSIGHT_COOKIE_SEARCH_ADVANCED,
			'cookie_search_query'	   => WPSIGHT_COOKIE_SEARCH_QUERY,
			'cookie_listings_compare'  => WPSIGHT_COOKIE_LISTINGS_COMPARE
		);
		
		wp_localize_script( 'wpsight-listings-search', 'wpsight_localize', $data );

		wp_enqueue_style( 'wpsight-frontend', WPSIGHT_PLUGIN_URL . '/assets/css/frontend.css' );
	}
	
	/**
	 * activation()
	 *
	 * Callback for register_activation_hook
	 * to create a default listings page with
	 * the [wpsight_listings] shortcode and
	 * to create some default options to be
	 * used by this plugin.
	 *
	 * @uses wpsight_get_option()
	 * @uses wp_insert_post()
	 * @uses wpsight_add_option()
	 *
	 * @since 1.0.0
	 */
	
	public function activation() {
		
		// Create listings page
		
		$page_data = array(
			'post_title'     => _x( 'Listings', 'listings page title', 'wpsight' ),
			'post_content'   => '[wpsight_listings]',
			'post_type'      => 'page',
			'post_status'	 => 'publish',
			'comment_status' => 'closed',
			'ping_status'	 => 'closed'
		);
		
		$page_id = ! wpsight_get_option( 'listings_page' ) ? wp_insert_post( $page_data ) : false;
		
		// Add some default options
		
		$options = array(
			'listings_page' => $page_id
		);
		
		foreach( $options as $option => $value ) {
			
			if( wpsight_get_option( $option ) )
				continue;
			
			wpsight_add_option( $option, $value );

		}
		
	}
}

/**
 *  wpsight()
 *
 *  The main function responsible for returning the one true wpsight Instance to functions everywhere.
 *  Use this function like you would a global variable, except without needing to declare the global.
 *
 *  Example: <?php $wpsight = wpsight(); ?>
 *
 *  @return  object $wpsight
 */
function wpsight() {

	global $wpsight;
	
	if ( !isset( $wpsight ) ){
		$wpsight = new WPSight_Framework();
	}
	
	return $wpsight;
}

wpsight();
