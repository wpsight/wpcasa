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
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'activate_licenses' ) );
		add_action( 'admin_init', array( $this, 'update_licenses' ) );
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
			<h2><?php
                /* translators: %s: is the name */
                echo sprintf( esc_html__( '%s Licenses', 'wpcasa' ), esc_html( WPSIGHT_NAME ) ); ?> <small>- <?php echo esc_html__( 'To receive premium support and seamless updates, please activate your licenses here.', 'wpcasa' ); ?></small></h2>
            <p><?php echo esc_html__( 'By activating a license, you agree that a safe connection to wpcasa.com will be generated for validation purposes. If validated and activated this information will be stored on wpcasa.com and will be associated with your account.', 'wpcasa' ); ?></p>
			<form method="post" action="options.php">

            	<div class="wpsight-settings-grid">

					<?php settings_fields( 'wpsight_licenses' ); ?>

					<?php
                    // Get license settings
					$licenses = get_option( 'wpsight_licenses' );

					foreach( wpsight_licenses() as $id => $license ) :
                        $license_data			= $this->update_and_get_license_data( $license );
						$option_key				= $license['id'];
						$option_value			= $licenses[$option_key] ?? false;
                        $license_status			= $license_data->license ?? false;

					?>
					<div class="wpsight-settings-panel">

						<div class="wpsight-settings-panel-head">

                            <?php echo esc_attr( $license['name'] ); ?>
                            <?php
							if( $license_status == 'valid' ) { ?>
							    <small style="color:green;">(<?php echo esc_attr( 'active' ); ?>)</small>
							<?php	echo '<span class="indicator indicator-valid tips" data-tip="' . esc_attr__( 'Valid', 'wpcasa' ) . '"></span>';
                                /* translators: %s: is the license expire date */
								echo '<span class="wpsight-settings-help tips" data-tip="' . esc_attr( sprintf( __( 'Valid until %s', 'wpcasa' ), date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) ) ) ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
							} elseif( $license_status == 'expired' ) {
								echo '<span class="indicator indicator-expired tips" data-tip="' . esc_attr__( 'Expired', 'wpcasa' ) . '"></span>';
								echo '<span class="wpsight-settings-help tips" data-tip="' . esc_attr__( 'License is expired. Please consider to re-new.', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
							} elseif( $license_status == 'inactive' || $license_status == 'site_inactive' ) {
								echo '<span class="indicator indicator-inactive tips" data-tip="' . esc_attr__( 'Inactive', 'wpcasa' ) . '"></span>';
								echo '<span class="wpsight-settings-help tips" data-tip="' . esc_attr__( 'Activate your license', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
							} elseif( $license_status == 'item_name_mismatch' ) {
								echo '<span class="indicator indicator-mismatch tips" data-tip="' . esc_attr__( 'Mismatch', 'wpcasa' ) . '"></span>';
								echo '<span class="wpsight-settings-help tips" data-tip="' . esc_attr__( 'Enter correct license key for this product.', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
							} else {
								echo '<span class="indicator"></span>';
								echo '<span class="wpsight-settings-help tips" data-tip="' . esc_attr__( 'Enter License Key here, save and activate.', 'wpcasa' ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
							}

							?>
						</div>

						<div class="wpsight-settings-panel-body">

                            <input id="wpsight_licenses[<?php echo esc_attr( $license['id'] ); ?>]" name="wpsight_licenses[<?php echo esc_attr( $license['id'] ); ?>]" type="text" class="regular-text" value="<?php echo esc_attr( $option_value ); ?>" />
							<?php if( $license_status == 'valid' ) { ?>
                                <input type="submit" class="button-secondary" name="wpsight_<?php echo esc_attr( $license['id'] ); ?>_deactivate" value="<?php echo esc_html__( 'Deactivate License', 'wpcasa' ); ?>"/>
                                <?php wp_nonce_field( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ); ?>
							<?php } elseif( $license_status == 'expired' ) { ?>
								<a href="https://wpcasa.com/account/licenses/" target="_blank" class="button-secondary"><?php echo esc_html__( 'Renew License', 'wpcasa' ); ?></a>
							<?php } elseif( $license_status == 'inactive' || $license_status == 'site_inactive' ) { ?>
                                <input type="submit" class="button-secondary" name="wpsight_<?php echo esc_attr( $license['id'] ); ?>_activate" value="<?php echo esc_html__( 'Activate License', 'wpcasa' ); ?>"/>
                                <?php wp_nonce_field( 'wpsight_' . $license['id'] . '_activate_nonce', 'wpsight_' . $license['id'] . '_activate_nonce' ); ?>
							<?php } else { ?>
                            	<span class="tips" data-tip="<?php echo esc_html__( 'Enter License Key first, then save and then activate.', 'wpcasa' ); ?>">
                                	 <input type="submit" class="button-secondary" name="wpsight_<?php echo esc_attr( $license['id'] ); ?>_activate" value="<?php echo esc_html__( 'Activate License', 'wpcasa' ); ?>"/>
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
		register_setting( 'wpsight_licenses', 'wpsight_licenses', array( $this, 'sanitize_license' ) );
	}

	/**
	 *	sanitize_license()
	 *
	 *	Check if the license key has changed
	 *	and deactivate the license if yes.
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


	function update_licenses() {
		foreach( wpsight_licenses() as $id => $license ) {

            $this->update_and_get_license_data( $license );
		}
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
    public function activate_license( $id = '', $item = '' ) {
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
        set_transient('wpsight_' . $id, $license_data, $this->transient_lifespan());
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
    public function deactivate_license( $id = '', $item = '' ) {

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
        set_transient('wpsight_' . $id, $license_data, $this->transient_lifespan());
    }


    /**
	 *	get_license_response()
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
	public function get_license_response( $key = '', $item = '' ) {

		$licenses = get_option( 'wpsight_licenses' );

		// retrieve the license from the database
		$license = isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false;

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

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * Get Data from API Endpoint
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function update_and_get_license_data( $license = null ) {

		// Set transient name
		$transient_name = 'wpsight_' . $license['id'];

		// Do we have this information in our transients already?
		$transient = get_transient( $transient_name );

		// Check transient
		if( ! empty( $transient ) )
			return $transient;

		// Get the Data
		$data = $this->get_license_response( $license['id'], $license['name'] );

		// Save the API response so we don't have to call again until tomorrow.

        set_transient($transient_name, $data, $this->transient_lifespan());

		// Return the data. The function will return here the first time it is run, and then once again, each time the transient expires.
		return $data;

	}


	/**
	 * If the user is a super admin and debug mode is on, only store transients for a second.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function transient_lifespan() {

//		if( is_super_admin() && WP_DEBUG ) {
			return DAY_IN_SECONDS;
//		} else {
//			return DAY_IN_SECONDS;
//		}

	}

    public static function is_premium() {
        foreach( wpsight_licenses() as $id => $license ) {
            $keys[$id] = get_transient( 'wpsight_' . $license['id'] )->license;
        }

        if( in_array( 'valid', $keys ) )
            return true;

        return false;
    }

}