<div id="settings-overview" class="settings_panel">

    <div class="wpsight-admin-ui-container">

        <div class="wpsight-admin-ui-grid settings_panel_boxes wpsight-admin-ui-grid-same-height">

            <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-grid-col-same-height">
                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-account">
                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-account.php'; ?>
                </div>
            </div>

            <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-grid-same-height">
                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-documentation">
                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-documentation.php'; ?>
                </div>
            </div>

            <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-grid-same-height">
                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-support">
                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-support.php'; ?>
                </div>
            </div>

        </div>

        <div class="wpsight-admin-ui-grid">

            <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-2-3 wpsight-admin-ui-panel-wrap-theme">

                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-theme">

                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-theme.php'; ?>

                </div>

                <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-addons">

                    <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-addons.php'; ?>

                </div>

            </div>

            <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-3 wpsight-admin-ui-panel-wrap-theme-bar">

                <div class="wpsight-admin-ui-panel-wrap-theme-bar-item wpsight-admin-ui-panel-wrap-theme-bar-images">
<!--                    --><?php //if( wpsight_is_premium() == false ) { ?>
                        <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-auto-height wpsight-admin-ui-panel-system wpsight-admin-ui-no-padding">
                            <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-promo-products.php'; ?>
                        </div>
<!--                    --><?php //} ?>

                    <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-auto-height wpsight-admin-ui-panel-system wpsight-admin-ui-no-padding">
                        <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-promo-services.php'; ?>
                    </div>
                </div>

                <div class="wpsight-admin-ui-panel-wrap-theme-bar-item wpsight-admin-ui-panel-wrap-theme-bar-content">
                    <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-hero wpsight-admin-ui-panel-system">
                        <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-server-info.php'; ?>
                    </div>

                    <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-newsletter">
                        <?php include WPSIGHT_PLUGIN_DIR . '/includes/admin/views/panel-newsletter.php'; ?>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>