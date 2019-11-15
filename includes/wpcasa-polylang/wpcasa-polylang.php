<?php
/*
Plugin Name: WPCasa Polylang
Plugin URI: https://wpcasa.com/downloads/wpcasa-polylang
Description: Add support for Polylang to manage WPCasa property data in multiple languages.
Version: 1.1.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.9
Text Domain: wpcasa-polylang
Domain Path: /languages

	Copyright: 2015 Simon Rimkus
	License: GNU General Public License v2.0 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Polylang class
 */
class WPSight_Polylang {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define constants

		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );

		define( 'WPSIGHT_POLYLANG_NAME', 'WPCasa Polylang' );
		define( 'WPSIGHT_POLYLANG_DOMAIN', 'wpcasa-polylang' );
		define( 'WPSIGHT_POLYLANG_VERSION', '1.1.0' );
		define( 'WPSIGHT_POLYLANG_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_POLYLANG_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		if ( is_admin() ){
			include( WPSIGHT_POLYLANG_PLUGIN_DIR . '/includes/admin/class-wpsight-polylang-admin.php' );
			$this->admin = new WPSight_Polylang_Admin();
		}

		// Load text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Register text strings with Polylang

		add_action( 'admin_init', array( &$this, 'details_register' ) );
		add_action( 'admin_init', array( &$this, 'rental_periods_register' ) );
		add_action( 'admin_init', array( &$this, 'listing_labels_register' ) );
//
//		// Filter text strings to be translated
//
		add_filter( 'wpsight_details', array( &$this, 'details_filter' ), 25 );
		add_filter( 'wpsight_rental_periods', array( &$this, 'rental_periods_filter' ), 25 );
		add_filter( 'wpsight_listing_labels', array( &$this, 'listing_labels_filter' ), 25 );
//
//		// Convert some permalinks set in options (e.g. search results page)
		add_filter( 'wpsight_get_option', array( &$this, 'options_pages' ), 10, 2 );
//
//		// Make some dashboard settings
//
		add_filter( 'wpsight_get_dashboard_listings_args', array( &$this, 'dashboard_listings' ) );
		add_filter( 'wpsight_dashboard_columns', array( &$this, 'dashboard_columns' ) );
		add_action( 'wpsight_dashboard_column_lang', array( &$this, 'dashboard_column_lang' ) );

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
		if ( ! isset( $wpsight->polylang ) ) {
			$wpsight->polylang = new self();
		}
		do_action_ref_array( 'wpsight_init_polylang', array( $wpsight ) );

		return $wpsight->polylang;
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
		load_plugin_textdomain( 'wpcasa-polylang', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *	details_register()
	 *
	 *	Register listing details
	 *	for Polylang strings translation.
	 *
	 *	@uses	wpsight_get_detail()
	 *	@uses	pll_register_string()
	 *	@return	array
	 *
	 *	@since 1.0.0
	 */
	public function details_register() {

		if( function_exists( 'pll_register_string' ) ) {

			$details = wpsight_details();

			foreach( $details as $key => $detail )
				pll_register_string( 'Listing Details', wpsight_get_detail( $key, 'label' ), WPSIGHT_NAME );

		}

	}

	/**
	 *	details_filter()
	 *
	 *	Filter listing details
	 *	to be translated by Polylang.
	 *
	 *	@uses	pll__()
	 *	@return array
	 *
	 *	@since 1.0.0
	 */
	public function details_filter( $details ) {

		if( function_exists( 'pll__' ) ) {

			foreach( $details as $k => $v )
				$details[ $k ]['label'] = pll__( $details[ $k ]['label'] );

		}

		return $details;

	}

	/**
	 *	rental_periods_register()
	 *
	 *	Register rental periods
	 *	for Polylang strings translation.
	 *
	 *	@uses	wpsight_rental_periods()
	 *	@uses	wpsight_get_rental_period()
	 *	@uses	pll_register_string()
	 *
	 *	@since 1.0.0
	 */
	public function rental_periods_register() {

		if( function_exists( 'pll_register_string' ) ) {

			$periods = wpsight_rental_periods();

			foreach( $periods as $period => $label ) {
				$rental_period = wpsight_get_rental_period( $period );
				pll_register_string( 'Listing Periods', $rental_period, WPSIGHT_NAME );
			}

		}

	}

	/**
	 *	rental_periods_filter()
	 *
	 *	Filter rental periods
	 *	to be translated by Polylang.
	 *
	 *	@uses	pll__()
	 *
	 *	@since 1.0.0
	 */
	public function rental_periods_filter( $periods ) {

		if( function_exists( 'pll__' ) ) {

			foreach( $periods as $k => $v )
				$periods[ $k ] = pll__( $periods[ $k ] );

		}

		return $periods;

	}

	/**
	 *	listing_labels_register()
	 *
	 *	Register listing labels
	 *	for Polylang strings translation.
	 *
	 *	@uses	wpsight_listing_labels()
	 *	@uses	wpsight_get_label()
	 *	@uses	pll_register_string()
	 *
	 *	@since 1.0.0
	 */
	public function listing_labels_register() {

		if( ! function_exists( 'wpsight_listing_labels' ) || ! function_exists( 'pll_register_string' ) )
			return false;

		$labels = wpsight_listing_labels();

		foreach( $labels as $key => $label )
			pll_register_string( 'Listing Labels', wpsight_get_label( $key, 'label' ), WPSIGHT_NAME . ' Listing Labels' );

		return $labels;

	}

	/**
	 *	listing_labels_filter()
	 *
	 *	Filter listing labels
	 *	to be translated by Polylang.
	 *
	 *	@uses	pll__()
	 *
	 *	@since 1.0.0
	 */
	public function listing_labels_filter( $labels ) {

		if( ! function_exists( 'wpsight_listing_labels' ) )
			return false;

		if( function_exists( 'pll__' ) ) {

			foreach( $labels as $k => $v )
				$labels[ $k ]['label'] = pll__( $labels[ $k ]['label'] );

		}

		return $labels;

	}

	/**
	 *	options_pages()
	 *
	 *	Filter wpsight_get_option for pages
	 * 	saved in options.
	 *
	 *	@uses	pll_current_language()
	 *	@uses	pll_default_language()
	 *	@uses	pll_get_post()
	 *
	 *	@since 1.0.0
	 */
	public function options_pages( $option, $name ) {
		if( ! function_exists( 'pll_current_language' ) )
			return;

		$pages = array(
			'listings_page',
			'favorites_page',
			'dashboard_page',
			'dashboard_submit',
			'listings_map_page'
		);

		if( ! in_array( $name, $pages ) )
			return $option;

		$current = pll_current_language();

		$lang = $current ? $current : pll_default_language();

		return pll_get_post( $option, $lang );

	}

	/**
	 *	dashboard_listings()
	 *
	 *	Make sure dashboard add-on
	 *	shows all languages.
	 *
	 *	@uses	get_terms()
	 *	@uses	pll_languages_list()
	 *
	 *	@since 1.0.0
	 */
	public function dashboard_listings( $args ) {

		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'language',
				'terms'    => get_terms( 'language', array( 'fields' => 'ids' ) ),
				'operator' => 'NOT IN'
			),
			array(
				'taxonomy' => 'language',
				'field'    => 'slug',
				'terms'    => pll_languages_list()
			)
		);

		return $args;

	}

	/**
	 *	dashboard_columns()
	 *
	 *	Make sure dashboard add-on
	 *	shows all languages.
	 *
	 *	@since 1.0.0
	 */
	public function dashboard_columns( $columns ) {

		$columns['lang'] = __( 'Language', 'wpcasa-polylang' );

		return $columns;

	}

	/**
	 *	dashboard_column_lang()
	 *
	 *	Display language column
	 *	in dashboard.
	 *
	 *	@uses	wp_list_pluck()
	 *	@uses	pll_the_languages()
	 *	@uses	pll_get_post_language()
	 *
	 *	@since 1.0.0
	 */
	public function dashboard_column_lang( $post ) {

		$languages = wp_list_pluck( pll_the_languages( array( 'raw' => 1 ) ), 'flag', 'slug' );

		$lang = pll_get_post_language( $post->ID );

		echo '<div style="text-align:center"><img src="' . $languages[ $lang ] . '" /></div>';

	}

}

/**
 *	Check if Polylang plugin is active
 *	and activate our add-on if yes.
 *
 *	@since 1.0.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( 'polylang-pro/polylang.php' ) ) {

	// Initialize plugin on wpsight_init
	add_action( 'wpsight_init', array( 'WPSight_Polylang', 'init' ) );

}
