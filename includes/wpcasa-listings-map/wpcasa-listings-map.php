<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listings_Map class
 */
class WPSight_Listings_Map {

	/**
	 *	Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );


//		TODO: check if we need that constants till wpcasa 1.5
		define( 'WPSIGHT_LISTINGS_MAP_NAME', 'WPCasa Listings Map' );
		define( 'WPSIGHT_LISTINGS_MAP_DOMAIN', 'wpcasa-listings-map' );
		define( 'WPSIGHT_LISTINGS_MAP_VERSION', '1.2.1' );

        define( 'WPSIGHT_LISTINGS_MAP_PLUGIN_DIR', WPSIGHT_PLUGIN_DIR . '/includes/wpcasa-listings-map' );
        define( 'WPSIGHT_LISTINGS_MAP_PLUGIN_URL', WPSIGHT_PLUGIN_URL . '/includes/wpcasa-listings-map' );

		// Cookie constants

		define( 'WPSIGHT_LISTINGS_MAP_COOKIE', WPSIGHT_DOMAIN . '_listings_map' );

		// Include functions
		include 'wpcasa-listings-map-functions.php';

		// Include styles
		include 'includes/class-wpsight-listings-map-styles.php';
		
		// Include shortcode
		include 'includes/class-wpsight-listings-map-shortcode.php';
		
		// Include admin part
		
		if ( is_admin() ) {
			include( WPSIGHT_LISTINGS_MAP_PLUGIN_DIR . '/includes/admin/class-wpsight-listings-map-admin.php' );
			$this->admin = new WPSight_Listings_Map_Admin();
		}

		// Actions

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		
		// Display Show/hide map link in listings panel
		add_action( 'wpsight_listings_panel_actions', array( $this, 'panel_map_link' ) );
		
		// Add map after listings panel
		add_filter( 'wpsight_get_panel', array( $this, 'panel_map' ), 10, 2 );

	}

	/**
	 *	init()
	 *	
	 *	Initialize the plugin when WPCasa is loaded
	 *	
	 *	@param	object	$wpsight
	 *	@return	object	$wpsight->listings_map
	 *	
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->listings_map ) )
			$wpsight->listings_map = new self();

		do_action_ref_array( 'wpsight_init_listings_map', array( &$wpsight ) );

		return $wpsight->listings_map;

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
		load_plugin_textdomain( 'wpcasa-listings-map', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue JS scripts and CSS styles.
	 *	Also localize some JS to use PHP constants.
	 *	
	 *	@uses	wp_enqueue_script()
	 *	@uses	wp_enqueue_style()
	 *	
	 *	@since 1.0.0
	 */
	public function frontend_scripts() {

		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'wpsight-listings-map', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/css/wpsight-listings-map.css', array(), WPSIGHT_LISTINGS_MAP_VERSION );
		wp_register_script( 'wpsight-map-googleapi', '//maps.googleapis.com/maps/api/js', null, WPSIGHT_LISTINGS_MAP_VERSION );
		wp_register_script( 'wpsight-map-infobox', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/js/infobox.js', array( 'wpsight-map-googleapi' ), WPSIGHT_LISTINGS_MAP_VERSION );
		wp_register_script( 'wpsight-map-markerclusterer', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/js/markerclusterer.js', array( 'wpsight-map-googleapi' ), WPSIGHT_LISTINGS_MAP_VERSION );
		wp_register_script( 'wpsight-listings-map', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/js/wpcasa-listings-map.js', array( 'wpsight-map-googleapi', 'wpsight-map-markerclusterer', 'wpsight-map-infobox' ), WPSIGHT_LISTINGS_MAP_VERSION );

	}

	/**
	 *	panel_map_link()
	 *	
	 *	Add Show Map link to listings panel.
	 *
	 *	@uses	wpsight_get_option()
	 *	
	 *	@since 1.1.0
	 */
	public function panel_map_link() {

        if( ! wpsight_get_option( 'listings_map_panel' ) )
            return;

		$link = sprintf( '<div class="listings-panel-action"><a href="#" class="toggle-map">%1$s</a></div>', apply_filters( 'wpsight_listings_panel_map_link_label', wpsight_get_option( 'listings_map_panel_link', __( 'Toggle Map', 'wpcasa-listings-map' ) ) ) );
		
		echo apply_filters( 'wpsight_listings_panel_map_link', $link );
		
	}
	
	/**
	 *	panel_map()
	 *	
	 *	Add map output after listings panel.
	 *
	 *	@param	string	$panel	Listings panel output
	 *	@param	object	$query	Listings query
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_listings_map()
	 *	@return	string
	 *	
	 *	@since 1.1.0
	 */
	public function panel_map( $panel, $query ) {
        if ( wpsight_get_option('listings_map_display') == 1 )  {
            if( isset( $query->post_count ) && $query->post_count >= 1 ) {
                $args = array(
                    'map_id'		=> uniqid( 'listings-panel-' ),
                    'toggle'		=> false,
                    'toggle_button'	=> 'listings-panel',
                );

                $panel_map = wpsight_get_listings_map( $args, $query );

                $panel = $panel . $panel_map;

            }
        }

		return $panel;
	}
}


// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Listings_Map', 'init' ) );
