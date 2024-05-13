<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h3><?php echo esc_html__( 'Addons', 'wpcasa' ) ?></h3>

<div class="wrap-addons-tabs">
    <a href="#addons-all" class="active" id="addons-all"><?php echo esc_html__( 'All', 'wpcasa' ) ?></a>
    <a href="#addons-active" id="addons-active"><?php echo esc_html__( 'Active', 'wpcasa' ) ?></a>
    <a href="#addons-inactive" id="addons-inactive"><?php echo esc_html__( 'Inactive', 'wpcasa' ) ?></a>
</div>

<div class="wpsight-admin-ui-grid-col wpsight-admin-addons wpsight-admin-ui-grid-1-1">

    <table class="wpsight-admin-ui-table addons-info-desktop wpsight-admin-ui-table-striped">

        <thead>
            <tr>
                <th><?php echo esc_html__( 'Addon', 'wpcasa' ) ?></th>
                <th><?php echo esc_html__( 'Version', 'wpcasa' ) ?></th>
                <th><?php echo esc_html__( 'Author', 'wpcasa' ) ?></th>
                <th><?php echo esc_html__( 'Status', 'wpcasa' ) ?></th>
                <?php /*?><th><?php _e( 'Action', 'wpcasa' ) ?></th><?php */?>
            </tr>
        </thead>

        <tbody>

            <?php foreach( get_plugins() as $plugin => $p ) { ?>

                <?php if ( strpos( $p['Name'], 'WPCasa' ) !== false ) { ?>
                    <tr class="<?php if( is_plugin_active( $plugin ) ) { echo esc_attr( 'addon-active' ); } else { echo esc_attr( 'addon-inactive' ); } ?>">
                        <td><?php echo esc_html( $p['Name'] ) ?></td>
                        <td><?php echo esc_html( $p['Version'] ) ?></td>
                        <td><?php echo esc_html( $p['Author'] ) ?></td>
                        <td class="<?php if( is_plugin_active( $plugin ) ) { echo esc_attr( 'status-active' ); } else { echo esc_attr( 'status-inactive' ); } ?>">
                          <?php if( is_plugin_active( $plugin ) ) { echo esc_html__( 'Active', 'wpcasa' ); } else { echo esc_html__( 'Inactive', 'wpcasa' ); } ?>
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
                <li class="addons-list <?php if( is_plugin_active( $plugin ) ) { echo esc_attr__( 'addon-active', 'wpcasa' ); } else { echo esc_attr__( 'addon-inactive', 'wpcasa' ); } ?>">
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
                                    <span class="text"><?php echo esc_html__( 'Addon', 'wpcasa' ) ?></span>
                                    <span class="text"><?php echo esc_html( $p['Name'] ) ?></span>
                                </li>

                                <li class="content-item">
                                    <span class="text"><?php echo esc_html__( 'Version', 'wpcasa' ) ?></span>
                                    <span class="text"><?php echo esc_html( $p['Version'] ) ?></span>
                                </li>

                                <li class="content-item">
                                    <span class="text"><?php echo esc_html__( 'Author', 'wpcasa' ) ?></span>
                                    <span class="text"><?php echo esc_html( $p['Author'] ) ?></span>
                                </li>

                                <li class="content-item">
                                    <span class="text"><?php echo esc_html__( 'Status', 'wpcasa' ) ?></span>
                                    <span class="text <?php if( is_plugin_active( $plugin ) ) { echo esc_attr( 'status-active' ); } else { echo esc_attr( 'status-inactive' ); } ?>">
                                      <?php if( is_plugin_active( $plugin ) ) { echo esc_html__( 'Active', 'wpcasa' ); } else { echo esc_html__( 'Inactive', 'wpcasa' ); } ?>
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