<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wpsight-admin-sidebar-back"></div>
<div class="wpsight-admin-sidebar">

    <div class="wpsight-admin-intro-box">
        <div class="wpsight-admin-ui-image">
            <img src="<?php echo esc_url( WPSIGHT_PLUGIN_URL . '/assets/img/wpcasa-admin-logo.jpg' ); ?>" />
        </div>
    </div>

    <div class="wpsight-admin-nav nav-tab-wrapper">
        <a href="#settings-overview" id="settings-overview-tab" class="nav-tab"><span class="dashicons dashicons-laptop"></span><?php echo esc_html__( 'Overview', 'wpcasa' ); ?></a>
        <?php
        foreach ( $settings as $key => $section )
            echo '<a href="#settings-' . esc_attr( sanitize_title( $key ) ) . '" id="settings-' . esc_attr( sanitize_title( $key ) ) . '-tab" class="nav-tab">' . wp_kses( $section[0], array( 'span' => array( 'class' => array() ) ) ) . '</a>';
        ?>
        <a href="#settings-tools" id="settings-tools-tab" class="nav-tab"><span class="dashicons dashicons-admin-tools"></span><?php echo esc_html__( 'Tools', 'wpcasa' ); ?></a>
    </div>
</div>