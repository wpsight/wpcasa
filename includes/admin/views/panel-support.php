<h3><?php _e( 'Support', 'wpcasa' ) ?></h3>

<p><?php _e( 'In order to get help with an issue, please click the link below to submit a ticket', 'wpcasa' ) ?></p>
    <?php if( wpsight_is_premium() ) { ?>
        <a href="https://wpcasa.com/submit-ticket" target="_self" class="button button-secondary button-text-icon"><span class="dashicons dashicons-upload"></span><?php _e( 'Activate', 'wpcasa' ) ?></a>
    <?php } else { ?>
        <a href="https://wordpress.org/support/plugin/wpcasa/" target="_self" class="button button-secondary button-text-icon"><span class="dashicons dashicons-upload"></span><?php _e( 'Install', 'wpcasa' ) ?></a>
    <?php } ?>