<?php
if (isset($option)) {
    $option_name = isset( $option['name'] )	? stripslashes ( $option['name'] )	: '';
    $option_desc = isset( $option['desc'] ) ? stripslashes ( $option['desc'] )  : '';
    $option_icon = isset( $option['icon'] )	? $option['icon'] : '';
    $option_link = isset( $option['link'] )	? $option['link'] : "#";
?>

<th scope="row" colspan="2">
    <div class="wpsight-admin-ui-heading">
        <div class="wpsight-admin-ui-heading-title">
            <span class="wpsight-admin-ui-icon">
                <span class="<?php echo $option_icon ?>"></span>
            </span>
            <h3> <?php echo $option_name ?></h3>
            <small> <?php echo $option_desc  ?></small>
        </div>

       <div class="wpsight-admin-ui-heading-actions">
           <a href="<?php echo $option_link; ?>" class="button button-primary" target="_blank"> <?php echo __('View Documentation', 'wpcasa') ?></a>
            <?php submit_button(__('Save Changes', 'wpcasa'), 'primary', 'wpsight-settings-save', false); ?>
        </div>
    </div>
</th>


<?php } ?>