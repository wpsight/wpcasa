<h3><?php _e( 'Account', 'wpcasa' ) ?></h3>
<?php if( wpsight_is_premium() ) { ?>
    <p><?php _e( 'Visit your account to see your purchases, access your downloads or submit a ticket.', 'wpcasa' ) ?></p>
    <a href="https://wpcasa.com/account/" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-cloud"></span><?php _e( 'View Account', 'wpcasa' ) ?></a>
<?php } else { ?>
    <p><?php _e( 'Please activate at least one of your premium products in order to connect your website for support and updates', 'wpcasa' ) ?></p>
    <a href="<?php echo admin_url( '/admin.php?page=wpsight-licenses' ) ?>" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-cloud"></span><?php _e( 'Activate', 'wpcasa' ) ?></a>
<?php } ?>