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

		include_once( 'class-wpsight-cpt.php' );
		include_once( 'class-wpsight-settings.php' );
		include_once( 'class-wpsight-meta-boxes.php' );
		include_once( 'class-wpsight-agents.php' );

		$this->settings_page = new WPSight_Admin_Settings();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
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
			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

			// wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'wpsight_admin_css', WPSIGHT_PLUGIN_URL . '/assets/css/admin.css' );
			wp_register_script( 'jquery-tiptip', WPSIGHT_PLUGIN_URL . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WPSIGHT_VERSION, true );
			wp_enqueue_script( 'wpsight_admin_js', WPSIGHT_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'jquery-tiptip', 'jquery-ui-datepicker' ), WPSIGHT_VERSION, true );
		}
		
		if( in_array( $screen->id, array( 'profile', 'user-edit' ) ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'profile', WPSIGHT_PLUGIN_URL . '/assets/js/profile.js', array( 'jquery' ), WPSIGHT_VERSION, true );
		}

		wp_enqueue_style( 'wpsight_admin_menu_css', WPSIGHT_PLUGIN_URL . '/assets/css/menu.css' );
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
		$addons = include( 'class-wpsight-addons.php' );
		$addons->output();
	}
}

// Call wpSight_Admin class
new WPSight_Admin();
