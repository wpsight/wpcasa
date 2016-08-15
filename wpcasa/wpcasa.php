<?php
/*
Plugin Name: WPCasa
Plugin URI: https://wpcasa.com
Description: Flexible WordPress plugin to create professional real estate websites and manage property listings with ease.
Version: 1.0.6.1
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.6
Text Domain: wpcasa
Domain Path: /languages

	Copyright: 2015 Simon Rimkus
	License: GNU General Public License v2.0 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/vendor/autoload.php';

/**
 * WPSight_Framework class
 */
class WPSight_Framework {

	/**
	 *	Constructor - get the plugin hooked in and ready
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

		define( 'WPSIGHT_VERSION', '1.0.6.1' );
		define( 'WPSIGHT_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Cookie constants
	
		define( 'WPSIGHT_COOKIE_SEARCH_QUERY', WPSIGHT_DOMAIN . '_search_query' );
		define( 'WPSIGHT_COOKIE_SEARCH_MAP', WPSIGHT_DOMAIN . '_search_map' );
		
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
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-agents.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-general.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-helpers.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-search.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-meta-boxes.php' );
		include( WPSIGHT_PLUGIN_DIR . '/includes/class-wpsight-template.php' );
		
		// Include shortcodes
		include( WPSIGHT_PLUGIN_DIR . '/includes/shortcodes/class-wpsight-shortcodes.php' );
		
		// Include admin class
		include( WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin.php' );

		// Only instantiate admin class when in admin area
		if ( is_admin() )
			$this->admin = new WPSight_Admin();
		
		// Init classes
		$this->post_types = new WPSight_Post_Type_Listing();
		$this->agents     = new WPSight_Agents();
		$this->general    = new WPSight_General();
		$this->helpers    = new WPSight_Helpers();
		$this->search     = new WPSight_Search();
		$this->meta_boxes = new WPSight_Meta_Boxes();

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
		
		// Init action for add-ons to hook in
		do_action_ref_array( 'wpsight_init', array( &$this ) );

	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up the text domain for the plugin
	 *	and load language files.
	 *	
	 *	@uses	plugin_basename()
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue scripts and css.
	 *	
	 *	@uses	wp_enqueue_script()
	 *	@uses	wp_localize_script()
	 *	@uses	wp_enqueue_style()
	 *	@uses	wpsight_get_option()
	 *	
	 *	@since 1.0.0
	 */
	public function frontend_scripts() {
		
		// Enqueue jQuery
		wp_enqueue_script( 'jquery' );
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script( 'jquery-tiptip', WPSIGHT_PLUGIN_URL . '/assets/js/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		wp_enqueue_script( 'jquery-cookie', WPSIGHT_PLUGIN_URL . '/assets/js/jquery.cookie.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		wp_enqueue_script( 'wpsight-listings-search', WPSIGHT_PLUGIN_URL . '/assets/js/wpsight-listings-search.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		
		// Localize scripts
	
		$data = array(
			'cookie_path'			=> COOKIEPATH,
			'cookie_search_query'	=> WPSIGHT_COOKIE_SEARCH_QUERY
		);
		
		wp_localize_script( 'wpsight-listings-search', 'wpsight_localize', $data );
		
		// Enqueue Google Maps (optionally with API key)
		
		if( true == apply_filters( 'wpsight_google_maps', true ) ) {
			
			$api_key = wpsight_get_option( 'google_maps_api_key' );
			
			$api_url = $api_key ? add_query_arg( array( 'key' => $api_key ), '//maps.googleapis.com/maps/api/js' ) : '//maps.googleapis.com/maps/api/js';
		
			wp_enqueue_script( 'wpsight-map-googleapi', apply_filters( 'wpsight_google_maps_endpoint', esc_url( $api_url ), $api_key ), null, WPSIGHT_VERSION );
		
		}		
		
		if( true == apply_filters( 'wpsight_css', true ) && wpsight_get_option( 'listings_css' ) ) {

			wp_enqueue_style( 'wpsight', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight' . $suffix . '.css' );
			
			if ( is_rtl() )
				wp_enqueue_style( 'wpsight-rtl', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-rtl' . $suffix . '.css' );
		
		}

	}
	
	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook
	 *	to create a default listings page with
	 *	the [wpsight_listings] shortcode and
	 *	to create some default options to be
	 *	used by this plugin.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wp_insert_post()
	 *	@uses	wpsight_add_option()
	 *	
	 *	@since 1.0.0
	 */
	
	public function activation() {
		
		// Create listings page
		
		$page_data = array(
			'post_title'     => _x( 'Listings', 'listings page title', 'wpcasa' ),
			'post_content'   => '[wpsight_listings]',
			'post_type'      => 'page',
			'post_status'	 => 'publish',
			'comment_status' => 'closed',
			'ping_status'	 => 'closed'
		);
		
		$page_id = ! wpsight_get_option( 'listings_page' ) ? wp_insert_post( $page_data ) : false;
		
		// Add some default options
		
		$options = array(
			'listings_page'			=> $page_id,
			'listing_id'			=> __( 'ID-', 'wpcasa' ),
			'measurement_unit'		=> 'm2',
			'currency'				=> 'usd',
			'currency_symbol'		=> 'before',
			'currency_separator'	=> 'comma',
			'date_format'			=> get_option( 'date_format' ),
			'listings_css'			=> '1'
		);
		
		// Add default standard features
		foreach ( wpsight_details() as $option => $value )
			$options[ $option ] = array( 'label' => $value['label'], 'unit' => $value['unit'] );
		
		// Add default rental periods
		foreach ( wpsight_rental_periods() as $option => $value )
			$options[ $option ] = $value;
		
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
 *  Use this function like you would use a global variable, except without needing to declare the global.
 *
 *  Example: <?php $wpsight = wpsight(); ?>
 *
 *  @return  object $wpsight
 */
function wpsight() {
	global $wpsight;
	
	// Don't activate plugin add-ons if theme still active
	
	if( wp_get_theme()->template == 'wpcasa' ) {
		remove_all_actions( 'wpsight_init' );
		return false;
	}
	
	if ( ! isset( $wpsight ) )
		$wpsight = new WPSight_Framework();
	
	return $wpsight;
}

wpsight();

/**
 *	wpsight_admin_notice_wpcasa()
 *	
 *	Make sure users first deactivate
 *	the old WPCasa theme version.
 *	Display error message if it is
 *	still activated.
 *	
 *	@uses	wp_get_theme()
 *	
 *	@since 1.0.0
 */
add_action( 'admin_notices', 'wpsight_admin_notice_wpcasa' );

function wpsight_admin_notice_wpcasa() {
	
	if( wp_get_theme()->template != 'wpcasa' )
		return;
	
	echo '<div class="error"><p>' . __( 'Please make sure to <strong>deactivate the WPCasa theme</strong> in order to use the WPCasa plugin version. For more information about how to switch please <a href="http://docs.wpsight.com/article/switching-from-theme-version/" target="_blank">read our docs</a>.', 'wpcasa' ) . '</p></div>';
	
}
