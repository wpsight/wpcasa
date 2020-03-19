<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

/**
 *	WPSight_Admin class
 */
class WPSight_Admin {

    /**
     *	Constructor
     */
    public function __construct() {

        include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-cpt.php';
        include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-agents.php';

        include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-page-settings.php';
        include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-page-licenses.php';

        include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-color-scheme.php';

        include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-helpers.php';

        $this->cpt				= new WPSight_Admin_CPT();
        $this->settings_page	= new WPSight_Admin_Settings();
        $this->license_page		= new WPSight_Admin_Licenses();
        $this->agents			= new WPSight_Admin_Agents();

        $this->color_scheme		= new WPSight_Admin_Color_Scheme();

        $this->helpers			= new WPSight_Admin_Helpers();

        add_action( 'admin_menu',						array( $this, 'admin_menu' ),					12 );
        add_action( 'admin_enqueue_scripts',			array( $this, 'admin_enqueue_scripts' ) );

        add_action( 'admin_notices',					array( $this, 'notice_setup' ) );

        add_filter( 'views_upload',						array( $this, 'media_custom_views' ) );
        add_filter( 'views_edit-listing',				array( $this, 'listings_custom_views' ) );
        add_filter( 'views_edit-property',				array( $this, 'listings_custom_views' ) );
        add_filter( 'manage_users_columns',				array( $this, 'manage_users_columns' ) );
        add_action( 'manage_users_custom_column',		array( $this, 'manage_users_custom_column' ),	10, 3 );

        add_filter( 'install_plugins_tabs',				array( $this, 'add_addon_tab' ) );
        add_action( 'install_plugins_wpcasa_addons',	array( $this, 'addons_page' ), 10, 1 );
//        add_action( 'install_plugins_wpcasa_recommends',	array( $this, 'recommends_page' ), 10, 1 );

//		add_filter( 'install_themes_tabs',				array( $this, 'add_theme_tab' ) );
//		add_action( 'install_themes_wpcasa_themes',		array( $this, 'themes_page' ), 10, 1 );

    }

    /**
     *	admin_enqueue_scripts()
     *
     *	Enqueue scripts and styles used
     *	on WordPress admin pages.
     *
     *	@access	public
     *	@uses	get_current_screen()
     *	@uses	wp_enqueue_style()
     *	@uses	wp_register_script()
     *	@uses	wp_enqueue_script()
     *
     *	@since 1.0.0
     */
    public function admin_enqueue_scripts() {

        global $wp_scripts;

        // Script debugging?
        $suffix = SCRIPT_DEBUG ? '' : '.min';

        $screen		= get_current_screen();
        $post_type	= wpsight_post_type();

//        TODO: Delete it till wpcasa 1.7
        if ( in_array( $screen->id, array( 'plugins' ) ) )
            wp_enqueue_script( 'jquery-plugins-admin', WPSIGHT_PLUGIN_URL . '/assets/js/wpsight-plugins-admin.js', array( 'jquery' ), WPSIGHT_VERSION, true );


        wp_enqueue_style( 'wpsight-font', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-admin-font' . $suffix . '.css', array() );

        if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type, 'toplevel_page_wpsight-settings', 'wpcasa_page_wpsight-addons', 'wpcasa_page_wpsight-themes', 'wpcasa_page_wpsight-licenses', 'wpcasa_page_wpsight-recommendations' ) ) || $screen->id == 'plugin-install' && isset( $_GET['tab'] ) && $_GET['tab'] == 'wpcasa_addons' ) {

            wp_register_script( 'jquery-tiptip',				WPSIGHT_PLUGIN_URL . '/assets/js/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WPSIGHT_VERSION, true );

            wp_enqueue_style( 'wpsight-admin-ui-framework',		WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-admin-ui-framework' . $suffix . '.css', array( 'cmb2-styles' ) );
            wp_enqueue_style( 'wpsight-admin',					WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-admin' . $suffix . '.css', array( 'wpsight-admin-ui-framework', 'cmb2-styles' ) );

            wp_enqueue_script( 'wpsight_admin_js',				WPSIGHT_PLUGIN_URL . '/assets/js/wpsight-admin' . $suffix . '.js', array( 'jquery', 'jquery-tiptip', 'jquery-ui-datepicker' ), WPSIGHT_VERSION, true );
            wp_localize_script( 'wpsight_admin_js', 'settings_name', $this->settings_page->settings_name);

            wp_enqueue_style( 'wpsight-admin-swiper-styles',		WPSIGHT_PLUGIN_URL . '/assets/css/swiper.min.css', array( 'cmb2-styles' ) );
            wp_enqueue_script( 'wpsight-admin-swiper-script',				WPSIGHT_PLUGIN_URL . '/assets/js/swiper.min.js', array( 'jquery' ), WPSIGHT_VERSION, true );

        }

        if ( in_array( $screen->id, array( 'profile', 'user-edit' ) ) )
            wp_enqueue_media();

    }

    /**
     *	admin_menu()
     *
     *	Add WPSight settings main and
     *	sub pages to the admin menu.
     *
     *	@access	public
     *	@uses	add_menu_page()
     *	@uses	add_submenu_page()
     *	@uses	apply_filters()
     *
     *	@since 1.0.0
     */
    public function admin_menu() {

        add_menu_page( WPSIGHT_NAME, WPSIGHT_NAME, 'manage_options', 'wpsight-settings', array( $this->settings_page, 'output' ), 'dashicons-marker' );

        add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Settings', 'wpcasa' ),  __( 'Settings', 'wpcasa' ) , 'manage_options', 'wpsight-settings', array( $this->settings_page, 'output' ) );

        if ( apply_filters( 'wpsight_show_addons_page', true ) )
            add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Add-Ons', 'wpcasa' ),  __( 'Add-Ons', 'wpcasa' ) , 'manage_options', 'wpsight-addons', array( $this, 'addons_page' ) );

//        if ( apply_filters( 'wpsight_show_recommend_page', true ) )
//            add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Recommendations', 'wpcasa' ),  __( 'Recommendations', 'wpcasa' ) , 'manage_options', 'wpsight-recommendations', array( $this, 'recommends_page' ) );

        if ( apply_filters( 'wpsight_show_themes_page', true ) )
            add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Themes', 'wpcasa' ),  __( 'Themes', 'wpcasa' ) , 'manage_options', 'wpsight-themes', array( $this, 'themes_page' ) );

        if ( apply_filters( 'wpsight_show_licenses_page', true ) )
            add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Licenses', 'wpcasa' ),  __( 'Licenses', 'wpcasa' ) , 'manage_options', 'wpsight-licenses', array( $this->license_page, 'output' ) );

        if ( apply_filters( 'wpsight_show_about_page', true ) )
            add_submenu_page(  null, WPSIGHT_NAME . ' ' . __( 'About', 'wpcasa' ),  __( 'About', 'wpcasa' ) , 'manage_options', 'wpsight-about', array( $this, 'about_page' ) );
    }

    /**
     * Adds a new tab to the install plugins page.
     *
     * @return void
     */
    public function add_addon_tab( $tabs ) {
        $tabs['wpcasa_addons'] = __( 'WPCasa ', 'wpcasa' ) . '<span class="wpcasa-addons">' . __( 'Addons', 'wpcasa' ) . '</span>' ;
        return $tabs;
    }

    /**
     *	addons_page()
     *
     *	Add WPSight addons page to sub menu.
     *
     *	@access	public
     *
     *	@since 1.0.0
     */
    public function addons_page() {
        $addons = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-page-addons.php';
        $addons->output();
    }

    /**
     *	recommends_page()
     *
     *	Add WPSight recommends page to sub menu.
     *
     *	@access	public
     *
     *	@since 1.0.0
     */
//    public function recommends_page() {
//        $addons = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-page-recommends.php';
//        $addons->output();
//    }

    /**
     * Adds a new tab to the install plugins page.
     *
     * @return void
     */
    public function add_theme_tab( $tabs ) {
        $tabs['wpcasa_themes'] = __( 'WPCasa ', 'wpcasa' ) . '<span class="wpcasa-themes">' . __( 'Themes', 'wpcasa' ) . '</span>' ;
        return $tabs;
    }


    /**
     *	themes_page()
     *
     *	Add WPSight themes page to sub menu.
     *
     *	@access	public
     *
     *	@since 1.0.0
     */
    public function themes_page() {
        $themes = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-page-themes.php';
        $themes->output();
    }

    /**
     *	about_page()
     *
     *	Add WPSight about page.
     *
     *	@access	public
     *
     *	@since 1.0.0
     */
    public function about_page() {
        $about = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-page-about.php';
        $about->output();
    }

    /**
     *	options()
     *
     *	Merge option tabs and
     *	return wpsight_options_listings()
     *
     *	@uses	wpsight_options_listings()
     *	@return	array	$options
     *
     *	@since 1.0.0
     */
    public static function options() {
        $options = array(
            'listings' => array(
                '<span class="dashicons dashicons-admin-multisite"></span>' . __( 'Listings', 'wpcasa' ),
                (array) self::options_listings()
            ),
//            'search' => array(
//                '<span class="dashicons dashicons-search"></span>' . __( 'Search', 'wpcasa' ),
//                (array) self::options_search()
//            ),
            'maps' => array(
                '<span class="dashicons dashicons-location-alt"></span>' . __( 'Maps', 'wpcasa' ),
                (array) self::options_maps()
            )
        );

        $options = apply_filters( 'wpsight_options', $options );

        return $options;
    }

    /**
     *	licenses()
     *
     *	Create license array
     *
     *	@return	array $licenses
     *
     *	@since 1.0.0
     */
    public static function licenses() {

        // initialize empty array
        $licenses = array();

        // add default license
        $licenses['support_package'] = array(
            'name'		=> __( 'Support Package', 'wpcasa' ),
            'desc'		=> __( 'To receive support for a free product please enter your support package license key.', 'wpcasa' ),
            'id'		=> 'support_package',
            'section'	=> 'services',
            'priority'	=> 1000
        );

        // filter licenses
        $licenses = apply_filters( 'wpsight_licenses', $licenses );

        // sort by priority
        $licenses = wpsight_sort_array_by_priority( $licenses );

        // return
        return $licenses;

    }

    /**
     *	options_listings()
     *
     *	Create theme options array
     *	Listings options
     *
     *	@uses	wpsight_get_option()
     *	@uses	wpsight_measurements()
     *	@uses	wpsight_currencies()
     *	@uses	wpsight_details()
     *	@uses	wpsight_rental_periods()
     *	@uses	wpsight_date_formats()
     *	@return	array	$options_listings
     *
     *	@since 1.0.0
     */
    public static function options_listings() {

        $options_listings = array();

        $options_listings['pageheading_listings'] = array(
            'name'		=> __( 'Listings', 'wpcasa' ),
            'desc'		=> __( 'Here you can define some basic settings', 'wpcasa' ),
            'icon'		=> 'dashicons dashicons-admin-multisite',
            'link'		=> 'https://docs.wpcasa.com',
            'id'		=> 'pageheading_listings',
            'position'	=> 10,
            'type'		=> 'pageheading'
        );

        $options_listings['heading_listings'] = array(
            'name'		=> __( 'General Listing Settings', 'wpcasa' ),
            'desc'		=> __( 'Here you can define some basic settings', 'wpcasa' ),
            'id'		=> 'heading_listings',
            'position'	=> 20,
            'type'		=> 'heading'
        );

        $options_listings['listings_page'] = array(
            'name'		=> __( 'Listings Page', 'wpcasa' ),
            'desc'		=> __( 'Please select the main search results page with the <code>[wpsight_listings]</code> shortcode.', 'wpcasa' ),
            'id'		=> 'listings_page',
            'position'	=> 30,
            'type'		=> 'pages'
        );

        $options_listings['date_format'] = array(
            'name'		=> __( 'Date Format', 'wpcasa' ),
            'desc'		=> __( 'Please select the date format for the listings table in the admin.', 'wpcasa' ),
            'id'		=> 'date_format',
            'position'	=> 40,
            'type'		=> 'select',
            'options'	=> array_filter( wpsight_date_formats( true ) ),
            'default'	=> get_option( 'date_format' )
        );

        $options_listings['listings_css'] = array(
            'name'		=> __( 'Output CSS', 'wpcasa' ),
            'desc'		=> __( 'Please uncheck the box to disable the plugin from outputting CSS.', 'wpcasa' ),
            'id'		=> 'listings_css',
            'position'	=> 50,
            'type'		=> 'checkbox',
            'default'	=> '1'
        );

        // Check of old 'property_id' options was active
        $listing_id_default = wpsight_get_option( 'property_id' ) ? wpsight_get_option( 'property_id' ) : __( 'ID-', 'wpcasa' );

        $options_listings['listing_id'] = array(
            'name'		=> __( 'Listing ID Prefix', 'wpcasa' ),
            'desc'		=> __( 'The listing ID will be this prefix plus post ID. You can optionally set individual IDs on the listing edit screen.', 'wpcasa' ),
            'id'		=> 'listing_id',
            'position'	=> 60,
            'type'		=> 'text',
            'default'	=> $listing_id_default
        );

        $options_listings['measurement_unit'] = array(
            'name'		=> __( 'Measurement Unit', 'wpcasa' ),
            'desc'		=> __( 'Please select the general measurement unit. The unit for the listing details can be defined separately.', 'wpcasa' ),
            'id'		=> 'measurement_unit',
            'position'	=> 70,
            'type'		=> 'radio',
            'class'		=> 'mini',
            'options'	=> array_filter( wpsight_measurements() ),
            'default'	=> 'm2'
        );

        $options_listings['heading_currency'] = array(
            'name'		=> __( 'Currency', 'wpcasa' ),
            'id'		=> 'heading_currency',
            'position'	=> 80,
            'type'		=> 'heading'
        );

        $options_listings['currency'] = array(
            'name'		=> __( 'Currency', 'wpcasa' ),
            'desc'		=> __( 'Please select the currency for the listing prices. If your currency is not listed, please select <code>Other</code>.', 'wpcasa' ),
            'id'		=> 'currency',
            'position'	=> 90,
            'type'		=> 'select',
            'class'		=> 'mini',
            'options'	=> array_merge( array_filter( wpsight_currencies() ), array( 'other' => __( 'Other', 'wpcasa'  ) ) ),
            'default'	=> 'usd'
        );

        $options_listings['currency_other'] = array(
            'name'		=> __( 'Other Currency', 'wpcasa' ) . ' (' . __( 'Abbreviation', 'wpcasa' ) . ')',
            'desc'		=> __( 'Please insert the abbreviation of your currency (e.g. <code>EUR</code>).', 'wpcasa' ),
            'id'		=> 'currency_other',
            'position'	=> 100,
            'type'		=> 'text',
            'class'		=> 'hidden'
        );

        $options_listings['currency_other_ent'] = array(
            'name'		=> __( 'Other Currency', 'wpcasa' ) . ' (' . __( 'Symbol', 'wpcasa' ) . ')',
            'desc'		=> __( 'Please insert the currency symbol or HTML entity (e.g. <code>&amp;euro;</code>).', 'wpcasa' ),
            'id'		=> 'currency_other_ent',
            'position'	=> 110,
            'type'		=> 'text',
            'class'		=> 'hidden'
        );

        $options_listings['currency_symbol'] = array(
            'name'		=> __( 'Currency Symbol', 'wpcasa' ),
            'desc'		=> __( 'Please select the position of the currency symbol.', 'wpcasa' ),
            'id'		=> 'currency_symbol',
            'position'	=> 120,
            'type'		=> 'radio',
            'options'	=> array(
                'before'		=> __( 'Before the value', 'wpcasa' ),
                'after'			=> __( 'After the value', 'wpcasa' ),
                'before_space'	=> __( 'Before the value (with Space)', 'wpcasa' ),
                'after_space'	=> __( 'After the value (with Space)', 'wpcasa' )
            ),
            'default'	=> 'before'
        );

        $options_listings['currency_separator'] = array(
            'name'		=> __( 'Thousands Separator', 'wpcasa' ),
            'desc'		=> __( 'Please select the thousands separator for your listing prices.', 'wpcasa' ),
            'id'		=> 'currency_separator',
            'position'	=> 130,
            'type'		=> 'radio',
            'options'	=> array(
                'comma'		=> __( 'Comma (e.g. 1,000,000)', 'wpcasa' ),
                'dot'		=> __( 'Period (e.g. 1.000.000)', 'wpcasa' ) ),
            'default'	=> 'comma'
        );

        $options_listings['heading_details'] = array(
            'name'		=> __( 'Listing Details', 'wpcasa' ),
            'id'		=> 'heading_details',
            'position'	=> 140,
            'type'		=> 'heading'
        );

        /** Toggle standard details */

//		$options_listings['listing_details'] = array(
//			'name'		=> __( 'Listing Details', 'wpcasa' ),
//			'desc'	=> __( 'Please check the box to edit the listing details.', 'wpcasa' ),
//			'id'		=> 'listing_features',
//			'type'		=> 'checkbox',
//			'default'	=> '0'
//		);

        /** Loop through standard details */

        $i=1;

        $position=150;

        foreach ( wpsight_details() as $detail_id => $value ) {

            $options_listings[ $detail_id ] = array(
                'name'		=> __( 'Listing Detail', 'wpcasa' ) . ' #' . $i++,
                'desc'		=> $value['description'],
                'id'		=> $detail_id,
                'position'	=> $position++,
                'type'		=> 'measurement',
                'class'		=> '',
                'default'	=> array(
                    'label'		=> $value['label'],
                    'unit'		=> $value['unit']
                )
            );

//			$i++;
//			$position++;

        }

        $options_listings['heading_rental_periods'] = array(
            'name'		=> __( 'Rental Periods', 'wpcasa' ),
            'id'		=> 'heading_rental_periods',
            'position'	=> 300,
            'type'		=> 'heading'
        );

        /** Toggle rental periods */

//		$options_listings['rental_periods'] = array(
//			'name'		=> __( 'Rental Periods', 'wpcasa' ),
//			'desc'	=> __( 'Please check the box to edit the rental periods.', 'wpcasa' ),
//			'id'		=> 'rental_periods',
//			'type'		=> 'checkbox',
//			'default'	=> '0'
//		);

        /** Loop through rental periods */

        $i=1;
        $position=310;

        foreach ( wpsight_rental_periods() as $period => $value ) {

            $options_listings[ $period ] = array(
                'name'		=> __( 'Rental Period', 'wpcasa' ) . ' #' . $i++,
                'id'		=> $period,
                'position'	=> $position++,
                'type'		=> 'text',
                'class'		=> '',
                'default'	=> $value
            );

//			$i++;
//			$position++;

        }

        // filter options
        $options_listings = apply_filters( 'wpsight_options_listings', $options_listings );

        // sort options by position
        $options_listings = wpsight_sort_array_by_position( $options_listings );

        return $options_listings;

    }

    /**
     *	options_search()
     *
     *	Create theme options array
     *	Search options
     *
     *	@uses	wpsight_get_search_fields()
     *	@return	array	$options_search
     *
     *	@since 1.1.0
     */
    public static function options_search() {

        $options_search = array();

        /** Loop through search fields */
        $fields = wpsight_get_search_fields();

        $options = array();

        foreach( $fields as $field => $v ) {

            $label = isset($v['label']) ? $v['label'] : '';

            if( $v['type'] == 'taxonomy_select' && $v['data']['show_option_none'] )
                $label = $v['data']['show_option_none'];

            $options[$field] = $label;
        }

        $options_search['pageheading_search'] = array(
            'name'		=> __( 'Search', 'wpcasa' ),
            'desc'		=> __( 'Here you can define some basic settings', 'wpcasa' ),
            'icon'		=> 'dashicons dashicons-search',
            'link'		=> '',
            'id'		=> 'pageheading_search',
            'position'	=> 10,
            'type'		=> 'pageheading'
        );

        $options_search['search_elements'] = array(
            'name'		=> __( 'Search Form Elements', 'wpcasa' ),
            'desc'		=> __( 'Choose what to display in the search form', 'wpcasa' ),
            'id'		=> 'search_elements',
            'position'	=> 20,
            'type'		=> 'multicheck',
            'options'	=> $options
        );

        // filter options
        $options_search = apply_filters( 'wpsight_options_search', $options_search );

        // sort options by position
        $options_search = wpsight_sort_array_by_position( $options_search );

        return $options_search;

    }

    /**
     *	options_maps()
     *
     *	Create theme options array
     *	Maps options
     *
     *	@return	array	$options_maps
     *
     *	@since 1.1.0
     */
    public static function options_maps() {

        $options_maps = array();

        $options_maps['pageheading_maps'] = array(
            'name'	=> __( 'Maps', 'wpcasa' ),
            'desc'	=> __( 'Here you can define some basic settings', 'wpcasa' ),
            'icon'	=> 'dashicons dashicons-location-alt',
            'link'	=> 'https://docs.wpcasa.com/article/wpcasa-listings-map/',
            'id'	=> 'pageheading_maps',
            'type'	=> 'pageheading'
        );

        $options_maps['heading_listings_map_api'] = array(
            'name'	=> __( 'Map API', 'wpcasa' ),
            'desc'	=> __( '', 'wpcasa' ),
            'id'	=> 'heading_listings_map_api',
            'type'	=> 'heading',
            'position'	=> '490'
        );

        $options_maps['google_maps_api_key'] = array(
            'name'		=> __( 'Google Maps API', 'wpcasa' ),
            'desc'		=> sprintf( __( 'If necessary, please enter your Google Maps API key (<a href="%s" target="_blank">register here</a>).', 'wpcasa' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ),
            'id'		=> 'google_maps_api_key',
            'type'		=> 'text',
            'position'	=> '500'
        );

        // filter options
        $options_maps = apply_filters( 'wpsight_options_maps', $options_maps );

        // sort options by position
        $options_maps = wpsight_sort_array_by_position( $options_maps );

        return $options_maps;

    }

    /**
     *	options_debug()
     *
     *	Create theme options array
     *	Listings options
     *
     *	@uses	wpsight_get_option()
     *	@uses	wpsight_measurements()
     *	@uses	wpsight_currencies()
     *	@uses	wpsight_details()
     *	@uses	wpsight_rental_periods()
     *	@uses	wpsight_date_formats()
     *	@return	array	$options_listings
     *
     *	@since 1.0.0
     */
    public static function options_debug() {

        $options_debug = array();

        if( version_compare( '1.1.0', WPSIGHT_VERSION, '>=' ) ) {

            $options_debug['example_slider'] = array(
                'name'		=> __( 'Example Slider', 'wpcasa' ),
                'desc'		=> __( 'Example Slider Option', 'wpcasa' ),
                'id'		=> 'example_slider',
                'type'		=> 'range',
                'min'		=> 0,
                'max'		=> 1000,
                'step'		=> 50,
                'default'	=> ''
            );

            $options_debug['example_number'] = array(
                'name'		=> __( 'Example Number', 'wpcasa' ),
                'desc'		=> __( 'Example Number Option', 'wpcasa' ),
                'id'		=> 'example_number',
                'type'		=> 'number',
                'default'	=> ''
            );

        }


        return apply_filters( 'wpsight_options_debug', $options_debug );

    }

    /**
     *	media_custom_views()
     *
     *	Media library views
     *
     *	@param	array	$views	Incoming views
     *	@uses	$wpdb->prepare()
     *	@uses	$wpdb->get_col()
     *	@uses	wp_count_attachments()
     *	@return	array	$views	Updated views
     *
     *	@since 1.0.0
     */
    public static function media_custom_views( $views ) {

        global $wpdb, $wp_query, $pagenow;

        if ( 'upload.php' != $pagenow )
            return;

        if ( ! isset( $wp_query->query_vars['s'] ) )
            return $views;

        // Search custom fields for listing ID

        $post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
	    SELECT DISTINCT post_id FROM {$wpdb->postmeta}
	    WHERE meta_value LIKE '%s'
	    ", $wp_query->query_vars['s'] ) );

        if ( ! empty( $post_ids_meta ) && isset( $_GET['s'] ) ) {
            unset( $views );
            $_num_posts = (array) wp_count_attachments();
            $_total_posts = array_sum( $_num_posts ) - $_num_posts['trash'];
            $views['all'] = '<a href="' . $pagenow . '">' . __( 'All', 'wpcasa' ) . ' <span class="count">(' . $_total_posts . ')</span></a>';
            $views['found'] = '<a href="' . $pagenow . '?s=' . $_GET['s'] . '" class="current">' . $_GET['s'] . ' <span class="count">(' . $wp_query->found_posts . ')</span></a>';
        }

        return $views;
    }

    /**
     *	listings_custom_views()
     *
     *	Listing views
     *
     *	@param	array	$views	Incoming views
     *	@uses	$wpdb->prepare()
     *	@uses	$wpdb->get_col()
     *	@return	array	$views	Updated views
     *
     *	@since 1.0.0
     */
    public static function listings_custom_views( $views ) {
        global $wpdb, $wp_query, $pagenow;

        if ( 'edit.php' != $pagenow )
            return;

        // Replace 'Published' with 'Active'

        if( isset( $views['publish'] ) )
            $views['publish'] = str_replace( __( 'Published', 'wpcasa' ), __( 'Active', 'wpcasa' ), $views['publish'] );

        if ( empty( $wp_query->query_vars['s'] ) )
            return $views;

        // Search custom fields for listing ID

        $post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
	    SELECT DISTINCT post_id FROM {$wpdb->postmeta}
	    WHERE meta_value LIKE '%s'
	    ", $wp_query->query_vars['s'] ) );

        if ( empty( $post_ids_meta ) )
            return $views;

    }

    /**
     *	 manage_users_columns()
     *
     *	 Add column for number of listings of a user.
     *
     *	 @param		array	$columns	Incoming columns
     *	 @return	array	$columns	Updated columns
     *
     *	 @since 1.0.0
     */
    public static function manage_users_columns( $columns ) {
        $columns['listings_count'] = __( 'Listings', 'wpcasa' );
        return $columns;
    }

    /**
     *	manage_users_custom_column()
     *
     *	Show number of listings the user has
     *
     *	@param	string	$value
     *	@param	string	$column_name
     *	@param	int		$user_id
     *	@uses	count_user_posts()
     *	@uses	wpsight_post_type()
     *	@return	string	new value
     *
     *	@since 1.0.0
     */
    public static function manage_users_custom_column( $value, $column_name, $user_id ) {

        if ( 'listings_count' != $column_name  )
            return $value;

        $listings_count = count_user_posts( $user_id, wpsight_post_type() );
        $user_listings_links = '<a href="edit.php?author=' . $user_id . '&post_type=' . wpsight_post_type() . '">' . $listings_count . '</a>';

        return $user_listings_links;

    }



    /**
     *	check_license()
     *
     *	Check a specific license.
     *
     *	@uses	get_option()
     *	@uses	urlencode()
     *	@uses	home_url()
     *	@uses	wp_remote_post()
     *	@uses	is_wp_error()
     *	@uses	wp_remote_retrieve_body()
     *	@uses	json_decode()
     *	@uses	delete_option()
     *	@return	string	valid|invalid
     *
     *	@since 1.0.0
     */
//    public static function check_license( $id = '', $item = '' ) {
//
//        $licenses = get_option( 'wpsight_licenses' );
//
//        // retrieve the license from the database
//        $license = isset( $licenses[ $id ] ) ? trim( $licenses[ $id ] ) : false;
//
//        $api_params = array(
//            'edd_action'=> 'check_license',
//            'license'	=> $license,
//            'item_name' => urlencode( $item ),
//            'url'       => home_url()
//        );
//
//        // Call the custom API.
//        $response = wp_remote_post( WPSIGHT_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
//
//        if ( is_wp_error( $response ) )
//            return false;
//
//        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
////        var_dump($license_data);
//        update_option( 'wpsight_' . $id . '_status', $license_data->license );
//
//
//
//        return $license_data->license;
//
////		if( $license_data->license == 'valid' ) {
////			return 'valid';
////		} else {
////			//delete_option( 'wpsight_' . $id . '_status' );
////			return 'invalid';
////		}
//
//    }

    /**
     *	is_premium()
     *
     *	Check if premium is active.
     *	Premium is at least one active and valid license
     *	which grants access to specific features and support
     *
     *	@uses	get_option()
     *	@uses	wpsight_licenses()
     *	@uses	in_array()
     *	@return	bool	true|false
     *
     *	@since 1.2.0
     */
//    public static function is_premium() {
//        var_dump(wpsight_licenses());
//        foreach( wpsight_licenses() as $id => $license )
//            $keys[$id] = get_transient( 'wpsight_' . $license['id'] )->license;
//
//        if( in_array( 'valid', $keys ) )
//            return true;
//
//        return false;
//
//    }

    /**
     *	notice_setup()
     *
     *	Check if premium is active.
     *	Premium is at least one active and valid license
     *	which grants access to specific features and support
     *
     *	@uses	get_option()
     *	@uses	wpsight_licenses()
     *	@uses	in_array()
     *	@return	bool	true|false
     *
     *	@since 1.2.0
     */
    public static function notice_setup() {

        if( empty( wpsight_get_option( 'listings_page' ) ) ) {

            $link = admin_url() . 'admin.php?page=wpsight-settings#settings-listings';

            echo '<div id="" class="notice notice-warning">';
            echo '<p>' . sprintf( __( '<strong>Welcome to WPCasa</strong> &#8211; You&lsquo;re almost ready. Now go ahead and <a href="%s">setup your main listings page</a> as this is required in order to properly list your properties.', 'wpcasa' ), $link ) . '</p>';
            echo '</div>';

        }

    }


    /**
     *
     *	Return array of recommendation data
     *
     * @uses	apply_filters()
     * @return	array
     *
     *	@since 1.2.0
     */
    public static function recommends() {

        $recommends = [
            'dashboard' => [
                'title' =>  __( "", "wpcasa" ),
                'description' => __( "", "wpcasa" ),
                'image_url' => WPSIGHT_PLUGIN_URL . '/assets/img/wpcasa-recommendation-hubspot.jpg',
                'button_text' => __( "", "wpcasa" ),
                'button_link' => 'https://wpcasa.com/hubspot?ref=wpcasa-admin-dashboard',
            ],
            'hubspot' => [
                'title' =>  __( "", "wpcasa" ),
                'description' => __( "", "wpcasa" ),
                'image_url' => WPSIGHT_PLUGIN_URL . '/assets/img/wpcasa-recommendation-premium.jpg',
                'button_text' => __( "", "wpcasa" ),
                'button_link' => 'https://wpcasa.com?ref=wpcasa-admin-dashboard',
            ],
        ];

        $recommends = apply_filters('wpsight_recommendations', $recommends);

        return $recommends;

    }


}

if( ! class_exists( 'EDD_SL_Plugin_Updater' ) )
    include(dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php');