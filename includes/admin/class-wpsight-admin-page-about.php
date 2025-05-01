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
	public function output() : void {

		/**
		 * About This Version administration panel.
		 *
		 * @package WPCasa
		 * @subpackage Administration
		 */
		
		list( $display_version ) = explode( '-', WPSIGHT_VERSION );
		
		?>
        
			<div class="wpcasa-about wrap full-width-layout">
                        
            	<div class="wrap-inner">
                
				<a href="https://wpcasa.com" target="_blank" class="wp-badge">
                    <?php
                    /* translators: %s: is the current version */
                    printf( esc_html__( 'Version %s', 'wpcasa' ), esc_html( $display_version ) ); ?></a>
                
                <section id="section-intro" class="section section-intro">
                    
                    <div class="section-wrap">
                    
                        <div class="intro-text">
                            <h1><?php
                                /* translators: %s: is the current version */
                                printf( esc_html__( 'Welcome to WPCasa&nbsp;%s', 'wpcasa' ), esc_html( $display_version ) ); ?></h1>
                            <p><?php
                                /* translators: %s: is the current version */
                                printf( esc_html__( 'Thank you for updating to the latest version! WPCasa %s will smooth your user experience and includes new features and improvements.', 'wpcasa' ), esc_html( $display_version ) ); ?></p>
                        </div>              

                        <div class="hero-image">
                            <img src="<?php echo esc_url( WPSIGHT_PLUGIN_URL . '/assets/img/wpcasa-update-1.png' ); ?>" />
                        </div>
                        
                    </div>
                    
                </section>

                <section id="section-changelog" class="section section-changelog">
                
                    <div class="section-wrap">

                        <div class="changelog">
                            
                            <h3><?php echo esc_html__( 'Changelog', 'wpcasa' ) ?></h3>
                            
                            <style>
                                
                            .tabs {
                                width: 100%;
                                float: left;
                                margin: 0;
                                padding: 0;
                            }

                            .tabs li {
                                float: left;
                                width: 20%;
                                margin: 0;
                            }

                            .tabs a {
                                display: block;
                                text-align: center;
                                text-decoration: none;
                                color: #999;
                                padding: 10px 0;
                                background: rgba(0,0,0,.1);
                            }

                            .tabs a:hover,
                            .tabs a.active {
                                background: #12AE8F;
                                color: #FFF;
                            }
                                
                            .tabs a:focus {box-shadow: none;}

                            .tabgroup {
                                width: 100%;
                                float: left;
                            }

                            .tabgroup div {
                                padding: 30px;
                            }
                                
                            .tabgroup div p {margin-bottom: 30px;}

                            </style>

                            <ul class="tabs" data-tabgroup="first-tab-group">
                                <li class="tab"><a href="#version-1-4-1" class="active">v1.4.1</a></li>
                                <li class="tab"><a href="#version-1-4-0">v1.4.0</a></li>
                                <li class="tab"><a href="#version-1-3-1">v1.3.1</a></li>
                                <li class="tab"><a href="#version-1-3-0" >v1.3.0</a></li>
                                <li><a href="https://wordpress.org/plugins/wpcasa/#developers" target="_blank"><?php echo esc_html__( 'More', 'wpcasa' ); ?></a></li>
                            </ul>

                            <section id="first-tab-group" class="tabgroup">
                                <div id="version-1-4-1">
                                    <p>Version: 1.4.1</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>The license page may show an error under certain circumstances</td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="version-1-4-0">
                                    <p>Version: 1.4.0</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added basic compatibility with block editor and REST API endpoints for listings and all associated taxonomies.</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added energy efficiency chart to page printout. Requires <a href="https://wpcasa.com/downloads/wpcasa-energy-efficiency" target="_blank">WPCasa Energy Efficiency</a></td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added color picker feature for use on the settings page as with <a href="https://wpcasa.com/downloads/wpcasa-featured-listing" target="_blank">WPCasa Featured Listing</a> and <a href="https://wpcasa.com/downloads/wpcasa-listing-labels" target="_blank">WPCasa Listing Labels</a></td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added notice if updater class for paid plugins is missing</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Twitter renamed to X</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Translation settings improved and missing strings for translation added</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Textdomain wpcasa was loaded too early</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Message if no image is assigned to an agent</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Vulnerability to Cross Site Scripting (XSS) (Thanks to Patchstack)</td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="version-1-3-1">
                                    <p>Version: 1.3.1</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Added string translation of listing details</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-update">Update</span></td>
                                            <td>CMB2 updated to 2.11.0</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed authenticated stored XSS</td>
                                        </tr>
                                    </table>
                                </div>

                                <div id="version-1-3-0">
                                    <p>Version: 1.3.0</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added input field for longitude and latitude to manually set the location of the listing</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Switch to loading language files from <a href="https://translate.wordpress.org/projects/wp-plugins/wpcasa/" target="_blank">GlotPress</a></td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved use of singular and plural string translation</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Minified JS files for increase page load</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved PHP 8 compatibility</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed some notice when debug mode is activated</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Added missing file needed from [WPCasa Dashboard](https://wpcasa.com/downloads/wpcasa-dashboard/) to show the map</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed insecure direct object references (IDOR) (Thanks to Patchstack)</td>
                                        </tr>
                                    </table>
                                </div>

                            </section>

                            <script type="text/javascript">
                            jQuery(document).ready(function($) {                      
                                $('.tabgroup > div').hide();
                                $('.tabgroup > div:first-of-type').show();
                                $('.tabs .tab a').click(function(e){
                                    e.preventDefault();
                                    var $this = $(this),
                                    tabgroup = '#'+$this.parents('.tabs').data('tabgroup'),
                                    others = $this.closest('.tab').siblings().children('a'),
                                    target = $this.attr('href');
                                    others.removeClass('active');
                                    $this.addClass('active');
                                    $(tabgroup).children('div').hide();
                                    $(target).show();
                                })
                            });
                            </script>

                        </div>

                        <?php /*?>
                                 <div id="version-1-2-13">
                                    <p>Version: 1.2.13</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>QR code on listing single page was not working anymore. Switched from Google to the free QR Code Generator <a href="https://goqr.me" target="_blank">goqr.me</a></td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="version-1-2-12">
                                    <p>Version: 1.2.12</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Compatible with PHP 8+</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Listing Map styling of listing in popup box</td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="version-1-2-11">
                                    <p>Version: 1.2.11</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td> Added upgrade notice on WordPress plugin page</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Updated plugin header with file-level PHPDoc DocBlock</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Ensure compatibility with WordPress 6.5</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Listing Map settings didn't save new value</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>In some cases, toggling the Listing Map link did not work</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Removed unwanted extra character after description on listing single page</td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="version-1-2-10-hot">
                                    <p><br>Version: 1.2.10.1</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Search form stopped working for some non-WPCasa themes</td>
                                        </tr>
                                    </table>
                                </div>

                                <div id="version-1-2-10">
                                    <p>Version: 1.2.10</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Improved title escaping for compatibility with <a href="https://wpcasa.com/downloads/wpcasa-listing-labels/" target="_blank">WPCasa Listing Labels</a></td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Load WPCasa CSS on all pages, not just on the single listing pages</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Minimization of all CSS to improve the loading speed of the page</td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="version-1-2-9-hot">
                                    <p><br>Version: 1.2.9.2</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Improved html escaping on listing page for compatibility with <a href="https://wpcasa.com/downloads/wpcasa-listing-labels/" target="_blank">WPCasa Listing Labels</a></td>
                                        </tr>
                                    </table>
                                    <p><br>Version: 1.2.9.1</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Additional classes for listings were no longer taken into account</td>
                                        </tr>
                                    </table>
                                </div>

                                 <div id="version-1-2-9">
                                    <p>Version: 1.2.9</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Optimized code base</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Fixed guideline violation</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Bumped required WordPress version to 6.2</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved speed of database query to get listings</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Fixed listing filter with <code>wpsight_exclude_unavailable</code> stopped working</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved WPCasa About page</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Removed server info panel in favor of Site Health tools</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Issue of not assigning default value after activation in checkbox</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Apply and separate listing map default</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-update">Update</span></td>
                                            <td>Updated CMB2 to 2.10.1</td>
                                        </tr>
                                    </table>
                                </div>

                                <div id="version-1-2-8">
                                    <p>Version: 1.2.8</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Optimized code base</td>
                                        </tr>
                                    </table>
                                </div>
                                 <div id="version-1-2-7">
                                    <p>Version: 1.2.7</p>
                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Sanitized input fields and data</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Modifications to the Newsletter Panel in WPCasa Settings</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-update">Update</span></td>
                                            <td>Updated CMB2 to 2.9.0</td>
                                        </tr>
                                    </table>
                                </div>


 <div class="changelog">

                            <h3><?php _e( 'Changelog', 'wpcasa' ) ?></h3>

                            <!-- Accordion > Start -->
                            <a href="#" class="selected" wpsight-admin-ui--accordion-toggle>Version 1.2.8 - <small>2021/10/28</small></a>
                            <div class="open" wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Optimized code base</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.7 - <small>2021/10/28</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Sanitized input fields and data</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Modifications to the Newsletter Panel in WPCasa Settings</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-update">Update</span></td>
                                            <td>Updated CMB2 to 2.9.0</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.6 - <small>2021/07/20</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added German Formal Translation</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added filter wpsight_listing_text_not_available to customize the informal text when a listing is not available</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved embedment of previously integrated functionality from WPCasa Listings Map and WPCasa Admin Map UI</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Data for Javascript gets now provided through wp_add_inline_script instead of wp_localize_script</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved data escaping</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-update">Update</span></td>
                                            <td>Updated swiper.js to 6.7.5</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.5 - <small>2021/02/09</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Ensure compatibility with latest version of WordPress</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed admin license page issue</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.4 - <small>2020/05/26</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed licenses activation</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.3 - <small>2020/04/01</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Add ability to disable listing map displaying</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.2 - <small>2020/03/31</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed reinitialize map coordinates in some cases when dev mode is true</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.1 - <small>2020/03/23</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed map functionality after deleting WPCasa Listing Map plugin</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                            <a href="#" wpsight-admin-ui--accordion-toggle>Version 1.2.0 - <small>2020/03/20</small></a>
                            <div wpsight-admin-ui--accordion-content>
                                <div class="inner">

                                    <table>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Complete revamp of the WPCasa Admin UI</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Integration of WPCasa Admin Map UI</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Integration of WPCasa Listings Map</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Ability to restore settings to default</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Ability to delete all data</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-new">New</span></td>
                                            <td>Added recommends items</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved license algorithm</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Updated file/folder structure</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Improved map searching algorithm</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Multiple plugin activation</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-tweak">Tweak</span></td>
                                            <td>Tested up to WordPress 5.3.2</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-fix">Fix</span></td>
                                            <td>Fixed bulk listing edit</td>
                                        </tr>
                                        <tr>
                                            <td><span class="changelog-entry-update">Update</span></td>
                                            <td>Updated CMB2 to 2.6.0</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Accordion > End -->

                        </div><?php */?>
                        
                    </div>
                    
                </section>
                    
			</div>
		<?php
		
	}
}

endif;

return new WPSight_About();
