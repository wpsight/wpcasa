<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_Admin_Licenses class
 */
class WPSight_Admin_Licenses {

    /**
     *	Constructor
     */
    public function __construct() {
        if ( !isset($_GET['page']) && !($_GET['page'] == 'wpsight-licenses') ) {
            add_action( 'admin_init', array( $this, 'update_licenses' ) );
        }

        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_init', array( $this, 'activate_licenses' ) );
    }

    /**
     *	output()
     *
     *	Handles output of the licenses page in admin.
     *
     *	@uses	settings_fields()
     *	@uses	get_option()
     *	@uses	wpsight_licenses()
     *	@uses	wp_nonce_field()
     *	@uses	submit_button()
     *
     *	@since 1.0.0
     */
    public function output() { ?>

        <div class="wrap">

        <h2><?php echo WPSIGHT_NAME . ' ' . __( 'Licenses', 'wpcasa' ); ?> <small>- <?php _e( 'To receive premium support and seamless updates, please activate your licenses here.', 'wpcasa' ); ?></small></h2>
        <p><?php _e( 'By activating a license, you agree that a safe connection to wpcasa.com will be generated for validation purposes. If validated and activated this information will be stored on wpcasa.com and will be associated with your account.', 'wpcasa' ); ?></p>
        <form method="post" action="options.php">

            <div class="wpsight-settings-grid">

                <?php settings_fields( 'wpsight_licenses' ); ?>

                <?php
                // Get license settings
                $licenses = get_option( 'wpsight_licenses' );

                foreach( wpsight_licenses() as $id => $license ) :
                    $transient_name = 'wpsight_' . $license['id'];

                    // Do we have this information in our transients already?
                    $license_data = get_transient( $transient_name );
                    $option_key				= $license['id'];
                    $option_value			= isset( $licenses[ $option_key ] ) ? $licenses[ $option_key ] : false;

                    $license_status	        = $license_data ? $license_data->license : 'error';
                    $license_expires        = $license_data ? strtotime( $license_data->expires ) : 'error';
                    ?>
                    <div class="wpsight-settings-panel">

                        <div class="wpsight-settings-panel-head">

                            <?php echo esc_attr( $license['name'] ); ?>
                            <?php
                            if( $license_status == 'valid' ) { ?>
                                <small style="color:green;">(<?php _e( 'active', 'wpcasa' ); ?>)</small>
                                <?php	echo '<span class="indicator indicator-valid tips" data-tip="' . __( 'Valid', 'wpcasa' ) . '"></span>';
                                echo '<span class="wpsight-settings-help tips" data-tip="' . sprintf( __( 'Valid until %s', 'wpcasa' ), date_i18n( get_option( 'date_format' ), $license_expires ) ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
                            } elseif( $license_status == 'expired' ) {
                                echo '<span class="indicator indicator-expired tips" data-tip="' . __( 'Expired', 'wpcasa' ) . '"></span>';
                                echo '<span class="wpsight-settings-help tips" data-tip="' . __( 'License is expired. Please consider to re-new.', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
                            } elseif( $license_status == 'inactive' || $license_status == 'site_inactive' ) {
                                echo '<span class="indicator indicator-inactive tips" data-tip="' . __( 'Inactive', 'wpcasa' ) . '"></span>';
                                echo '<span class="wpsight-settings-help tips" data-tip="' . __( 'Activate your license', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
                            } elseif( $license_status == 'item_name_mismatch' ) {
                                echo '<span class="indicator indicator-mismatch tips" data-tip="' . __( 'Mismatch', 'wpcasa' ) . '"></span>';
                                echo '<span class="wpsight-settings-help tips" data-tip="' . __( 'Enter correct license key for this product.', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
                            } else {
                                echo '<span class="indicator"></span>';
                                echo '<span class="wpsight-settings-help tips" data-tip="' . __( 'Enter License Key here, save and activate.', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
                            }

                            ?>
                        </div>

                        <div class="wpsight-settings-panel-body">

                            <input id="wpsight_licenses[<?php esc_attr_e( $license['id'] ); ?>]" name="wpsight_licenses[<?php esc_attr_e( $license['id'] ); ?>]" type="text" class="regular-text" value="<?php esc_attr_e( $option_value ); ?>" />
                            <?php if( $license_status == 'valid' ) { ?>
                                <input type="submit" class="button-secondary" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_deactivate" value="<?php _e( 'Deactivate License', 'wpcasa' ); ?>"/>
                                <?php wp_nonce_field( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ); ?>
                            <?php } elseif( $license_status == 'expired' ) { ?>
                                <a href="https://wpcasa.com/account/licenses/" target="_blank" class="button-secondary"><?php _e( 'Renew License', 'wpcasa' ); ?></a>
                            <?php } elseif( $license_status == 'inactive' || $license_status == 'site_inactive' ) { ?>
                                <input type="submit" class="button-secondary" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_activate" value="<?php _e( 'Activate License', 'wpcasa' ); ?>"/>
                                <?php wp_nonce_field( 'wpsight_' . $license['id'] . '_activate_nonce', 'wpsight_' . $license['id'] . '_activate_nonce' ); ?>
                            <?php } else { ?>
                                <span class="tips" data-tip="<?php _e( 'Enter License Key first, then save and then activate.', 'wpcasa' ); ?>">
                                	 <input type="submit" class="button-secondary" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_activate" value="<?php _e( 'Activate License', 'wpcasa' ); ?>"/>
                                     <?php wp_nonce_field( 'wpsight_' . $license['id'] . '_activate_nonce', 'wpsight_' . $license['id'] . '_activate_nonce' ); ?>
                                </span>
                            <?php } ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <?php submit_button(); ?>

        </form>

        <?php

    }

    /**
     *	register_settings()
     *
     *	Register license settings in options table.
     *
     *	@uses	register_setting()
     *
     *	@since 1.0.0
     */
    function register_settings() {
        register_setting( 'wpsight_licenses', 'wpsight_licenses', array( $this, 'sanitize_license'  ));
    }

    /**
     *	sanitize_license()
     *
     *	Check if the license key has changed
     *	and deactivate license if yes.
     *
     *	@uses	get_option()
     *	@uses	wpsight_licenses()
     *	@uses	wpsight_deactivate_license()
     *
     *	@since 1.0.0
     */
    function sanitize_license( $new ) {

        $old = get_option( 'wpsight_licenses' );

        foreach( wpsight_licenses() as $id => $license ) {
            $license_id = $license['id'];
            if( isset( $old[ $license_id ] ) && $old[ $license_id ] != $new[ $license_id ] ) {
                // If license key has changed, deactivate old license
                $this->deactivate_license( $license_id, $license['name'] );
            }
        }

        return $new;

    }

    /**
     *	activate_licenses()
     *
     *	Check if the license keys need
     *	to be activated or deactivated.
     *
     *	@uses	wpsight_licenses()
     *	@uses	check_admin_referer()
     *	@uses	wpsight_activate_license()
     *	@uses	delete_transient()
     *
     *	@since 1.0.0
     */
    function activate_licenses() {
        foreach( wpsight_licenses() as $id => $license ) {

            // listen for our activate button to be clicked
            if( isset( $_POST[ 'wpsight_' . $license['id'] . '_activate' ] ) && check_admin_referer( 'wpsight_' . $license['id'] . '_activate_nonce', 'wpsight_' . $license['id'] . '_activate_nonce' ) ) {
                $this->activate_license( $license['id'], $license['name'] );
            }

            // listen for our deactivate button to be clicked
            if( isset( $_POST[ 'wpsight_' . $license['id'] . '_deactivate' ] ) && check_admin_referer( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ) ) {
                $this->deactivate_license( $license['id'], $license['name'] );
            }

        }

    }

    /**
     *	activate_license()
     *
     *	Activate a specific license.
     *
     *	@uses	update_and_set_license_data()
     *	@since 1.0.0
     */
    public function activate_license( $id = '', $item = '' ) {
        $this->update_and_set_license_data('activate_license', $id, $item);
    }

    /**
     *	deactivate_license()
     *
     *	Deactivate a specific license.
     *
     *	@uses	update_and_set_license_data()
     *
     *	@since 1.0.0
     */
    public function deactivate_license( $id = '', $item = '' ) {
        $this->update_and_set_license_data('deactivate_license', $id, $item);
    }


    /**
     *    get_license_response()
     *
     *    Check a specific license.
     *
     * @param $action
     * @param string $key
     * @param string $item
     * @return    string    valid|invalid
     *
     * @uses    get_option()
     * @uses    urlencode()
     * @uses    home_url()
     * @uses    wp_remote_post()
     * @uses    is_wp_error()
     * @uses    wp_remote_retrieve_body()
     * @uses    json_decode()
     * @since 1.2.2
     */
    public function update_and_set_license_data( $action = 'check_license' ,$key = '', $item = '' ) {
        $licenses = get_option( 'wpsight_licenses' );
        $transient_name = 'wpsight_' . $key;

        // retrieve the license from the database
        $license = isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false;

        $api_params = array(
            'edd_action'=> $action,
            'license'	=> $license,
            'item_name' => urlencode( $item ),
            'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( WPSIGHT_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        if ( is_wp_error( $response ) ) {
            set_transient($transient_name, 'error', $this->transient_lifespan());

            return false;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ) );
        set_transient($transient_name, $data, $this->transient_lifespan());
    }

    /**
     * update_licenses()
     *  Update licenses
     *
     * @uses    wpsight_licenses()
     * @uses    get_transient()
     * @uses    update_and_set_license_data()
     * @since 1.2.2
     */
    function update_licenses() {
        foreach( wpsight_licenses() as $id => $license ) {
            $transient_name = 'wpsight_' . $license['id'];
            if( ! empty(  get_transient( $transient_name ) ) )
                continue;

            $this->update_and_set_license_data('check_license',  $license['id'], $license['name'] );
        }
    }


    /**
     * If the user is a super admin and debug mode is on, only store transients for a second.
     *
     * @since  1.0.0
     * @access public
     */
    public function transient_lifespan() {
        return DAY_IN_SECONDS;
    }

    /**
     * Check if any of licenses is activated
     *
     * @since  1.2.0
     * @access public
     */
    public static function is_premium() {
        foreach( wpsight_licenses() as $id => $license ) {
            $keys[$id] = get_transient( 'wpsight_' . $license['id'] )->license;
        }

        if( in_array( 'valid', $keys ) )
            return true;

        return false;
    }

}
