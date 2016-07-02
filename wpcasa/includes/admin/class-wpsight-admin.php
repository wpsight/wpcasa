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
		include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-settings.php';
		include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-licenses.php';
		include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-agents.php';

		$this->cpt				= new WPSight_Admin_CPT();
		$this->settings_page	= new WPSight_Admin_Settings();
		$this->license_page		= new WPSight_Admin_Licenses();
		$this->agents			= new WPSight_Admin_Agents();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_filter( 'views_upload', array( $this, 'media_custom_views' ) );
		add_filter( 'views_edit-listing', array( $this, 'listings_custom_views' ) );
		add_filter( 'views_edit-property', array( $this, 'listings_custom_views' ) );
		add_filter( 'manage_users_columns', array( $this, 'manage_users_columns' ) );
		add_action( 'manage_users_custom_column', array( $this, 'manage_users_custom_column' ), 10, 3 );

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

		if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type, 'toplevel_page_wpsight-settings', 'wpcasa_page_wpsight-addons', 'wpcasa_page_wpsight-themes' ) ) ) {
			
			wp_enqueue_style( 'wpsight-admin', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-admin' . $suffix . '.css', array( 'cmb2-styles' ) );
			wp_register_script( 'jquery-tiptip', WPSIGHT_PLUGIN_URL . '/assets/js/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WPSIGHT_VERSION, true );
			wp_enqueue_script( 'wpsight_admin_js', WPSIGHT_PLUGIN_URL . '/assets/js/wpsight-admin' . $suffix . '.js', array( 'jquery', 'jquery-tiptip', 'jquery-ui-datepicker' ), WPSIGHT_VERSION, true );

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
		
		if ( apply_filters( 'wpsight_show_themes_page', true ) )
			add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Themes', 'wpcasa' ),  __( 'Themes', 'wpcasa' ) , 'manage_options', 'wpsight-themes', array( $this, 'themes_page' ) );
		
		if ( apply_filters( 'wpsight_show_licenses_page', true ) )
			add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Licenses', 'wpcasa' ),  __( 'Licenses', 'wpcasa' ) , 'manage_options', 'wpsight-licenses', array( $this->license_page, 'output' ) );
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
		$addons = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-addons.php';
		$addons->output();
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
		$themes = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-themes.php';
		$themes->output();
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
		return apply_filters( 'wpsight_options', array( 'listings' => array( __( 'Listings', 'wpcasa' ), (array) self::options_listings() ) ) );
	}
	
	/**
	 *	licenses()
	 *	
	 *	Create license array
	 *	
	 *	@return	array	$licenses
	 *	
	 *	@since 1.0.0
	 */
	public static function licenses() {

		$licenses = array();
		
		$licenses['support_package'] = array(
			'name' => __( 'Support Package', 'wpcasa' ),
			'desc' => __( 'To receive support for a free product please enter your support package license key. <a href="https://wpcasa.com/support/package/" target="_blank">More information&hellip;</a>', 'wpcasa' ),
			'id'   => 'support_package'
		);
		
		return apply_filters( 'wpsight_licenses', $licenses );
	
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
		
		$options_listings['listings_page'] = array(
			'name' => __( 'Listings Page', 'wpcasa' ),
			'desc' => __( 'Please select the main search results page with the <code>[wpsight_listings]</code> shortcode.', 'wpcasa' ),
			'id'   => 'listings_page',
			'type' => 'pages'
		);

		// Check of old 'property_id' options was active
		$listing_id_default = wpsight_get_option( 'property_id' ) ? wpsight_get_option( 'property_id' ) : __( 'ID-', 'wpcasa' );

		$options_listings['listing_id'] = array(
			'name'    => __( 'Listing ID Prefix', 'wpcasa' ),
			'id'      => 'listing_id',
			'desc'    => __( 'The listing ID will be this prefix plus post ID. You can optionally set individual IDs on the listing edit screen.', 'wpcasa' ),
			'default' => $listing_id_default,
			'type'    => 'text'
		);

		$options_listings['measurement_unit'] = array(
			'name'     => __( 'Measurement Unit', 'wpcasa' ),
			'desc'     => __( 'Please select the general measurement unit. The unit for the listing standard features can be defined separately below.', 'wpcasa' ),
			'id'       => 'measurement_unit',
			'default'  => 'm2',
			'class'    => 'mini',
			'type'     => 'select',
			'options'  => array_filter( wpsight_measurements() )
		);

		$options_listings['currency'] = array(
			'name'    => __( 'Currency', 'wpcasa' ),
			'desc'    => __( 'Please select the currency for the listing prices. If your currency is not listed, please select <code>Other</code>.', 'wpcasa' ),
			'id'      => 'currency',
			'default' => 'usd',
			'class'   => 'mini',
			'type'    => 'select',
			'options' => array_merge( array_filter( wpsight_currencies() ), array( 'other' => __( 'Other', 'wpcasa'  ) ) )
		);

		$options_listings['currency_other'] = array(
			'name'  => __( 'Other Currency', 'wpcasa' ) . ' (' . __( 'Abbreviation', 'wpcasa' ) . ')',
			'id'    => 'currency_other',
			'desc'  => __( 'Please insert the abbreviation of your currency (e.g. <code>EUR</code>).', 'wpcasa' ),
			'type'  => 'text',
			'class' => 'hidden'
		);

		$options_listings['currency_other_ent'] = array(
			'name'  => __( 'Other Currency', 'wpcasa' ) . ' (' . __( 'Symbol', 'wpcasa' ) . ')',
			'id'    => 'currency_other_ent',
			'desc'  => __( 'Please insert the currency symbol or HTML entity (e.g. <code>&amp;euro;</code>).', 'wpcasa' ),
			'type'  => 'text',
			'class' => 'hidden'
		);

		$options_listings['currency_symbol'] = array(
			'name'    => __( 'Currency Symbol', 'wpcasa' ),
			'desc'    => __( 'Please select the position of the currency symbol.', 'wpcasa' ),
			'id'      => 'currency_symbol',
			'default' => 'before',
			'type'    => 'radio',
			'options' => array( 'before' => __( 'Before the value', 'wpcasa' ), 'after' => __( 'After the value', 'wpcasa' ) )
		);

		$options_listings['currency_separator'] = array(
			'name'    => __( 'Thousands Separator', 'wpcasa' ),
			'desc'    => __( 'Please select the thousands separator for your listing prices.', 'wpcasa' ),
			'id'      => 'currency_separator',
			'default' => 'comma',
			'type'    => 'radio',
			'options' => array( 'comma' => __( 'Comma (e.g. 1,000,000)', 'wpcasa' ), 'dot' => __( 'Period (e.g. 1.000.000)', 'wpcasa' ) )
		);

		/** Toggle standard features */

		$options_listings['listing_features'] = array(
			'name'     => __( 'Listing Features', 'wpcasa' ),
			'cb_label' => __( 'Please check the box to edit the listing standard features.', 'wpcasa' ),
			'id'       => 'listing_features',
			'default'  => '0',
			'type'     => 'checkbox'
		);

		/** Loop through standard features */

		$i=1;

		foreach ( wpsight_details() as $feature_id => $value ) {

			$options_listings[ $feature_id ] = array(
				'name'    => __( 'Standard Feature', 'wpcasa' ) . ' #' . $i,
				'id'      => $feature_id,
				'desc'    => $value['description'],
				'default' => array( 'label' => $value['label'], 'unit' => $value['unit'] ),
				'type'    => 'measurement',
				'class'   => 'hidden'
			);

			$i++;

		}

		/** Toggle rental periods */

		$options_listings['rental_periods'] = array(
			'name'     => __( 'Rental Periods', 'wpcasa' ),
			'cb_label' => __( 'Please check the box to edit the rental periods.', 'wpcasa' ),
			'id'       => 'rental_periods',
			'default'  => '0',
			'type'     => 'checkbox'
		);

		/** Loop through rental periods */

		$i=1;

		foreach ( wpsight_rental_periods() as $period_id => $value ) {

			$options_listings[ $period_id ] = array(
				'name'    => __( 'Rental Period', 'wpcasa' ) . ' #' . $i,
				'id'      => $period_id,
				'default' => $value,
				'type'    => 'text',
				'class'   => 'hidden'
			);

			$i++;

		}

		$options_listings['date_format'] = array(
			'name'    => __( 'Date Format', 'wpcasa' ),
			'desc'    => __( 'Please select the date format for the listings table in the admin.', 'wpcasa' ),
			'id'      => 'date_format',
			'default' => get_option( 'date_format' ),
			'type'    => 'select',
			'options' => array_filter( wpsight_date_formats( true ) )
		);

		$options_listings['listings_css'] = array(
			'name'     => __( 'Output CSS', 'wpcasa' ),
			'cb_label' => __( 'Please uncheck the box to disable the plugin from outputting CSS.', 'wpcasa' ),
			'id'       => 'listings_css',
			'default'  => '1',
			'type'     => 'checkbox'
		);
		
		$options_listings['google_maps_api_key'] = array(
			'name'  => __( 'Google Maps API', 'wpcasa' ),
			'desc'	=> sprintf( __( 'If necessary, please enter your Google Maps API key (<a href="%s" target="_blank">register here</a>).', 'wpcasa' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ),
			'id'    => 'google_maps_api_key',
			'type'  => 'text'
		);

		return apply_filters( 'wpsight_options_listings', $options_listings );

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
			$views['publish'] = str_replace( __( 'Published' ), __( 'Active', 'wpcasa' ), $views['publish'] );

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
	 *	activate_license()
	 *	
	 *	Activate a specific license.
	 *	
	 *	@uses	get_option()
	 *	@uses	urlencode()
	 *	@uses	home_url()
	 *	@uses	wp_remote_post()
	 *	@uses	is_wp_error()
	 *	@uses	wp_remote_retrieve_body()
	 *	@uses	json_decode()
	 *	@uses	update_option()
	 *	
	 *	@since 1.0.0
	 */
	public static function activate_license( $id = '', $item = '' ) {
		
		$licenses = get_option( 'wpsight_licenses' );
	
		// retrieve the license from the database
		$license = trim( $licenses[ $id ] );
	
		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( $item ),
			'url'       => home_url()
		);
	
		// Call the custom API.
		$response = wp_remote_post( WPSIGHT_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	
		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;
	
		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
		// $license_data->license will be either "active" or "inactive"				
		update_option( 'wpsight_' . $id . '_status', $license_data->license );

	}
	
	/**
	 *	deactivate_license()
	 *	
	 *	Deactivate a specific license.
	 *	
	 *	@uses	get_option()
	 *	@uses	urlencode()
	 *	@uses	home_url()
	 *	@uses	wp_remote_post()
	 *	@uses	is_wp_error()
	 *	@uses	wp_remote_retrieve_body()
	 *	@uses	json_decode()
	 *	@uses	delete_option()
	 *	
	 *	@since 1.0.0
	 */
	public static function deactivate_license( $id = '', $item = '' ) {
		
		$licenses = get_option( 'wpsight_licenses' );
	
		// retrieve the license from the database
		$license = trim( $licenses[ $id ] );	
	
		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( $item ),
			'url'       => home_url()
		);
	
		// Call the custom API.
		$response = wp_remote_post( WPSIGHT_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	
		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;
	
		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'wpsight_' . $id . '_status' );

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
	public static function check_license( $id = '', $item = '' ) {
	
		$licenses = get_option( 'wpsight_licenses' );
	
		// retrieve the license from the database
		$license = isset( $licenses[ $id ] ) ? trim( $licenses[ $id ] ) : false;
	
		$api_params = array(
			'edd_action'=> 'check_license',
			'license'	=> $license,
			'item_name' => urlencode( $item ),
			'url'       => home_url()
		);
	
		// Call the custom API.
		$response = wp_remote_post( WPSIGHT_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	
		if ( is_wp_error( $response ) )
			return false;
	
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
		if( $license_data->license == 'valid' ) {
			return 'valid';
		} else {
			delete_option( 'wpsight_' . $id . '_status' );
			return 'invalid';
		}

	}

}

if( ! class_exists( 'EDD_SL_Plugin_Updater' ) )
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
