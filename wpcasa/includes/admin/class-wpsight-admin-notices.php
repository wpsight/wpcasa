<?php
/**
 * Display notices in admin
 *
 * @author      WPSight
 * @category    Admin
 * @package     WPCasa/Admin
 * @version     1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPSight_Admin_Notices Class.
 */
class WPSight_Admin_Notices {

	/**
	 * Stores notices.
	 * @var array
	 */
	private static $notices = array();

	/**
	 * Array of notices - name => callback.
	 * @var array
	 */
	private static $core_notices = array(
//		'install'             => 'install_notice',
//		'update'              => 'update_notice',
//		'template_files'      => 'template_file_check_notice',
		'theme_support'       => 'theme_check_notice'
	);

	/**
	 * Constructor.
	 */
	public static function init() {
		
		self::$notices = get_option( 'wpsight_admin_notices', array() );

		add_action( 'switch_theme',				array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'woocommerce_installed',	array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'wp_loaded',				array( __CLASS__, 'hide_notices' ) );
		add_action( 'shutdown',					array( __CLASS__, 'store_notices' ) );

//		if ( current_user_can( 'manage_options' ) )
			add_action( 'admin_print_styles',	array( __CLASS__, 'add_notices' ) );
			
	}

	/**
	 * Store notices to DB
	 */
	public static function store_notices() {
		update_option( 'wpsight_admin_notices', self::get_notices() );
	}

	/**
	 * Get notices
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}

	/**
	 * Remove all notices.
	 */
	public static function remove_all_notices() {
		self::$notices = array();
	}

	/**
	 * Reset notices for themes when switched or a new version of WC is installed.
	 */
	public static function reset_admin_notices() {
		
		//if ( ! current_theme_supports( 'wpcasa' ) && ! in_array( get_option( 'template' ), wc_get_core_supported_themes() ) ) {
		if ( ! current_theme_supports( 'wpcasa' ) ) {
			self::add_notice( 'theme_support' );
		}

		self::add_notice( 'template_files' );
		
	}

	/**
	 * Show a notice.
	 * @param string $name
	 */
	public static function add_notice( $name ) {
		self::$notices = array_unique( array_merge( self::get_notices(), array( $name ) ) );
	}

	/**
	 * Remove a notice from being displayed.
	 * @param  string $name
	 */
	public static function remove_notice( $name ) {
		self::$notices = array_diff( self::get_notices(), array( $name ) );
		delete_option( 'wpsight_admin_notice_' . $name );
	}

	/**
	 * See if a notice is being shown.
	 * @param  string  $name
	 * @return boolean
	 */
	public static function has_notice( $name ) {
		return in_array( $name, self::get_notices() );
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		
		if ( isset( $_GET['wpsight-hide-notice'] ) && isset( $_GET['_wpsight_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_wpsight_notice_nonce'], 'wpsight_hide_notices_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'wpcasa' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'wpcasa' ) );
			}

			$hide_notice = sanitize_text_field( $_GET['wpsight-hide-notice'] );
			self::remove_notice( $hide_notice );
			do_action( 'wpsight_hide_' . $hide_notice . '_notice' );
		}
		
	}

	/**
	 * Add notices + styles if needed.
	 */
	public static function add_notices() {
		$notices = self::get_notices();

		if ( ! empty( $notices ) ) {
			//wp_enqueue_style( 'wpcasa-activation', plugins_url(  '/assets/css/activation.css', WC_PLUGIN_FILE ) );
			foreach ( $notices as $notice ) {
				if ( ! empty( self::$core_notices[ $notice ] ) && apply_filters( 'wpsight_show_admin_notice', true, $notice ) ) {
					add_action( 'admin_notices', array( __CLASS__, self::$core_notices[ $notice ] ) );
				} else {
					add_action( 'admin_notices', array( __CLASS__, 'output_custom_notices' ) );
				}
			}
		}
	}

	/**
	 * Add a custom notice.
	 * @param string $name
	 * @param string $notice_html
	 */
	public static function add_custom_notice( $name, $notice_html ) {
		self::add_notice( $name );
		update_option( 'wpsight_admin_notice_' . $name, wp_kses_post( $notice_html ) );
	}

	/**
	 * Output any stored custom notices.
	 */
	public static function output_custom_notices() {
		$notices = self::get_notices();

		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				if ( empty( self::$core_notices[ $notice ] ) ) {
					$notice_html = get_option( 'wpsight_admin_notice_' . $notice );

					if ( $notice_html ) {
						include( 'views/html-notice-custom.php' );
					}
				}
			}
		}
	}

	/**
	 * If we need to update, include a message with the update button.
	 */
	public static function update_notice() {
//		if ( version_compare( get_option( 'wpcasa_db_version' ), WPSIGHT_VERSION, '<' ) ) {
//			$updater = new WC_Background_Updater();
//			if ( $updater->is_updating() || ! empty( $_GET['do_update_woocommerce'] ) ) {
//				include( 'views/html-notice-updating.php' );
//			} else {
//				include( 'views/html-notice-update.php' );
//			}
//		} else {
//			include( 'views/html-notice-updated.php' );
//		}
	}

	/**
	 * If we have just installed, show a message with the install pages button.
	 */
	public static function install_notice() {
//		include( 'views/html-notice-install.php' );
	}

	/**
	 * Show the Theme Check notice.
	 */
	public static function theme_check_notice() {
		//if ( ! current_theme_supports( 'wpcasa' ) && ! in_array( get_option( 'template' ), wc_get_core_supported_themes() ) ) {
		if ( ! current_theme_supports( 'wpcasa' ) ) {
			include( 'views/html-notice-theme-support.php' );
		} else {
			self::remove_notice( 'theme_support' );
		}
	}

	/**
	 * Show a notice highlighting bad template files.
	 */
//	public static function template_file_check_notice() {
//		$core_templates = WC_Admin_Status::scan_template_files( WC()->plugin_path() . '/templates' );
//		$outdated       = false;
//
//		foreach ( $core_templates as $file ) {
//
//			$theme_file = false;
//			if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
//				$theme_file = get_stylesheet_directory() . '/' . $file;
//			} elseif ( file_exists( get_stylesheet_directory() . '/woocommerce/' . $file ) ) {
//				$theme_file = get_stylesheet_directory() . '/woocommerce/' . $file;
//			} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
//				$theme_file = get_template_directory() . '/' . $file;
//			} elseif( file_exists( get_template_directory() . '/woocommerce/' . $file ) ) {
//				$theme_file = get_template_directory() . '/woocommerce/' . $file;
//			}
//
//			if ( $theme_file !== false ) {
//				$core_version  = WC_Admin_Status::get_file_version( WC()->plugin_path() . '/templates/' . $file );
//				$theme_version = WC_Admin_Status::get_file_version( $theme_file );
//
//				if ( $core_version && $theme_version && version_compare( $theme_version, $core_version, '<' ) ) {
//					$outdated = true;
//					break;
//				}
//			}
//		}
//
//		if ( $outdated ) {
//			include( 'views/html-notice-template-check.php' );
//		} else {
//			self::remove_notice( 'template_files' );
//		}
//	}

}

WPSight_Admin_Notices::init();
