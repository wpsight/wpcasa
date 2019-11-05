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