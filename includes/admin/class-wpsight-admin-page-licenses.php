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
//        add_action( 'admin_init', array( $this, 'check_licenses' ) );
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

						$option_key				= $license['id'];
						$option_status			= 'wpsight_' . $license['id'] . '_status';
						$option_value			= isset( $licenses[ $option_key ] ) ? $licenses[ $option_key ] : false;
						$option_value_status	= get_option( $option_status );

						$license_data			= $this->get_license_data( $license );
						$license_status			= $option_value_status;

					?>

					<div class="wpsight-settings-panel">

						<div class="wpsight-settings-panel-head">
                            <?php echo esc_attr( $license['name'] ); ?>
                            <?php
							if( $license_status == 'valid' ) { ?>
							    <small style="color:green;">(<?php _e( 'active', 'wpcasa' ); ?>)</small>
							<?php	echo '<span class="indicator indicator-valid tips" data-tip="' . __( 'Valid', 'wpcasa' ) . '"></span>';
								echo '<span class="wpsight-settings-help tips" data-tip="' . sprintf( __( 'Valid until %s', 'wpcasa' ), date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) ) ) . '"><span class="dashicons dashicons-editor-help"></span></span>';
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
		register_setting( 'wpsight_licenses', 'wpsight_licenses', array( $this, 'sanitize_license' ) );
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
				wpsight_deactivate_license( $license_id, $license['name'] );
				delete_transient( 'wpsight_' . $license_id );
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
				wpsight_activate_license( $license['id'], $license['name'] );

//				delete_transient( 'wpsight_' . $license['id'] );
			}

			// listen for our deactivate button to be clicked
			if( isset( $_POST[ 'wpsight_' . $license['id'] . '_deactivate' ] ) && check_admin_referer( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ) ) {
//                wpsight_check_license( $license['id'], $license['name'] );
				wpsight_deactivate_license( $license['id'], $license['name'] );
//                wpsight_check_license( $license['id'], $license['name'] );

//				delete_transient( 'wpsight_' . $license['id'] );
			}

		}

	}

	/**
	 *	check_licenses()
	 *
	 *	Check if the license keys have been
	 *	deactivated from the website account.
	 *	The result is cached for 12 hours.
	 *
	 *	@uses	wpsight_licenses()
	 *	@uses	get_transient()
	 *	@uses	wpsight_check_license()
	 *	@uses	set_transient()
	 *
	 *	@since 1.0.0
	 */
	function check_licenses() {

		foreach( wpsight_licenses() as $id => $license ) {
//            var_dump($check_license = get_transient( 'wpsight_' . $license['id'] ));
//            it's check not working
//			if ( false === ( $check_license = get_transient( 'wpsight_' . $license['id'] ) ) ) {
            $check_license = wpsight_check_license( $license['id'], $license['name'] );
            set_transient( 'wpsight_' . $license['id'], $check_license, 12 * HOUR_IN_SECONDS );
//			}


		}

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
	public function get_license_data( $license = null ) {

		// Set transient name
		$transient_name = 'wpsight_' . $license['id'] . '_license_data';

		// Do we have this information in our transients already?
		$transient = get_transient( $transient_name );

		// Check transient
		if( ! empty( $transient ) )
			return $transient;

		// Get the Data
		$data = $this->get_license_response( $license['id'], $license['name'] );

		// Save the API response so we don't have to call again until tomorrow.
		set_transient( $transient_name, $data, $this->transient_lifespan() );

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

		if( is_super_admin() && WP_DEBUG ) {
			return DAY_IN_SECONDS;
		} else {
			return DAY_IN_SECONDS;
		}

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
//	public function check_license( $id = '', $item = '' ) {
//
//		$licenses = get_option( 'wpsight_licenses' );
//
//		// retrieve the license from the database
//		$license = isset( $licenses[ $id ] ) ? trim( $licenses[ $id ] ) : false;
//
//		$api_params = array(
//			'edd_action'=> 'check_license',
//			'license'	=> $license,
//			'item_name' => urlencode( $item ),
//			'url'       => home_url()
//		);
//
//		// Call the custom API.
//		$response = wp_remote_post( WPSIGHT_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
//
//		if ( is_wp_error( $response ) )
//			return false;
//
//		$license_data	= json_decode( wp_remote_retrieve_body( $response ) );
//		$license		= $license_data->license;
//
//		if( $license )
//			return $license;
//
//	}

}
