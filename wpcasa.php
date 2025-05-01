<?php
/**
 * WPCasa
 *
 * @package           WPCasa
 * @author            WPSight
 * @copyright         2025 Kybernetik Services GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WPCasa
 * Plugin URI:        https://wordpress.org/plugins/wpcasa/
 * Description:       Flexible WordPress plugin to create professional real estate websites and manage property listings with ease.
 * Version:           1.4.1
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Author:            WPSight
 * Author URI:        https://wpcasa.com
 * Text Domain:       wpcasa
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

/**
 * WPSight_Framework class
 */
#[\AllowDynamicProperties]
class WPSight_Framework {

    // Variables
    public $admin;
    public $post_types;
    public $agents;
    public $general;
    public $helpers;
    public $search;
    public $meta_boxes;
    public $admin_map_ui;
    public $listings_map;
	public $ninja_forms;

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

        if( ! function_exists( 'get_plugin_data' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
	    $plugin_data = get_plugin_data( __FILE__, false, false );

        define( 'WPSIGHT_VERSION', $plugin_data[ 'Version' ] );
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

        require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        // TODO: delete till wpcasa 2.0
        if ( is_plugin_active( 'wpcasa-admin-map-ui/wpcasa-admin-map-ui.php' )  ) {
            deactivate_plugins( '/wpcasa-admin-map-ui/wpcasa-admin-map-ui.php' );
        }

        // TODO: delete check till wpcasa 2.0
        if ( ! is_plugin_active( 'wpcasa-admin-map-ui/wpcasa-admin-map-ui.php' )  ) {
            if ( ! class_exists( 'WPSight_Admin_Map_UI' ) ) {
                include_once( WPSIGHT_PLUGIN_DIR . '/includes/admin-map-ui/class-wpsight-admin-map-ui.php' );
            }
        }

        // TODO: delete till wpcasa 2.0
        if ( is_plugin_active( 'wpcasa-listings-map/wpcasa-listings-map.php' )  ) {
            deactivate_plugins( '/wpcasa-listings-map/wpcasa-listings-map.php' );
        }

        // TODO: delete check till wpcasa 2.0
        if ( ! is_plugin_active( 'wpcasa-listings-map/wpcasa-listings-map.php' )  ) {
            if ( ! class_exists( 'WPSight_Listings_Map' ) ) {
                include( WPSIGHT_PLUGIN_DIR . '/includes/listings-map/class-wpsight-listings-map.php' );
            }
        }

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

        register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), [ $this->post_types, 'register_post_type_listing' ], 10 );
        register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), function() { include_once('includes/class-wpsight-install.php'); }, 10 );
        register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'flush_rewrite_rules', 15 );
        register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), [ $this, 'activation' ] );

        // Actions

        add_action( 'switch_theme', [ $this->post_types, 'register_post_type_listing' ], 10 );
        add_action( 'switch_theme', 'flush_rewrite_rules', 15 );
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

        // Init action for add-ons to hook in
        do_action_ref_array( 'wpsight_init', [ &$this ] );

    }

	/**
	 * setChildClass()
	 *
	 * Set up the child plugin class
	 *
	 * @param $name
	 * @param $addon_object
	 *
	 * @since 1.2.13
	 */

    public function setChildClass( $name, $addon_object ) {
        $this->{$name} = $addon_object;
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


        wp_enqueue_script( 'jquery-tiptip', WPSIGHT_PLUGIN_URL . '/assets/js/jquery.tipTip' . $suffix . '.js', [ 'jquery' ], '1.3', true );
        wp_enqueue_script( 'jquery-cookie', WPSIGHT_PLUGIN_URL . '/assets/js/jquery.cookie.js', [ 'jquery' ], '1.4.1', true );
        wp_enqueue_script( 'wpsight-listings-search', WPSIGHT_PLUGIN_URL . '/assets/js/wpsight-listings-search.js', [ 'jquery' ], WPSIGHT_VERSION, true );

        // Localize scripts
        $data = [
            'cookie_path'			=> COOKIEPATH,
            'cookie_search_query'	=> WPSIGHT_COOKIE_SEARCH_QUERY
        ];

        wp_localize_script( 'wpsight-listings-search', 'wpsight_localize', $data );

        // Enqueue Google Maps (optionally with API key)

        if( true == apply_filters( 'wpsight_google_maps', true ) ) {

            $api_key = wpsight_get_option( 'google_maps_api_key' );
            $api_url = $api_key ? add_query_arg( [ 'key' => $api_key ], '//maps.googleapis.com/maps/api/js' ) : '//maps.googleapis.com/maps/api/js';

            wp_enqueue_script( 'wpsight-map-googleapi', apply_filters( 'wpsight_google_maps_endpoint', esc_url( $api_url ), $api_key ), null, WPSIGHT_VERSION, array( 'in_footer' => false ) );

        }

        if( true == apply_filters('wpsight_css', true) && wpsight_get_option('listings_css' ) ) {

            //if ( is_singular( 'listing' ) ) {
                wp_enqueue_style( 'wpsight', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight' . $suffix . '.css', '', WPSIGHT_VERSION);

                if ( is_rtl() )
                    wp_enqueue_style( 'wpsight-rtl', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-rtl' . $suffix . '.css', '', WPSIGHT_VERSION );
                //}

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

        $page_data = [
            'post_title'     => _x( 'Listings', 'listings page title', 'wpcasa' ),
            'post_content'   => '[wpsight_listings]',
            'post_type'      => 'page',
            'post_status'	 => 'publish',
            'comment_status' => 'closed',
            'ping_status'	 => 'closed'
        ];

        $page_id = ! wpsight_get_option( 'listings_page' ) ? wp_insert_post( $page_data ) : false;

        // Add some default options

        $options = [
            'listings_page'			=> $page_id,
            'listing_id'			=> __( 'ID-', 'wpcasa' ),
            'measurement_unit'		=> 'm2',
            'currency'				=> 'usd',
            'currency_symbol'		=> 'before',
            'currency_separator'	=> 'comma',
            'date_format'			=> get_option( 'date_format' ),
            'listings_css'			=> '1'
        ];

        // Add default standard features
        foreach ( wpsight_details() as $option => $value )
            $options[ $option ] = [ 'label' => $value['label'], 'unit' => $value['unit'] ];

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
 *  @return  object|bool $wpsight
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
function wpsight_admin_notice_wpcasa() {

    if( wp_get_theme()->template != 'wpcasa' )
        return;

    $error_notice = '<div class="error"><p>' . __( 'Please make sure to <strong>deactivate the WPCasa theme</strong> in order to use the WPCasa plugin version. For more information about how to switch please <a href="http://docs.wpsight.com/article/switching-from-theme-version/" target="_blank">read our docs</a>.', 'wpcasa' ) . '</p></div>';
    echo wp_kses( $error_notice, array( 'div' => array( 'class' => array() ), 'p' => array(), 'strong' => array(), 'a' => array( 'href' => array() ) ) );
}
add_action( 'admin_notices', 'wpsight_admin_notice_wpcasa' );

/**
 *	wpsight_admin_plugins_delete_notice()
 *
 *	Make sure users awera
 *	that WPCasa Listing Map and
 *	WPCasa admin map ui plugins
 *	can be deleted
 *
 *	@since 1.2.0
 */
function wpsight_admin_plugins_delete_notice() {

    $admin_map_ui	= WP_PLUGIN_DIR . '/wpcasa-admin-map-ui/wpcasa-admin-map-ui.php';
    $listing_map	= WP_PLUGIN_DIR . '/wpcasa-listings-map/wpcasa-listings-map.php';

    $plugin_name    = '';
    $count          = 1;

    if( file_exists( $admin_map_ui ) ) {

        $plugin_name .= '<strong>WPCasa Admin Map UI</strong>';

    }

    if( file_exists( $listing_map ) ) {

        if( !empty( $plugin_name ) ) {

            $plugin_name .= ' ' . __( 'and' , 'wpcasa' ) . ' ';

            $count = 2;

        }

        $plugin_name .= '<strong>WPCasa Listings Map</strong>';

    }

    if ( ! empty( $plugin_name ) )  {
        $message = sprintf( 
                            // Translators: %s is plugin name
                            _n( 
                                '%s has been discontinued. The Functionality of the plugin has been integrated in WPCasa as of 1.2.0.</br>Feel free to remove the plugin.',
                                '%s has been discontinued. The Functionality of both plugins has been integrated in WPCasa as of 1.2.0.</br>Feel free to remove both of those plugins.',
                                $count, 'wpcasa' ),
                    $plugin_name );

        echo wp_kses( '<div class="notice notice-warning my-dismiss-notice is-dismissible"><p>' . $message . '</p></div>', array( 'div' => array( 'class' => array() ), 'p' => array() ) );

    }

}
add_action( 'admin_notices', 'wpsight_admin_plugins_delete_notice' );

/**
 *
 *	Redirect after single wpcasa activatation
 *	Prevent multiple activation redirect
 *
 *	@since 1.2.0
 */
function wpcasa_plugin_activate() {
    add_option('wpcasa_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'wpcasa_plugin_activate');

function wpcasa_activation_redirect() {
    if (get_option('wpcasa_do_activation_redirect', false)) {
        delete_option('wpcasa_do_activation_redirect');
        if ( !isset($_GET['activate-multi']) ) {
            wp_safe_redirect( admin_url( 'index.php?page=wpsight-settings' ));
            exit();
        }
    }
}
add_action('admin_init', 'wpcasa_activation_redirect');

/**
 *  Adds plugin upgrade notification
 *
 *	@since 1.2.11
 */
function wpcasa_plugin_update_message( $data, $response ) {

    if( isset( $data[ 'upgrade_notice'] ) ) {

        $upgrade_notice = str_replace('<p>', '<span style="margin-top: 5px">', wpautop( $data['upgrade_notice'] ) );
        $upgrade_notice = str_replace('</p>', '</span><br>', $upgrade_notice );

        printf(
            '</p><p style="background-color: #d63638; padding: 10px; color: #f9f9f9; margin-top: 10px"><span style="margin-left: -25px"><strong>%s:</strong></span><br>%s',
            esc_html__( 'Important Upgrade Notice', 'wpcasa'),
            $upgrade_notice
        );
    }

}
add_action( 'in_plugin_update_message-wpcasa/wpcasa.php', 'wpcasa_plugin_update_message', 10, 2 );

function wpcasa_settings_value_change() {
    $wpcasa_settings    = get_option('wpcasa');
    $thousand_separator = $wpcasa_settings['currency_separator'] ?? '';

    if ( ( 'comma' === $thousand_separator ) || ( 'dot' === $thousand_separator ) ) {
        switch ( $thousand_separator ) {
            case 'comma':
                $wpcasa_settings['currency_separator'] = ',';
            break;
            case 'dot':
                $wpcasa_settings['currency_separator'] = '.';
            break;
        }
        update_option( 'wpcasa', $wpcasa_settings );
    }   
}
add_action('admin_init', 'wpcasa_settings_value_change');


