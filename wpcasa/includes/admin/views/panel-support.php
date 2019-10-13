<?php
$plugins	= get_plugins();
$plugin		= 'wpcasa-assistance/wpcasa-assistance.php';
?>

<h3><?php _e( 'Assistance', 'wpcasa' ) ?></h3>

<?php if( is_plugin_active( $plugin ) ) { ?>

    <p><?php _e( 'If you cant find your question answered, feel free to submit a ticket to us.', 'wpcasa' ) ?></p>
    <a href="#wpca" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-sos"></span><?php _e( 'Submit Ticket', 'wpcasa' ) ?></a>

<?php } else { ?>

	<?php if( ! empty( $plugins[$plugin] ) ) { ?>
        <p><?php _e( 'WPCasa Assistance is already installed. Please activate it in order to use it!', 'wpcasa' ) ?></p>
        <a href="<?php $helpers = new WPSight_Admin_Helpers; echo $helpers->action_link( $plugin, 'activate' ); ?>" target="_self" class="button button-secondary button-text-icon"><span class="dashicons dashicons-upload"></span><?php _e( 'Activate', 'wpcasa' ) ?></a>
    <?php } else { ?>
        <p><?php _e( 'Please install and activate the WPCasa Assistance Plugin in order to receive support. ', 'wpcasa' ) ?></p>
        <a href="<?php echo admin_url(); ?>plugin-install.php?s=wpcasa+assistance&tab=search&type=term" target="_self" class="button button-secondary button-text-icon"><span class="dashicons dashicons-upload"></span><?php _e( 'Install', 'wpcasa' ) ?></a>
    <?php } ?>
	    
<?php } ?>



