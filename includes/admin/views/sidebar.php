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
        foreach ( $settings as $key => $section )
            echo '<a href="#settings-' . sanitize_title( $key ) . '" id="settings-' . sanitize_title( $key ) . '-tab" class="nav-tab">' . $section[0] . '</a>';
        ?>
        <a href="#settings-tools" id="settings-tools-tab" class="nav-tab"><span class="dashicons dashicons-admin-tools"></span><?php _e( 'Tools', 'wpcasa' ); ?></a>
    </div>
</div>