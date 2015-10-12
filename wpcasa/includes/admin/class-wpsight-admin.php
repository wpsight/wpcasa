<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * wpSight_Admin class
 */
class WPSight_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {

		include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-cpt.php';
		include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-settings.php';
		include_once WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-agents.php';

		$this->cpt           = new WPSight_Admin_CPT();
		$this->settings_page = new WPSight_Admin_Settings();
		$this->agents        = new WPSight_Admin_Agents();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_filter( 'views_upload', array( $this, 'media_custom_views' ) );
		add_filter( 'views_edit-listing', array( $this, 'listings_custom_views' ) );
		add_filter( 'views_edit-property', array( $this, 'listings_custom_views' ) );
		add_filter( 'manage_users_columns', array( $this, 'manage_users_columns' ) );
		add_action( 'manage_users_custom_column', array( $this, 'manage_users_custom_column' ), 10, 3 );
	}

	/**
	 * admin_enqueue_scripts()
	 *
	 * Enqueue scripts and styles used
	 * on WordPress admin pages.
	 *
	 * @access public
	 * @uses get_current_screen()
	 * @uses wp_enqueue_style()
	 * @uses wp_register_script()
	 * @uses wp_enqueue_script()
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {
		global $wp_scripts;

		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'edit-listing', 'listing', 'toplevel_page_wpsight-settings', 'wpcasa_page_wpsight-addons' ) ) ) {
			
			wp_enqueue_style( 'wpsight-admin', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-admin.css' );
			wp_register_script( 'jquery-tiptip', WPSIGHT_PLUGIN_URL . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), '1.3', true );
			wp_enqueue_script( 'wpsight_admin_js', WPSIGHT_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'jquery-tiptip', 'jquery-ui-datepicker' ), WPSIGHT_VERSION, true );

		}

		if ( in_array( $screen->id, array( 'profile', 'user-edit' ) ) )
			wp_enqueue_media();

	}

	/**
	 * admin_menu()
	 *
	 * Add wpSight settings main and
	 * sub pages to the admin menu.
	 *
	 * @access public
	 * @uses add_menu_page()
	 * @uses add_submenu_page()
	 * @uses apply_filters()
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {

		add_menu_page( WPSIGHT_NAME, WPSIGHT_NAME, 'manage_options', 'wpsight-settings', array( $this->settings_page, 'output' ), 'dashicons-marker' );

		add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Settings', 'wpsight' ),  __( 'Settings', 'wpsight' ) , 'manage_options', 'wpsight-settings', array( $this->settings_page, 'output' ) );

		if ( apply_filters( 'wpsight_show_addons_page', true ) )
			add_submenu_page(  'wpsight-settings', WPSIGHT_NAME . ' ' . __( 'Addons', 'wpsight' ),  __( 'Addons', 'wpsight' ) , 'manage_options', 'wpsight-addons', array( $this, 'addons_page' ) );
	}

	/**
	 * addons_page()
	 *
	 * Add wpSight addons page to sub menu.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function addons_page() {
		$addons = include WPSIGHT_PLUGIN_DIR . '/includes/admin/class-wpsight-addons.php';
		$addons->output();
	}

	/**
	 * Merge option tabs and
	 * return wpsight_options_listings()
	 *
	 * @since 1.0
	 */

	public static function options() {

		$options = array( 'listings' => array( __( 'Listings', 'wpsight' ), (array) wpsight_options_listings() ) );

		return apply_filters( 'wpsight_options', $options );

	}


	/**
	 * Create theme options array
	 * Listings options
	 *
	 * @since 0.8
	 */

	public static function options_listings() {

		/** Define data arrays */

		$options_listings = array();

		$options_listings['listings_page'] = array(
			'name' => __( 'Listings Page', 'wpsight' ),
			'desc' => __( 'Please select the main search results page with the <code>[wpsight_listings]</code> shortcode.', 'wpsight' ),
			'id'   => 'listings_page',
			'type' => 'pages'
		);

		// Check of old 'property_id' options was active
		$listing_id_default = wpsight_get_option( 'property_id' ) ? wpsight_get_option( 'property_id' ) : __( 'ID-', 'wpsight' );

		$options_listings['listing_id'] = array(
			'name'    => __( 'Listing ID Prefix', 'wpsight' ),
			'id'      => 'listing_id',
			'desc'    => __( 'The listing ID will be this prefix plus post ID. You can optionally set individual IDs on the listing edit screen.', 'wpsight' ),
			'default' => $listing_id_default,
			'type'    => 'text'
		);

		$options_listings['measurement_unit'] = array(
			'name'     => __( 'Measurement Unit', 'wpsight' ),
			'desc'     => __( 'Please select the general measurement unit. The unit for the listing standard features can be defined separately below.', 'wpsight' ),
			'id'       => 'measurement_unit',
			'default'  => 'm2',
			'class'    => 'mini',
			'type'     => 'select',
			'options'  => array_filter( wpsight_measurements() )
		);

		$options_listings['currency'] = array(
			'name'    => __( 'Currency', 'wpsight' ),
			'desc'    => __( 'Please select the currency for the listing prices. If your currency is not listed, please select <code>Other</code>.', 'wpsight' ),
			'id'      => 'currency',
			'default' => 'usd',
			'class'   => 'mini',
			'type'    => 'select',
			'options' => array_merge( array_filter( wpsight_currencies() ), array( 'other' => __( 'Other', 'wpsight'  ) ) )
		);

		$options_listings['currency_other'] = array(
			'name'  => __( 'Other Currency', 'wpsight' ) . ' (' . __( 'Abbreviation', 'wpsight' ) . ')',
			'id'    => 'currency_other',
			'desc'  => __( 'Please insert the abbreviation of your currency (e.g. <code>EUR</code>).', 'wpsight' ),
			'type'  => 'text',
			'class' => 'hidden'
		);

		$options_listings['currency_other_ent'] = array(
			'name'  => __( 'Other Currency', 'wpsight' ) . ' (' . __( 'Symbol', 'wpsight' ) . ')',
			'id'    => 'currency_other_ent',
			'desc'  => __( 'Please insert the currency symbol or HTML entity (e.g. <code>&amp;euro;</code>).', 'wpsight' ),
			'type'  => 'text',
			'class' => 'hidden'
		);

		$options_listings['currency_symbol'] = array(
			'name'    => __( 'Currency Symbol', 'wpsight' ),
			'desc'    => __( 'Please select the position of the currency symbol.', 'wpsight' ),
			'id'      => 'currency_symbol',
			'default' => 'before',
			'type'    => 'radio',
			'options' => array( 'before' => __( 'Before the value', 'wpsight' ), 'after' => __( 'After the value', 'wpsight' ) )
		);

		$options_listings['currency_separator'] = array(
			'name'    => __( 'Thousands Separator', 'wpsight' ),
			'desc'    => __( 'Please select the thousands separator for your listing prices.', 'wpsight' ),
			'id'      => 'currency_separator',
			'default' => 'comma',
			'type'    => 'radio',
			'options' => array( 'comma' => __( 'Comma (e.g. 1,000,000)', 'wpsight' ), 'dot' => __( 'Period (e.g. 1.000.000)', 'wpsight' ) )
		);

		/** Toggle standard features */

		$options_listings['listing_features'] = array(
			'name'     => __( 'Listing Features', 'wpsight' ),
			'cb_label' => __( 'Please check the box to edit the listing standard features.', 'wpsight' ),
			'id'       => 'listing_features',
			'default'  => '0',
			'type'     => 'checkbox'
		);

		/** Loop through standard features */

		$i=1;

		foreach ( wpsight_details() as $feature_id => $value ) {

			$options_listings[$feature_id] = array(
				'name'    => __( 'Standard Feature', 'wpsight' ) . ' #' . $i,
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
			'name'     => __( 'Rental Periods', 'wpsight' ),
			'cb_label' => __( 'Please check the box to edit the rental periods.', 'wpsight' ),
			'id'       => 'rental_periods',
			'default'  => '0',
			'type'     => 'checkbox'
		);

		/** Loop through rental periods */

		$i=1;

		foreach ( wpsight_rental_periods() as $period_id => $value ) {

			$options_listings[$period_id] = array(
				'name'    => __( 'Rental Period', 'wpsight' ) . ' #' . $i,
				'id'      => $period_id,
				'default' => $value,
				'type'    => 'text',
				'class'   => 'hidden'
			);

			$i++;

		}

		$options_listings['date_format'] = array(
			'name'    => __( 'Date Format', 'wpsight' ),
			'desc'    => __( 'Please select the date format for the listings table in the admin.', 'wpsight' ),
			'id'      => 'date_format',
			'default' => get_option( 'date_format' ),
			'type'    => 'select',
			'options' => array_filter( wpsight_date_formats( true ) )
		);

		return apply_filters( 'wpsight_options_listings', $options_listings );

	}

	/**
	 * Media library views
	 *
	 * @since 1.2
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
			$views['all'] = '<a href="' . $pagenow . '">' . __( 'All', 'wpsight' ) . ' <span class="count">(' . $_total_posts . ')</span></a>';
			$views['found'] = '<a href="' . $pagenow . '?s=' . $_GET['s'] . '" class="current">' . $_GET['s'] . ' <span class="count">(' . $wp_query->found_posts . ')</span></a>';
		}

		return $views;
	}

	/**
	 * Listing views
	 *
	 * @since 1.2
	 */

	public static function listings_custom_views( $views ) {
		global $wpdb, $wp_query, $pagenow;

		if ( 'edit.php' != $pagenow )
			return;

		// Replace 'Published' with 'Active'
		
		if( isset( $views['publish'] ) )
			$views['publish'] = str_replace( __( 'Published' ), __( 'Active', 'wpsight' ), $views['publish'] );

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
	 * Show listing count
	 * in user list
	 *
	 * @since 1.2
	 */

	public static function manage_users_columns( $columns ) {
		$columns['listings_count'] = __( 'Listings', 'wpsight' );
		return $columns;
	}

	/**
	 *  Show number of listings the user has
	 *
	 *  @param   string  $value
	 *  @param   string  $column_name
	 *  @param   int  $user_id
	 *  @uses  count_user_posts()
	 *
	 *  @return  string new value
	 */
	public static function manage_users_custom_column( $value, $column_name, $user_id ) {

		if ( 'listings_count' != $column_name  )
			return $value;

		$listings_count = count_user_posts( $user_id, wpsight_post_type() );
		$user_listings_links = '<a href="edit.php?author=' . $user_id . '&post_type=' . wpsight_post_type() . '">' . $listings_count . '</a>';

		return $user_listings_links;

	}
}
