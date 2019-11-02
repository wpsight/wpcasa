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
		add_action( 'admin_init', array( $this, 'check_licenses' ) );
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
			<h2><?php echo WPSIGHT_NAME . ' ' . __( 'Licenses', 'wpcasa' ); ?></h2>
			<form method="post" action="options.php">			
				<?php settings_fields( 'wpsight_licenses' ); ?>				
				<table class="form-table">
					<tbody>
					
						<?php
							// Get license settings
							$licenses = get_option( 'wpsight_licenses' );
							
							foreach( wpsight_licenses() as $id => $license ) :
						
								$option_key				= $license['id'];
								$option_status			= 'wpsight_' . $license['id'] . '_status';
								$option_value			= isset( $licenses[ $option_key ] ) ? $licenses[ $option_key ] : false;
								$option_value_status	= get_option( $option_status );
							
							?>
					
							<tr valign="top">	
								<th scope="row" valign="top">
									<?php echo esc_attr( str_replace( WPSIGHT_NAME . ' ', '', $license['name'] ) ); ?>
									<?php if( false !== $option_value && $option_value_status !== false && $option_value_status == 'valid' ) { ?>
									   <br /><small style="color:green;">(<?php _e( 'active', 'wpcasa' ); ?>)</small>
									<?php } ?>
								</th>
								<td>
									<input id="wpsight_licenses[<?php esc_attr_e( $license['id'] ); ?>]" name="wpsight_licenses[<?php esc_attr_e( $license['id'] ); ?>]" type="text" class="regular-text" value="<?php esc_attr_e( $option_value ); ?>" />
									<?php if( false !== $option_value && ! empty( $option_value ) ) : ?>
										<?php if( $option_value_status !== false && $option_value_status == 'valid' ) { ?>
											<input type="submit" class="button-secondary" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_deactivate" value="<?php _e( 'Deactivate License', 'wpcasa' ); ?>"/>
											<?php wp_nonce_field( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ); ?>
										<?php } else { ?>
											<input type="submit" class="button-secondary" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_activate" value="<?php _e( 'Activate License', 'wpcasa' ); ?>"/>
											<?php wp_nonce_field( 'wpsight_' . $license['id'] . '_activate_nonce', 'wpsight_' . $license['id'] . '_activate_nonce' ); ?>
										<?php } ?>
									<?php endif; ?>									
									<br />
									<p class="description">
										<?php echo wp_kses_post( $license['desc'] ); ?>
									</p>
								</td>
							</tr>						
						<?php endforeach; ?>						
					</tbody>
				</table>	
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
				delete_transient( 'wpsight_' . $license['id'] );
			}
			
			// listen for our deactivate button to be clicked
			if( isset( $_POST[ 'wpsight_' . $license['id'] . '_deactivate' ] ) && check_admin_referer( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ) ) {
				wpsight_deactivate_license( $license['id'], $license['name'] );
				delete_transient( 'wpsight_' . $license['id'] );
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
			
			if ( false === ( $check_license = get_transient( 'wpsight_' . $license['id'] ) ) ) {
				$check_license = wpsight_check_license( $license['id'], $license['name'] );
				set_transient( 'wpsight_' . $license['id'], $check_license, 12 * HOUR_IN_SECONDS );
			}

		}

	}
	
}
