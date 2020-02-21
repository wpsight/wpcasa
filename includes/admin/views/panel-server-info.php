<h3><?php _e( 'Server Information', 'wpcasa' ) ?></h3>

<div class="wpsight-admin-ui-content">
    <table class="wpsight-admin-ui-table">
    
        <tbody>
        
            <tr>
            
                <td><?php _e( 'PHP Version', 'wpcasa' ) ?>:</td>
                <td><?php echo phpversion(); ?></td>
    
            </tr>
            
            <tr>
            
                <td><?php _e( 'Memory Limit', 'wpcasa' ) ?>:</td>
                <td><?php echo ini_get( 'memory_limit' ) ?></td>
    
            </tr>
            
            <?php if( function_exists( 'memory_get_usage' ) ) {
                
                $usage 		= memory_get_usage( true );
                $usageInMb 	= $usage/1024/1024;
                ?>
                <tr>
                
                    <td><?php _e( 'Memory Usage', 'wpcasa' ) ?>:</td>
                    <td><?php echo $usageInMb ?>M</td>
                
                </tr>
                <?php
            } ?>
            
        </tbody>
        
    </table>
</div>

<!--<a href="#" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-cloud"></span>--><?php //_e( 'Detailed Report', 'wpcasa' ) ?><!--</a>-->
