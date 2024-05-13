<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h3><?php echo esc_html__( 'Account', 'wpcasa' ) ?></h3>
<?php if( wpsight_is_premium() ) { ?>
    <p><?php echo esc_html__( 'Visit your account to see your purchases, access your downloads or submit a ticket.', 'wpcasa' ) ?></p>
    <a href="https://wpcasa.com/account/" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-cloud"></span><?php echo esc_html__( 'View Account', 'wpcasa' ) ?></a>
<?php } else { ?>
    <p><?php echo esc_html__( 'Please activate at least one of your premium products in order to connect your website for support and updates', 'wpcasa' ) ?></p>
    <a href="<?php echo esc_url( admin_url( '/admin.php?page=wpsight-licenses' ) ); ?>" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-cloud"></span><?php echo esc_html__( 'Activate', 'wpcasa' ) ?></a>
<?php } ?>