<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'WPSight_About' ) ) :

/**
 * WPSight_About Class
 */
class WPSight_About {

	/**
	 * Handles output of the reports page in admin.
	 */
	public function output() {

		/**
		 * About This Version administration panel.
		 *
		 * @package WPCasa
		 * @subpackage Administration
		 */
		
		list( $display_version ) = explode( '-', WPSIGHT_VERSION );
		
		?>
        
			<div class="wrap about-wrap full-width-layout">
            
            	<?php /*?><div class="bg-layer bg-layer-1"></div>
            	<div class="bg-layer bg-layer-2"></div>
            	<div class="bg-layer bg-layer-3"></div>
            	<div class="bg-layer bg-layer-4"></div>
            	<div class="bg-layer bg-layer-5"></div>
            	<div class="bg-layer bg-layer-6"></div>
            	<div class="bg-layer bg-layer-7"></div>
            	<div class="bg-layer bg-layer-8"></div><?php */?>
            
            	<div class="wrap-inner">
                
				<a href="https://wpcasa.com" target="_blank" class="wp-badge"><?php printf( __( 'Version %s' ), $display_version ); ?></a>
                
                <div class="intro-text">
                    <h1><?php printf( __( 'Welcome to WPCasa&nbsp;%s' ), $display_version ); ?></h1>
                    <p><?php printf( __( 'Thank you for updating to the latest version! WPCasa %s will smooth your user experience and includes new features and improvements.' ), $display_version ); ?></p>  
                </div>              
                
                <div class="hero-image">
                	<img src="<?php echo plugins_url( 'wpcasa' ) ?>/assets/img/wpcasa-update-1.png" />
                </div>
		
				<hr />
                
                <div class="developer-changes">
                </div>
                
                <div class="contributors">
                </div>
                
                <div class="changelog">
                
					<h2><?php _e( 'Changelog', 'wpcasa' ) ?></h2>
                    
                	<table>
                    	<tr>
                        	<td><span class="changelog-entry-improved">Improved</span></td>
                        	<td>Complete revamp of the WPCasa Admin UI</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Improved</span></td>
                        	<td>Added XX new Currencies and Symbols</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Added</span></td>
                        	<td>Italian Translation</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Added</span></td>
                        	<td>Croatian Translation</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Added</span></td>
                        	<td>Dutch Translation</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Added</span></td>
                        	<td>Russian Translation</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Added</span></td>
                        	<td>Romanian Translation</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-added">Added</span></td>
                        	<td>Slovak Translation</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-fixed">Fixed</span></td>
                        	<td>Typo in rental periods</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-fixed">Fixed</span></td>
                        	<td>Default order for listing type and location in search form</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-fixed">Fixed</span></td>
                        	<td>Reference conditional tag to correspondending query</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-fixed">Fixed</span></td>
                        	<td>Double 'edit_listings' capability</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-fixed">Fixed</span></td>
                        	<td>Missing textdomain in WPSight_Admin->listings_custom_views</td>
                        </tr>
                    	<tr>
                        	<td><span class="changelog-entry-updated">Updated</span></td>
                        	<td>Portoguese Translation</td>
                        </tr>
                                                
                    </table>
                
                </div>
		
			</div>
				
		<?php
		
		return;

	}
}

endif;

return new WPSight_About();
