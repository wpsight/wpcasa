<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Settings class
 */
class WPSight_Admin_Settings {

	/**
	 *	Constructor
	 */
	public function __construct() {

		$this->settings_group = WPSIGHT_DOMAIN;
		$this->settings_name  = WPSIGHT_DOMAIN;

		add_action( 'admin_init',			array( $this, 'register_settings' ) );
		add_filter( 'admin_body_class',		array( $this, 'admin_body_class' ) );

    add_action( 'admin_post_reset_settings',  array( $this, 'reset_settings' ) );
	}

	/**
	 *	admin_body_class()
	 *
	 *	@since 1.1.0
	 */
	public function admin_body_class( $classes ) {

		$screen		= get_current_screen();
		$post_type	= wpsight_post_type();

		if ( in_array( $screen->id, array( 'toplevel_page_wpsight-settings' ) ) ) {
			$classes .= ' wpsight-settings-page ';
		} elseif( in_array( $screen->id, array( 'wpcasa_page_wpsight-addons', 'wpcasa_page_wpsight-themes', 'wpcasa_page_wpsight-licenses' ) ) ) {
			$classes .= ' wpsight-extras-page ';
		} elseif( in_array( $screen->id, array( 'edit-' . $post_type, $post_type ) ) ) {
			$classes .= ' wpsight-post-type-page ';
		}

		return $classes;
	}

	/**
	 *	init_settings()
	 *
	 *	@access	protected
	 *	@uses	get_editable_roles()
	 *	@uses	apply_filters()
	 *	@uses	wpsight_options()
	 *
	 *	@since 1.0.0
	 */
	protected function init_settings() {

		// Prepare roles option
		$roles         = get_editable_roles();
		$account_roles = array();

		foreach ( $roles as $key => $role ) {
			if ( $key == 'administrator' )
				continue;
			$account_roles[ $key ] = $role['name'];
		}

		$this->settings = apply_filters( 'wpsight_settings', wpsight_options() );

	}

	/**
	 *	register_settings()
	 *
	 *	@access	public
	 *	@uses	$this->init_settings()
	 *	@uses	get_option()
	 *	@uses	add_option()
	 *	@uses	wpsight_options_defaults()
	 *	@uses	register_setting()
	 *
	 *	@since 1.0.0
	 */
	public function register_settings() {

		$this->init_settings();

	    // If no settings available, set defaults

	    if( get_option( $this->settings_name ) === false )
	    	add_option( $this->settings_name, wpsight_options_defaults() );

		register_setting( $this->settings_group, $this->settings_name );

	}

	/**
	 *	output()
	 *
	 *	@access	public
	 *	@uses	$this->init_settings()
	 *	@uses	settings_fields()
	 *	@uses	flush_rewrite_rules()
	 *	@uses	wpsight_options_defaults()
	 *	@uses	update_option()
	 *	@uses	wpsight_get_option()
	 *	@uses	do_action()
	 *	@uses	submit_button()
	 *
	 *	@since 1.0.0
	 */
	public function output() {

		$this->init_settings(); ?>

    <?php
//      if ( isset( $_POST['reset'] ) ) {
//        flush_rewrite_rules();
//        update_option( $this->settings_name, wpsight_options_defaults() );
//        echo 'testtt';
//        echo '<div class="fade notice notice-info"><p>' . __( 'Settings reset.', 'wpcasa' ) . '</p></div>';
//      } elseif ( isset( $_GET['settings-updated'] ) ) {
//        flush_rewrite_rules();
//        echo '<div class="fade notice notice-success"><p>' . __( 'Settings saved.', 'wpcasa' ) . '</p></div>';
//      }
    ?>

		<div class="wrap wpsight-settings-wrap">

        <div class="wpsight-admin-sidebar-back"></div>
        <div class="wpsight-admin-sidebar">

            <div class="wpsight-admin-intro-box">
                <div class="wpsight-admin-ui-image">
                    <img src="<?php echo WPSIGHT_PLUGIN_URL . '/assets/img/wpcasa-admin-logo.jpg' ?>" />
                </div>
            </div>

            <div class="wpsight-admin-nav nav-tab-wrapper">

                <a href="#settings-overview" id="settings-overview-tab" class="nav-tab"><span class="dashicons dashicons-laptop"></span><?php _e( 'Overview', 'wpcasa' ); ?></a>
                <?php
                    foreach ( $this->settings as $key => $section )
                        echo '<a href="#settings-' . sanitize_title( $key ) . '" id="settings-' . sanitize_title( $key ) . '-tab" class="nav-tab">' . $section[0] . '</a>';
                ?>
                <a href="#settings-tools" id="settings-tools-tab" class="nav-tab"><span class="dashicons dashicons-admin-tools"></span><?php _e( 'Tools', 'wpcasa' ); ?></a>

            </div>

        </div>

        <div class="wpsight-admin-main">

            <div class="wpsight-admin-ui-panel wpsight-admin-main-wrap-btn-toggle">
                <button class="wpsight-admin-main-btn-toggle">
                    <span class="wpsight-admin-main-btn-toggle-line"></span>
                    <span class="wpsight-admin-main-btn-toggle-line"></span>
                    <span class="wpsight-admin-main-btn-toggle-line"></span>
                </button>
            </div>

            <div id="settings-overview" class="settings_panel">

                <div class="wpsight-admin-ui-container">

                    <div class="wpsight-admin-ui-grid settings_panel_boxes wpsight-admin-ui-grid-same-height">

                        <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-grid-col-same-height">
                            <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-account">
                              <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-account.php'; ?>
                            </div>
                        </div>

                        <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-grid-same-height">
                            <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-documentation">
                              <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-documentation.php'; ?>
                            </div>
                        </div>

                        <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-grid-same-height">
                            <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-support">
                              <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-support.php'; ?>
                            </div>
                        </div>

                    </div>

                    <div class="wpsight-admin-ui-grid">

                        <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-2-3 wpsight-admin-ui-panel-wrap-theme">

                            <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-theme">

                              <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-theme.php'; ?>

                            </div>

                            <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-addons">

                              <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-addons.php'; ?>

                            </div>

                        </div>

                        <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-panel-wrap-theme-bar">

                             <div class="wpsight-admin-ui-panel-wrap-theme-bar-item wpsight-admin-ui-panel-wrap-theme-bar-images">
                                  <?php if( wpsight_is_premium() == false ) { ?>
                                      <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-auto-height wpsight-admin-ui-panel-system wpsight-admin-ui-no-padding">
                                          <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-promo-products.php'; ?>
                                      </div>
                                  <?php } ?>

                                  <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-auto-height wpsight-admin-ui-panel-system wpsight-admin-ui-no-padding">
                                      <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-promo-services.php'; ?>
                                  </div>
                             </div>

                            <div class="wpsight-admin-ui-panel-wrap-theme-bar-item wpsight-admin-ui-panel-wrap-theme-bar-content">
                                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-system">
                                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-server-info.php'; ?>
                                </div>

                                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-newsletter">
                                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-newsletter.php'; ?>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <form method="post" action="options.php">

                <?php settings_fields( $this->settings_group ); ?>

                <?php

                    foreach ( $this->settings as $key => $section ) {

                        echo '<div id="settings-' . sanitize_title( $key ) . '" class="settings_panel">'; ?>

                            <div class="wpsight-admin-ui-container">

                                <div class="wpsight-admin-ui-grid">

                                    <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">
                                        <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-large">

                                            <table class="form-table">

                                            <?php foreach ( $section[1] as $option ) {
                                                $option_css			= sanitize_html_class( $this->settings_name . '_' . $option['id'] );

                                                $option_name		= isset( $option['name'] )				? stripslashes ( $option['name'] )					: '';
                                                $option_desc		= isset( $option['desc'] )				? stripslashes ( $option['desc'] )					: '';
                                                $option_type		= isset( $option['type'] )				? $option['type']									: '';
                                                $class				= isset( $option['class'] )				? ' ' . $option['class']							: '';

                                                ?>

                                                <tr valign="top" class="setting-<?php echo $option_css ?>-tr<?php echo $class ?>">
                                                    <?php

                                                    if( ( $option_type == 'pageheading' ) || ( $option_type == 'heading' ) ) {
                                                        require  plugin_dir_path( __FILE__ ) . 'views/option-' . $option_type . '.php';

                                                    } else { ?>

                                                        <th scope="row">
                                                            <label for="setting-' . $option_css . '"><?php echo $option_name ?></label>
                                                            <p class="description"><?php echo $option_desc ?></p>
                                                        </th>
                                                        <td>
                                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-' . $option_type . '-wrap">

                                                            <?php require  plugin_dir_path( __FILE__ ) . 'views/option-' . $option_type . '.php';
                                                            ?>

                                                            </div>
                                                        </td>
                                                    <?php  } ?>
                                                </tr>

                                                <?php  } ?>

                                            </table>

                                        </div>
                                    </div>

                                </div>

                            </div>

                       </div>

                   <?php  } ?>

            </form>

            <div id="settings-tools" class="settings_panel">

                <div class="wpsight-admin-ui-container">

                    <div class="wpsight-admin-ui-grid">


                        <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">

                            <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-large">

                                <table class="form-table">

                                    <tr valign="top">

                                        <th scope="row" colspan="2">

                                            <div class="wpsight-admin-ui-heading">

                                                <div class="wpsight-admin-ui-heading-title">

                                                    <span class="wpsight-admin-ui-icon"><span class="dashicons dashicons-admin-tools"></span></span>
                                                    <h3><?php _e( 'Tools', 'wpcasa' ); ?></h3>
                                                    <small> - <?php _e( 'Sample Description', 'wpcasa' ); ?></small>

                                                </div>

                                                <div class="wpsight-admin-ui-heading-actions">
                                                    <a href="#" class="button button-primary" target="_blank"><?php _e( 'View Documentation', 'wpcasa' ); ?></a>
                                                </div>

                                            </div>

                                        </th>

                                    </tr>

                                    <tr valign="top">

                                        <th scope="row">
                                            <label><?php _e( 'Restore Defaults', 'wpcasa' ); ?></label>
                                            <p class="description"><?php _e( 'This will restore all the settings to the defaults. Use this if you want to start over again.', 'wpcasa' ); ?></p>
                                        </th>

                                        <td>
                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-reset-wrap">
                                                <div class="wpsight-settings-field wpsight-settings-field-reset">

                                                    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                                                        <input type="hidden" name="action" value="reset_settings">
                                                        <?php wp_nonce_field("reset", "reset_settings"); ?>
                                                        <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Reset Settings', 'wpcasa' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Are you sure?', 'wpcasa' ) ); ?>' );" />
                                                    </form>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                    <tr valign="top">

                                        <th scope="row">
                                            <label><?php _e( 'Migrate Data', 'wpcasa' ); ?></label>
                                            <p class="description"><?php _e( 'This will migrate data from the old wpCasa Theme Framework, into the new WPCasa Format. Please make sure to fully backup your site before you proceed. Only use if you have used (or still use) the old wpCasa Theme (or one of its child themes like Ushuaia, Penthouse, Marbella,...) and are now in the process to migrate to the new WPCasa plugin, and dont see your properties.', 'wpcasa' ); ?></p>
                                        </th>

                                        <td>

                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-reset-wrap">
                                                <div class="wpsight-settings-field wpsight-settings-field-reset">
                                                    <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Migrate Data', 'wpcasa' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Do you really want to perform the migration?', 'wpcasa' ) ); ?>' );" />
                                                </div>
                                            </div>

                                        </td>

                                    </tr>

                                    <tr valign="top">

                                        <th scope="row">
                                            <label><?php _e( 'Delete all Transients', 'wpcasa' ); ?></label>
                                            <p class="description"><?php _e( 'Transients are used in order to store specific kind of data. For example it stores currency exchange rates but also license information. You can safely delete all transients in order to see if it helps fixing an issue you came across.', 'wpcasa' ); ?></p>
                                        </th>

                                        <td>

                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-reset-wrap">
                                                <div class="wpsight-settings-field wpsight-settings-field-reset">
                                                    <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Delete Data', 'wpcasa' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Do you really want to perform the migration?', 'wpcasa' ) ); ?>' );" />
                                                </div>
                                            </div>

                                        </td>

                                    </tr>

                                    <tr valign="top">

                                        <th scope="row">
                                            <label><?php _e( 'Delete all Data', 'wpcasa' ); ?></label>
                                            <p class="description"><?php _e( 'This will erase all data completely. Use this if you want to start over. Keep in mind that this does only erase WPCasa-related data and dont touch data from any other plugins. If you want to completely reset your site we would recommend to have a look at WP Reset.', 'wpcasa' ); ?></p>
                                        </th>

                                        <td>

                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-reset-wrap">
                                                <div class="wpsight-settings-field wpsight-settings-field-reset">
                                                    <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Delete Data', 'wpcasa' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Do you really want to perform the migration?', 'wpcasa' ) ); ?>' );" />
                                                </div>
                                            </div>

                                        </td>

                                    </tr>

                                </table>

                            </div>

                        </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

		</div>

		<?php

		do_action( 'wpsight_settings_scripts', $this->settings_name );

  }
    public function reset_settings() {

      check_admin_referer( 'reset', 'reset_settings' );

      foreach(get_option( WPSIGHT_DOMAIN ) as $key => $default ) {
            wpsight_delete_option($key);
        }

        $options = array(
            'listings_page'			=> '',
            'listing_id'			=> __( 'ID-', 'wpcasa' ),
            'measurement_unit'		=> 'm2',
            'currency'				=> 'usd',
            'currency_symbol'		=> 'before',
            'currency_separator'	=> 'comma',
            'date_format'			=> get_option( 'date_format' ),
            'listings_css'			=> '1'
        );

        foreach( $options as $option => $value ) {

            if( wpsight_get_option( $option ) )
                continue;

            wpsight_add_option( $option, $value );

        }

        wp_redirect( admin_url("/admin.php?page=wpsight-settings"), 301 );
        exit;

        wp_die();
    }
}
