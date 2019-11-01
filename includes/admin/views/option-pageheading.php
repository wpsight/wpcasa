<?php

foreach ( $this->settings as $key => $section ) {
    foreach ( $section[1] as $option ) {
        if( $option['type'] == 'pageheading' ) { ?>
           <th scope="row" colspan="2">
                <div class="wpsight-admin-ui-heading">
                    <div class="wpsight-admin-ui-heading-title">
                        <span class="wpsight-admin-ui-icon">
                            <span class="<?php echo $option['icon'] ?>"></span>
                        </span>
                        <h3> <?php echo $option['name'] ?></h3>
                        <small> <?php echo $option['desc']  ?></small>
                    </div>
                   <div class="wpsight-admin-ui-heading-actions">
                       <a href="' . $option['$option_link'] . '" class="button button-primary" target="_blank"> <?php echo __('View Documentation', 'wpcasa') ?></a>
                        <?php submit_button(__('Save Changes', 'wpcasa'), 'primary', 'wpsight-settings-save', false); ?>
                    </div>
                </div>
           </th>
<?php
         return;
        }
    }
}

?>

