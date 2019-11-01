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
      if ( isset( $_POST['reset'] ) ) {
        flush_rewrite_rules();
        update_option( $this->settings_name, wpsight_options_defaults() );
        echo '<div class="fade notice notice-info"><p>' . __( 'Settings reset.', 'wpcasa' ) . '</p></div>';
      } elseif ( isset( $_GET['settings-updated'] ) ) {
        flush_rewrite_rules();
        echo '<div class="fade notice notice-success"><p>' . __( 'Settings saved.', 'wpcasa' ) . '</p></div>';
      }
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

                        echo '<div id="settings-' . sanitize_title( $key ) . '" class="settings_panel">';

                            echo '<div class="wpsight-admin-ui-container">';

                                echo '<div class="wpsight-admin-ui-grid">';

                                    echo '<div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">';
                                        echo '<div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-large">';

                                            echo '<table class="form-table">';

                                            foreach ( $section[1] as $option ) {

                                                $option_id			= isset( $option['id'] ) ? $this->settings_name . '[' . $option['id'] . ']' : '';
                                                $option_css			= sanitize_html_class( $this->settings_name . '_' . $option['id'] );

                                                $option_name		= isset( $option['name'] )				? stripslashes ( $option['name'] )					: '';
                                                $option_desc		= isset( $option['desc'] )				? stripslashes ( $option['desc'] )					: '';
                                                $option_type		= isset( $option['type'] )				? $option['type']									: '';
                                                $option_icon		= isset( $option['icon'] )				? $option['icon']									: '';
                                                $option_link		= isset( $option['link'] )				? $option['link']									: '';
                                                $option_cb_label	= isset( $option['cb_label'] )			? $option['cb_label']								: '';
                                                $option_options		= isset( $option['options'] )			? $option['options']								: '';

                                                $placeholder		= isset( $option['placeholder'] )		? 'placeholder="' . $option['placeholder'] . '"'	: '';
                    //							$class				= isset( $option['class'] )				? 'class="' . $option['class'] . '"'				: '';
                                                $class				= isset( $option['class'] )				? ' ' . $option['class']							: '';

                                                $min				= isset( $option['min'] )				? 'min="' . $option['min'] . '"'					: null;
                                                $max				= isset( $option['max'] )				? 'max="' . $option['max'] . '"'					: null;
                                                $step				= isset( $option['step'] )				? 'step="' . $option['step'] . '"'					: null;

                                                $value				= wpsight_get_option( $option['id'] );

                                                if( ! isset( $value ) && isset( $option['default'] ) )
                                                    $value = $option['default'];

                                                $attributes = array();

                                                if ( isset( $option['attributes'] ) && is_array( $option['attributes'] ) )
                                                    foreach ( $option['attributes'] as $attribute_name => $attribute_value )
                                                        $attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';

                                                echo '<tr valign="top" class="setting-' . $option_css . '-tr' . $class . '">';

                                                    if( $option_type == 'pageheading' ) {

                                                        echo '<th scope="row" colspan="2">';

                                                            echo '<div class="wpsight-admin-ui-heading">';

                              echo '<div class="wpsight-admin-ui-heading-title">';

                                if ( $option_icon )
                                  echo '<span class="wpsight-admin-ui-icon"><span class="' . $option_icon . '"></span></span>';

                                echo '<h3>' . $option_name . '</h3>';

                                if ( $option_desc )
                                  echo '<small> - ' . $option_desc . '</small>';

                              echo '</div>';

                              echo '<div class="wpsight-admin-ui-heading-actions">';
                                if ( $option_link )
                                  echo ' <a href="' . $option_link. '" class="button button-primary" target="_blank">' . __( 'View Documentation', 'wpcasa' ) . '</a>';

                                submit_button( __( 'Save Changes', 'wpcasa' ), 'primary', 'wpsight-settings-save', false );

                              echo '</div>';

                                                            echo '</div>';

                                                        echo '</th>';

                        } elseif( $option_type == 'heading' ) {

                                                        echo '<th scope="row" colspan="2">';

                            echo '<h4>' . $option_name . '</h4>';

                            if ( $option_desc )
                              echo '<i>' . $option_desc . '</i>';

                                                        echo '</th>';

                                                    } else {

                                                        echo '<th scope="row">';

                                                            echo '<label for="setting-' . $option_css . '">' . $option_name . '</label>';

                                                            if ( $option_desc )
                                                                echo ' <p class="description">' . $option_desc . '</p>';

                                                        echo '</th>';

                                                        echo '<td>';

                                                        echo '<div class="wpsight-settings-field-wrap wpsight-settings-field-' . $option_type . '-wrap">';

                                                        switch ( $option_type ) {

                                                            case "heading" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <h4><?php echo $value; ?></h4>
                                                                </div>

                                                                <?php

                                                            break;
                                                            case "checkbox" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <div class="switch">
                                                                        <input id="setting-<?php echo $option_css; ?>" name="<?php echo $option_id; ?>" type="<?php echo $option_type; ?>" value="1" <?php echo implode( ' ', $attributes ); ?> <?php checked( '1', $value ); ?> />
                                                                        <label for="setting-<?php echo $option_css; ?>" class="label-<?php echo $option_type; ?>"><?php //echo $option_cb_label; ?></label>
                                                                    </div>
                                                                </div>

                                                                <?php

                                                            break;
                                                            case "multicheck" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">

                                                                    <?php

                                                                    foreach ( $option_options as $key => $name ) {

                                                                        $v = isset( $value[$key] ) ? $value[$key] : '';
                                                                    ?>
                                                                    <div class="multicheck">
                                                                        <input id="setting-<?php echo $option_css; ?>_<?php echo $key; ?>" name="<?php echo $option_id . '[' . $key . ']' ; ?>" type="checkbox" value="1" <?php //echo implode( ' ', $attributes ); ?> <?php checked( '1', $v ); ?> />
                                                                        <label for="setting-<?php echo $option_css; ?>_<?php echo $key; ?>" class="label-checkbox"><?php echo $name; ?></label>
                                                                    </div>

                                                                    <?php } ?>


                                                                </div>

                                                                <?php

                                                            break;
                                                            case "textarea" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <textarea id="setting-<?php echo $option_css; ?>" cols="100" rows="8" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?>><?php echo esc_textarea( $value ); ?></textarea>
                                                                </div>

                                                                <?php

                                                            break;
                                                            case "select" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <select id="setting-<?php echo $option_css; ?>" class="regular-text" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?>><?php
                                                                        foreach( $option_options as $key => $name )
                                                                            echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $name ) . '</option>'; ?>
                                                                    </select>
                                                                </div>

                                                                <?php

                                                            break;
                                                            case "pages" :

                              $page_args = array(
                                'sort_order' => 'asc',
                                'sort_column' => 'post_title',
                                'hierarchical' => 0
                              );

                              $get_pages = get_pages( $page_args );

                                                                $pages = array();

                                                                foreach ( $get_pages as $key => $page ) {

                                                                    $pages[$page->ID] = array();

                                                                    $pages[$page->ID]['name'] = $page->post_title;
                                                                    $pages[$page->ID]['date'] = $page->post_name;
                              }

//																	echo '<pre>';
//																	var_dump( $pages );
//																	echo '</pre>';

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <select id="setting-<?php echo $option_css; ?>" class="regular-text" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?>>
                                                                        <option value=""><?php _ex( 'Select page', 'plugin settings', 'wpcasa' ); ?>&hellip;</option><?php
                                                                        foreach( $pages as $key => $page )
                                                                            echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $page['name'] ) . ' <small><i>(' . esc_html( $page['date'] ) . ')<small><i></option>'; ?>
                                                                    </select>
                                                                </div>

                                                                <?php

                                                            break;
                                                            case "password" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <input id="setting-<?php echo $option_css; ?>" class="regular-text" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
                                                                </div>

                                                                <?php

                                                            break;

                                                            case "measurement" :

                                                                $measurement = $value;

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-text">
                                                                    <input id="setting-<?php echo $option_css; ?>_label" class="regular-text" type="text" name="<?php echo $option_id . '[label]'; ?>" value="<?php echo esc_attr( $measurement['label'] ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
                                                                </div>

                                                                <div class="wpsight-settings-field wpsight-settings-field-radio">

                                                                    <?php

                                                                    foreach ( wpsight_measurements() as $key => $unit ) {
                                                                        $id = $option_css .'-'. $key;

                                                                    ?>

                                                                    <input id="setting-<?php echo $id; ?>" name="<?php echo esc_attr( $option_id ); ?>[unit]" type="radio" value="<?php echo esc_attr( $key ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php checked( $measurement['unit'], $key ); ?> />
                                                                    <label for="setting-<?php echo $id; ?>" class="label-radio"><?php if( empty( $unit ) ) { echo 'None'; } else { echo $unit; } ?></label>

                                                                    <?php } ?>

                                                                </div>

                                                                <?php

                                                            break;

                                                            case "radio" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">

                                                                    <?php

                                                                    $name = $option_name .'['. $option_id .']';

                                                                    foreach ( $option['options'] as $key => $option ) {
                                                                        $id = $option_css .'-'. $key;

                                                                        ?>

                                                                        <input id="setting-<?php echo $id; ?>" name="<?php echo esc_attr( $option_id ); ?>" type="<?php echo $option_type; ?>" value="<?php echo esc_attr( $key ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php checked( $value, $key ); ?> />
                                                                        <label for="setting-<?php echo $id; ?>" class="label-<?php echo $option_type; ?>"><?php echo $option; ?></label>

                                                                    <?php } ?>

                                                                </div>

                                                                <?php

                                                            break;

                                                            case "range" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <input id="setting-<?php echo $option_css; ?>" class="range-slider__range" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> <?php echo $min; ?> <?php echo $max; ?> <?php echo $step; ?> />
                                                                    <span class="range-slider__value">0</span>
                                                                </div>

                                                                <?php

                                                            break;

                                                            case "number" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <input id="setting-<?php echo $option_css; ?>" class="regular-text" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
                                                                </div>

                                                                <?php

                                                            break;

                                                            case "" :
                                                            case "input" :
                                                            case "text" :

                                                                ?>

                                                                <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
                                                                    <input id="setting-<?php echo $option_css; ?>" class="regular-text" type="text" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
                                                                </div>

                                                                <?php

                                                            break;
                                                            default :
                                                                do_action( 'wpsight_settings_field_' . $option_type, $option, $attributes, $value, $placeholder );
                                                            break;

                                                        }

                                                        echo '</div>';

                                                        echo '</td>';

                                                    }

                                                echo '</tr>';

                                            }

                                            echo '</table>';

                                        echo '</div>';
                                    echo '</div>';

                                echo '</div>';

                            echo '</div>';

                        echo '</div>';

                    }

                ?>

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
                                                  <form method="post" action="">
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

		<script type="text/javascript">

			var totoggle_currency = '.setting-<?php echo $this->settings_name; ?>_currency_other-tr, .setting-<?php echo $this->settings_name; ?>_currency_other_ent-tr';

			jQuery('#setting-<?php echo $this->settings_name; ?>_currency').change(function() {

			  	if( jQuery(this).val() == 'other' ) {
					jQuery(totoggle_currency).fadeIn(150);
				} else {
					jQuery(totoggle_currency).fadeOut(150);
				}
			});

			<?php
				/** Loop through standard details and hide them */
				$totoggle_details = array();
				foreach( wpsight_details() as $feature => $value ) {
					$totoggle_details[] = '.' . sanitize_html_class( '.setting-' . $this->settings_name . '_' . $feature . '-tr' );
				}
				$totoggle_details = implode( ', ' , $totoggle_details );
			?>

			var totoggle_details = '<?php echo $totoggle_details; ?>';

			jQuery('#setting-<?php echo $this->settings_name; ?>_listing_features').click(function() {
  				jQuery(totoggle_details).fadeToggle(150);
			});

			if (jQuery('#setting-<?php echo $this->settings_name; ?>_listing_features:checked').val() !== undefined) {
				jQuery(totoggle_details).show();
			}

			<?php
				/** Loop through standard details and hide them */
				$totoggle_periods = array();
				foreach( wpsight_rental_periods() as $period_id => $value ) {
					$totoggle_periods[] = '.' . sanitize_html_class( '.setting-' . $this->settings_name . '_' . $period_id . '-tr' );
				}
				$totoggle_periods = implode( ', ' , $totoggle_periods );
			?>

			var totoggle_periods = '<?php echo $totoggle_periods; ?>';

			jQuery('#setting-<?php echo $this->settings_name; ?>_rental_periods').click(function() {
  				jQuery(totoggle_periods).fadeToggle(150);
			});

			if (jQuery('#setting-<?php echo $this->settings_name; ?>_rental_periods:checked').val() !== undefined) {
				jQuery(totoggle_periods).show();
			}



			jQuery('.addon-inactive').hide();

			jQuery('#addons-all').click(function(e) {
				e.preventDefault();
				jQuery('.addon-active').show();
				jQuery('.addon-inactive').show();
			});

			jQuery('#addons-active').click(function(e) {
				e.preventDefault();
				jQuery('.addon-active').show();
				jQuery('.addon-inactive').hide();
			});

			jQuery('#addons-inactive').click(function(e) {
				e.preventDefault();
				jQuery('.addon-active').hide();
				jQuery('.addon-inactive').show();
			});


		</script>
		<?php

//		do_action( 'wpsight_settings_scripts', $this->settings_name );
//
//        foreach(get_option( WPSIGHT_DOMAIN ) as $key => $default ) {
//            wpsight_delete_option($key);
//        }
//
//        $options = array(
//            'listings_page'			=> '',
//            'listing_id'			=> __( 'ID-', 'wpcasa' ),
//            'measurement_unit'		=> 'm2',
//            'currency'				=> 'usd',
//            'currency_symbol'		=> 'before',
//            'currency_separator'	=> 'comma',
//            'date_format'			=> get_option( 'date_format' ),
//            'listings_css'			=> '1'
//        );
//
//        foreach( $options as $option => $value ) {
//
//            if( wpsight_get_option( $option ) )
//                continue;
//
//            wpsight_add_option( $option, $value );
//
//        }

	}
}
