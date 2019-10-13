<h3><?php _e( 'Account', 'wpcasa' ) ?></h3>
<?php if( wpsight_is_premium() ) { ?>
    <p><?php _e( 'Your account provides access to all your purchased products and their license keys.', 'wpcasa' ) ?></p>
    <a href="https://wpcasa.com/account/" target="_blank" class="button button-secondary button-text-icon"><span class="dashicons dashicons-cloud"></span><?php _e( 'View Account', 'wpcasa' ) ?></a>
<?php } else { ?>
    <p><?php _e( 'Please activate at least one of your premium products in order to get access to your account.', 'wpcasa' ) ?></p>
    <a href="https://wpcasa.com/account/" target="_blank" class="button button-secondary button-text-icon"><span class="dashicons dashicons-cloud"></span><?php _e( 'Connect', 'wpcasa' ) ?></a>
<?php } ?>