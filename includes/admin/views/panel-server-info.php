<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h3><?php esc_html_e( 'Server Information', 'wpcasa' ) ?></h3>

<div class="wpsight-admin-ui-content">
    <table class="wpsight-admin-ui-table">
    
        <tbody>
        
            <tr>
            
                <td><?php echo esc_html__( 'PHP Version', 'wpcasa' ); ?>:</td>
                <td><?php echo esc_html( phpversion() ); ?></td>
    
            </tr>
            
            <tr>
            
                <td><?php echo esc_html__( 'Memory Limit', 'wpcasa' ); ?>:</td>
                <td><?php echo esc_html( ini_get( 'memory_limit' ) ); ?></td>
    
            </tr>
            
            <?php if( function_exists( 'memory_get_usage' ) ) {
                
                $usage 		= memory_get_usage( true );
                $usageInMb 	= $usage/1024/1024;
                ?>
                <tr>
                
                    <td><?php echo esc_html__( 'Memory Usage', 'wpcasa' ); ?>:</td>
                    <td><?php echo esc_html( $usageInMb ); ?>M</td>
                
                </tr>
                <?php
            } ?>
            
        </tbody>
        
    </table>
</div>

<!--<a href="#" target="_blank" class="button button-primary button-text-icon"><span class="dashicons dashicons-cloud"></span>--><?php //_e( 'Detailed Report', 'wpcasa' ) ?><!--</a>-->
