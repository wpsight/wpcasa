<h3><?php _e( 'Addons', 'wpcasa' ) ?></h3>
<a href="#addons-all" id="addons-all"><?php _e( 'All', 'wpcasa' ) ?></a>
<a href="#addons-active" id="addons-active"><?php _e( 'Active', 'wpcasa' ) ?></a>
<a href="#addons-inactive" id="addons-inactive"><?php _e( 'Inactive', 'wpcasa' ) ?></a>

<br>
<br>

<div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">

    <table class="wpsight-admin-ui-table wpsight-admin-ui-table-striped">

        <thead>
            <tr>
                <th><?php _e( 'Addon', 'wpcasa' ) ?></th>
                <th><?php _e( 'Version', 'wpcasa' ) ?></th>
                <th><?php _e( 'Author', 'wpcasa' ) ?></th>
                <th><?php _e( 'Status', 'wpcasa' ) ?></th>
            </tr>
        </thead>

        <tbody>

            <?php foreach( get_plugins() as $plugin => $p ) { ?>
            <?php //echo '<pre>'; var_dump( $p ); echo '</pre>'; ?>

                <?php if ( strpos( $p['Name'], 'WPCasa' ) !== false ) { ?>
                    <tr class="<?php if( is_plugin_active( $plugin ) ) { echo __( 'addon-active', 'wpcasa' ); } else { echo __( 'addon-inactive', 'wpcasa' ); } ?>">
                        <td><?php echo $p['Name'] ?></td>
                        <td><?php echo $p['Version'] ?></td>
                        <td><?php echo $p['Author'] ?></td>
                        <td><?php if( is_plugin_active( $plugin ) ) { echo __( 'Active', 'wpcasa' ); } else { echo __( 'Inactive', 'wpcasa' ); } ?></td>
                    </tr>
                <?php } ?>

            <?php } ?>
        </tbody>

    </table>

</div>


<?php /*?>
<div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">

    <table class="wpsight-admin-ui-table wpsight-admin-ui-table-striped">

        <thead>
            <tr>
                <th></th>
                <th><?php _e( 'Addon', 'wpcasa' ) ?></th>
                <th><?php _e( 'Version', 'wpcasa' ) ?></th>
                <th><?php _e( 'License Key', 'wpcasa' ) ?></th>
                <th></th>
            </tr>
        </thead>

        <tbody>

            <form method="post" action="options.php">

				<?php

				settings_fields( 'wpsight_licenses' );

				$admin_licenses_class = new WPSight_Admin_Licenses;

				// Get license settings
				$licenses = get_option( 'wpsight_licenses' );

				foreach( wpsight_licenses() as $id => $license ) :

					$option_key				= $license['id'];
					$option_status			= 'wpsight_' . $license['id'] . '_status';
					$option_value			= isset( $licenses[ $option_key ] ) ? $licenses[ $option_key ] : false;
					$option_value_status	= get_option( $option_status );

					$license_data			= $admin_licenses_class->get_license_data( $license );
					$license_status			= $option_value_status;

				?>

				<tr>
					<td width="50">

						<?php

						if( $license_status == 'valid' ) {

							echo '<div class="wpsight-admin-license-status tips" data-tip="' . sprintf( __( 'Valid until %s', 'wpcasa' ), date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) ) ) . '">';
								echo '<span class="indicator indicator-valid"></span>';
							echo '</div>';

						} elseif( $license_status == 'expired' ) {
							echo '<div class="wpsight-admin-license-status tips" data-tip="' . __( 'License is expired. Please consider to re-new.', 'wpcasa' ) . '">';
								echo '<span class="indicator indicator-expired"></span>';
							echo '</div>';
						} elseif( $license_status == 'inactive' || $license_status == 'site_inactive' ) {
							echo '<div class="wpsight-admin-license-status tips" data-tip="' . __( 'No License activated', 'wpcasa' ) . '">';
								echo '<span class="indicator indicator-inactive"></span>';
							echo '</div>';
						} elseif( $license_status == 'item_name_mismatch' ) {
							echo '<div class="wpsight-admin-license-status tips" data-tip="' . __( 'Enter the correct license key for this product.', 'wpcasa' ) . '">';
								echo '<span class="indicator indicator-mismatch"></span>';
							echo '</div>';
						} else {
							echo '<div class="wpsight-admin-license-status tips" data-tip="' . __( 'Enter License Key here, save and activate.', 'wpcasa' ) . '">';
								echo '<span class="indicator"></span>';
							echo '</div>';
						}

						?>

					</td>
					<td>

						<?php echo esc_attr( $license['name'] ); ?>

					</td>
					<td>

						<?php

						$name = $license['name'];
						$name = str_replace( ' ', '-', $name );
						$name = strtolower( $name );

						$plugin_file = ABSPATH . '/wp-content/plugins/' . $name . '/' . $name . '.php';

						if( file_exists( $plugin_file ) ) {
							$plugin_data = get_plugin_data( $plugin_file );
							echo $plugin_data['Version'];
						}

						?>

					</td>
					<td width="100">

						<input id="wpsight_licenses[<?php esc_attr_e( $license['id'] ); ?>]" name="wpsight_licenses[<?php esc_attr_e( $license['id'] ); ?>]" type="text" class="regular-text" value="<?php esc_attr_e( $option_value ); ?>" />

					</td>
					<td>

						<?php if( $license_status == 'valid' ) { ?>

						<button type="submit" class="button button-secondary button-icon tips" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_deactivate" data-tip="<?php _e( 'Deactivate License', 'wpcasa' ); ?>">
						<span class="dashicons dashicons-no-alt"></span>
						</button>
						<?php wp_nonce_field( 'wpsight_' . $license['id'] . '_deactivate_nonce', 'wpsight_' . $license['id'] . '_deactivate_nonce' ); ?>

						<?php } elseif( $license_status == 'expired' ) { ?>

						<a href="https://wpcasa.com/checkout/?nocache=true&edd_license_key=<?php esc_attr_e( $option_value ); ?>" target="_blank" class="button button-secondary button-icon tips" data-tip="<?php _e( 'Renew License', 'wpcasa' ); ?>"><span class="dashicons dashicons-update"></span></a>

						<?php } elseif( $license_status == 'item_name_mismatch' ) { ?>

						<button type="submit" class="button button-secondary button-icon tips" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_activate" data-tip="<?php _e( 'Item Name Mismatch', 'wpcasa' ); ?>" disabled="disabled">
						<span class="dashicons dashicons-info"></span>
						</button>

						<?php } elseif( $license_status == 'inactive' || $license_status == 'site_inactive' ) { ?>

						<button type="submit" class="button button-secondary button-icon tips" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_activate" data-tip="<?php _e( 'Inactive', 'wpcasa' ); ?>" disabled="disabled">
						<span class="dashicons dashicons-menu"></span>
						</button>

						<?php } else { ?>

						<button type="submit" class="button button-secondary button-icon tips" name="wpsight_<?php esc_attr_e( $license['id'] ); ?>_activate" data-tip="<?php _e( 'Activate License', 'wpcasa' ); ?>">
						<span class="dashicons dashicons-migrate"></span>
						</button>
						<?php wp_nonce_field( 'wpsight_' . $license['id'] . '_activate_nonce', 'wpsight_' . $license['id'] . '_activate_nonce' ); ?>

						<?php } ?>

					</td>
				</tr>

				<?php endforeach; ?>

                <?php //submit_button(); ?>

            </form>

        </tbody>

    </table>

</div><?php */?>
