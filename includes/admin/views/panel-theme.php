<h3><?php _e( 'Site Information', 'wpcasa' ) ?></h3>

<?php
$theme = wp_get_theme();

if ( $theme->exists() ) {

    $parent_theme = wp_get_theme( $theme->get( 'Template' ) );

?>

<div class="wpsight-admin-ui-panel-theme-content wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">

    <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-2">

        <?php

		$image		= 'wp-content/themes/' . $theme->stylesheet. '/screenshot.png';
        $image_url	= site_url() . '/' . $image;
        $image_path	= ABSPATH . $image;

        if( ! file_exists( $image_path ) )
            $image_url = plugins_url( 'wpcasa' ) . '/assets/img/placeholder-theme.jpg';

        echo '<img src="' . $image_url . '" width="80%" />';
        ?>

    </div>

    <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-2">

        <h4><?php echo esc_html( $theme->get( 'Name' ) ); ?></h4><?php if( $parent_theme->get( 'Name' ) != $theme->get( 'Name' ) ) {echo '<small>' . esc_html( $parent_theme->get( 'Name' ) ) . '</small>';} ?>
        <p><?php echo esc_html( $theme->get( 'Description' ) ); ?></p>
        <table class="wpsight-admin-ui-table">
            <tr>
                <td><?php _e( 'Author', 'wpcasa' ); ?>:</td>
                <td><?php echo esc_html( $theme->get( 'Author' ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Version', 'wpcasa' ); ?>:</td>
                <td><?php echo esc_html( $theme->get( 'Version' ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WPCasa Support', 'wpcasa' ); ?>:</td>
                <td>
                    <?php
                        if ( $theme->get( 'Author' ) == 'WPSight' ) {
                            echo '<span class="wpsight-admin-ui-tag wpsight-admin-ui-tag-success">' . __( 'Yes', 'wpcasa' ) . '</span>';
                        } else {
                            echo '<span class="wpsight-admin-ui-tag wpsight-admin-ui-tag-error">' . __( 'No', 'wpcasa' ) . '</span>';
                        }
                    ?>

<!--                    --><?php //if( current_theme_supports( 'wpcasa' ) ) {
//                        echo '<span class="wpsight-admin-ui-tag wpsight-admin-ui-tag-success">' . __( 'Yes', 'wpcasa' ) . '</span>';
//                    } else {
//                        echo '<span class="wpsight-admin-ui-tag wpsight-admin-ui-tag-error">' . __( 'No', 'wpcasa' ) . '</span>';
//                    } ?>
                </td>
            </tr>
        </table>
        <p>
        <a href="<?php echo esc_html( $theme->get( 'ThemeURI' ) ); ?>" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-admin-links"></span> <?php _e( 'View Info', 'wpcasa' ) ?></a>
        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary button-text-icon"><span class="dashicons dashicons-admin-appearance"></span> <?php _e( 'Customize', 'wpcasa' ) ?></a>
        </p>


    </div>

</div>

<?php } ?>
