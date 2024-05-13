<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h3><?php echo esc_html__( 'Support', 'wpcasa' ) ?></h3>

<p><?php echo esc_html__( 'In order to get help with an issue, please click the link below to submit a ticket', 'wpcasa' ) ?></p>
    <?php if( wpsight_is_premium() ) { ?>
        <a href="https://wpcasa.com/submit-ticket" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-upload"></span><?php echo esc_html__( 'Submit ticket', 'wpcasa' ) ?></a>
    <?php } else { ?>
        <a href="https://wordpress.org/support/plugin/wpcasa/" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-upload"></span><?php echo esc_html__( 'Submit ticket', 'wpcasa' ) ?></a>
    <?php } ?>