<h3><?php _e( 'Addons', 'wpcasa' ) ?></h3>

<div class="wrap-addons-tabs">
    <a href="#addons-all" class="active" id="addons-all"><?php _e( 'All', 'wpcasa' ) ?></a>
    <a href="#addons-active" id="addons-active"><?php _e( 'Active', 'wpcasa' ) ?></a>
    <a href="#addons-inactive" id="addons-inactive"><?php _e( 'Inactive', 'wpcasa' ) ?></a>
</div>

<div class="wpsight-admin-ui-grid-col wpsight-admin-addons wpsight-admin-ui-grid-1-1">

    <table class="wpsight-admin-ui-table addons-info-desktop wpsight-admin-ui-table-striped">

        <thead>
            <tr>
                <th><?php _e( 'Addon', 'wpcasa' ) ?></th>
                <th><?php _e( 'Version', 'wpcasa' ) ?></th>
                <th><?php _e( 'Author', 'wpcasa' ) ?></th>
                <th><?php _e( 'Status', 'wpcasa' ) ?></th>
                <?php /*?><th><?php _e( 'Action', 'wpcasa' ) ?></th><?php */?>
            </tr>
        </thead>

        <tbody>

            <?php foreach( get_plugins() as $plugin => $p ) { ?>

                <?php if ( strpos( esc_html( $p['Name'] ), 'WPCasa' ) !== false ) { ?>
                    <tr class="<?php if( is_plugin_active( $plugin ) ) { echo __( 'addon-active', 'wpcasa' ); } else { echo __( 'addon-inactive', 'wpcasa' ); } ?>">
                        <td><?php echo esc_html( $p['Name'] ) ?></td>
                        <td><?php echo esc_html( $p['Version'] ) ?></td>
                        <td><?php echo esc_html( $p['Author'] ) ?></td>
                        <td class="<?php if( is_plugin_active( $plugin ) ) { echo 'status-active'; } else { echo 'status-inactive'; } ?>">
                          <?php if( is_plugin_active( $plugin ) ) { echo __( 'Active', 'wpcasa' ); } else { echo __( 'Inactive', 'wpcasa' ); } ?>
                        </td>
                        <?php /*?><td>
                            <a href="#" class="addons-table-btn"><?php _e( 'Button', 'wpcasa' ) ?></a>
                        </td><?php */?>
                    </tr>
                <?php } ?>

            <?php } ?>
        </tbody>

    </table>

    <ul class="addons-info-mobile">
        <?php foreach( get_plugins() as $plugin => $p ) {

            if ( strpos( esc_html( $p['Name'] ), 'WPCasa' ) !== false ) { ?>
                <li class="addons-list <?php if( is_plugin_active( $plugin ) ) { echo __( 'addon-active', 'wpcasa' ); } else { echo __( 'addon-inactive', 'wpcasa' ); } ?>">
                    <div class="addons-list-card">
                        <div class="content">
                            <div class="content-top">
                                <span class="content-name"><?php echo esc_html( $p['Name'] ) ?></span>
                                <button class="content-btn">
                                    <svg width="13" height="9" viewBox="0 0 13 9" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon">
                                        <line y1="-1" x2="9.41783" y2="-1" transform="matrix(-0.668965 0.743294 0.668965 0.743294 13 2)" stroke="white" stroke-width="2"></line> <line y1="-1" x2="9.41783" y2="-1" transform="matrix(0.668965 0.743294 -0.668965 0.743294 0.400391 2)" stroke="white" stroke-width="2"></line>
                                    </svg>
                                </button>
                            </div>

                            <ul class="content-bottom">
                                <li class="content-item">
                                    <span class="text"><?php _e( 'Addon', 'wpcasa' ) ?></span>
                                    <span class="text"><?php echo esc_html( $p['Name'] ) ?></span>
                                </li>

                                <li class="content-item">
                                    <span class="text"><?php _e( 'Version', 'wpcasa' ) ?></span>
                                    <span class="text"><?php echo esc_html( $p['Version'] ) ?></span>
                                </li>

                                <li class="content-item">
                                    <span class="text"><?php _e( 'Author', 'wpcasa' ) ?></span>
                                    <span class="text"><?php echo esc_html( $p['Author'] ) ?></span>
                                </li>

                                <li class="content-item">
                                    <span class="text"><?php _e( 'Status', 'wpcasa' ) ?></span>
                                    <span class="text <?php if( is_plugin_active( $plugin ) ) { echo 'status-active'; } else { echo 'status-inactive'; } ?>">
                                      <?php if( is_plugin_active( $plugin ) ) { echo __( 'Active', 'wpcasa' ); } else { echo __( 'Inactive', 'wpcasa' ); } ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php }

        } ?>
    </ul>

</div>