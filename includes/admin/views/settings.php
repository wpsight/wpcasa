<?php
    if ( isset( $_GET['settings-updated'] ) ) {
        echo '<div class="fade notice notice-success"><p>' . __( 'Settings saved.', 'wpcasa' ) . '</p></div>';
    }
?>

<div class="wrap wpsight-settings-wrap">
    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/sidebar.php'; ?>
    <div class="wpsight-admin-main">
        <div class="wpsight-admin-ui-panel wpsight-admin-main-wrap-btn-toggle">
            <button class="wpsight-admin-main-btn-toggle">
                <span class="wpsight-admin-main-btn-toggle-line"></span>
                <span class="wpsight-admin-main-btn-toggle-line"></span>
                <span class="wpsight-admin-main-btn-toggle-line"></span>
            </button>
        </div>
        <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panels.php'; ?>
        <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/settings-form.php'; ?>
        <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/page-tools.php'; ?>

    </div>
</div>