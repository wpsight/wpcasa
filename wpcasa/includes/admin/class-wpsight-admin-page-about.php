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
		 * @package WordPress
		 * @subpackage Administration
		 */
		
		wp_enqueue_script( 'underscore' );
		
		list( $display_version ) = explode( '-', WPSIGHT_VERSION );
		
		?>
        
            <style>
            body {background: #3b4045;}
            
            .about-wrap.full-width-layout {max-width: 100%; padding: 0; margin: 0 auto; position: relative; overflow: hidden;}
            .about-wrap.full-width-layout > .wrap-inner {width: 100%; position: relative; z-index: 1;}
    
            .about-wrap {text-align: center; color: #EEE;}
            .about-wrap h1 {text-align: center; margin: 1rem 0;}
            .about-wrap h1, .about-wrap h2, .about-wrap h3, .about-wrap h4, .about-wrap h5, .about-wrap h6 {color: #FFF !important;}
            .about-wrap p {text-align: center; margin: 1rem 0;}
            .about-wrap h2 {
                margin: 0 0 .6em;
                font-size: 2.7em;
                line-height: 1.3;
                font-weight: 300;
                text-align: center;
            }

            #wpcontent {padding-left: 0;}
            #wpfooter {display: none;}
            #wpbody-content {
                padding-bottom: 0;
            }
            
            .about-wrap .wp-badge {
                position: fixed;
                top: 0;
                right: 0;
                text-decoration: none;
                background-position: center !important;
                padding-top: 120px;
                background: #12AE8F url(<?php echo plugins_url( 'wpcasa' ) ?>/assets/img/icon.png) no-repeat;
                background-size: contain;
                box-shadow: 0 0 50px rgba(0,0,0,.5);
                transition: all .2s;
            }
                  
            .about-wrap .wp-badge:hover {
                color: #FFF;
                transform: scale(1.2);
            }
                  
            .bg-layer {
                content: '';
                background: #12AE8F;
                height: 80vh;
                position: absolute;
                left: 0;
                right: 0;
                width: 100%;
                z-index: 0;
                box-shadow: 0 0 200px rgba(0,0,0,.5);
            }
            
            .bg-layer-1 {
                top: 65vh;
                transform: skew(0, 3deg);
            }
            
            .bg-layer-2 {
                top: 215vh;
                transform: skew(0, -3deg);
            }
            
            .bg-layer-3 {
                top: 365vh;
                transform: skew(0, 3deg);
            }
            
            .bg-layer-4 {
                top: 515vh;
                transform: skew(0, -3deg);
            }
            
            .bg-layer-5 {
                top: 65vh;
                transform: skew(0, 6deg);
                background: rgba(18, 174, 143,.5);
                box-shadow: none;
            }
            
            .bg-layer-6 {
                top: 215vh;
                transform: skew(0, -6deg);
                background: rgba(18, 174, 143,.5);
                box-shadow: none;
            }
            
            .bg-layer-7 {
                top: 365vh;
                transform: skew(0, 6deg);
                background: rgba(18, 174, 143,.5);
                box-shadow: none;
            }
            
            .bg-layer-8 {
                top: 515vh;
                transform: skew(0, -6deg);
                background: rgba(18, 174, 143,.5);
                box-shadow: none;
            }
            
            .intro-text {margin: 5rem 0 0;}
            .hero-image img {max-width: 1000px; margin: 5rem 0;}
            
            .changelog {
                max-width: 800px;
                margin: auto;
            }
            
            .changelog table {width: 100%; text-align: left; border-collapse: collapse;}
            .changelog table tr {
                border-bottom: 1px solid rgba(255,255,255,.1);
                transition: all .2s;
            }
            .changelog table tr:hover {
                background: rgba(255,255,255,.1);
            }
            .changelog table tr td {
                padding: .5rem 1rem;
            }
            .changelog table tr td:first-child {
                text-align: right;
            }
            
             .changelog-entry-improved,
             .changelog-entry-added,
             .changelog-entry-fixed,
             .changelog-entry-updated {
                 width: 80px;
                 display: inline-block;
                 color: #FFF;
                 text-align: center; 
                 font-size: .8rem;
                 padding: 3px;
                 border-radius: 3px;
                 text-transform: uppercase;
                 background: #333;  
             }
            
             .changelog-entry-improved {color: rgb(0,204,102);}
             .changelog-entry-added {color: rgb(255,204,102);}
             .changelog-entry-fixed {color: rgb(255,51,51);}
             .changelog-entry-updated {color: rgb(102,204,255);}
            
                       
            
            </style>
        
			<div class="wrap about-wrap full-width-layout">
            
            	<div class="bg-layer bg-layer-1"></div>
            	<div class="bg-layer bg-layer-2"></div>
            	<div class="bg-layer bg-layer-3"></div>
            	<div class="bg-layer bg-layer-4"></div>
            	<div class="bg-layer bg-layer-5"></div>
            	<div class="bg-layer bg-layer-6"></div>
            	<div class="bg-layer bg-layer-7"></div>
            	<div class="bg-layer bg-layer-8"></div>
            
            	<div class="wrap-inner">
                
				<a href="https://wpcasa.com" target="_blank" class="wp-badge"><?php printf( __( 'Version %s' ), $display_version ); ?></a>
                
                <div class="intro-text">
                    <h1><?php printf( __( 'Welcome to WPCasa&nbsp;%s' ), $display_version ); ?></h1>
                    <p><?php printf( __( 'Thank you for updating to the latest version! WPCasa %s will smooth your user experience and includes many new features and improvements.' ), $display_version ); ?></p>  
                </div>              
                
                <div class="hero-image">
                	<img src="<?php echo plugins_url( 'wpcasa' ) ?>/assets/img/wpcasa-update-1.png" />
                </div>
		
				<?php /*?>
				<h2 class="nav-tab-wrapper wp-clearfix">
					<a href="about.php" class="nav-tab nav-tab-active"><?php _e( 'What&#8217;s New' ); ?></a>
					<a href="credits.php" class="nav-tab"><?php _e( 'Credits' ); ?></a>
					<a href="freedoms.php" class="nav-tab"><?php _e( 'Freedoms' ); ?></a>
					<a href="freedoms.php?privacy-notice" class="nav-tab"><?php _e( 'Privacy' ); ?></a>
				</h2><?php */?>
		
				<?php /*?><div class="changelog point-releases">
					<h3><?php _e( 'Maintenance and Security Releases' ); ?></h3>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.',
								17
							),
							'4.9.7',
							number_format_i18n( 17 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.7' );
						?>
					</p>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed %2$s bug.',
								'<strong>Version %1$s</strong> addressed %2$s bugs.',
								18
							),
							'4.9.6',
							number_format_i18n( 18 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.6' );
						?>
					</p>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.',
								28
							),
							'4.9.5',
							number_format_i18n( 28 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.5' );
						?>
					</p>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed %2$s bug.',
								'<strong>Version %1$s</strong> addressed %2$s bugs.',
								1
							),
							'4.9.4',
							number_format_i18n( 1 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.4' );
						?>
					</p>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed %2$s bug.',
								'<strong>Version %1$s</strong> addressed %2$s bugs.',
								34
							),
							'4.9.3',
							number_format_i18n( 34 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.3' );
						?>
					</p>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.',
								22
							),
							'4.9.2',
							number_format_i18n( 22 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.2' );
						?>
					</p>
					<p>
						<?php
						printf(
							_n(
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
								'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.',
								11
							),
							'4.9.1',
							number_format_i18n( 11 )
						);
						?>
						<?php
						printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.9.1' );
						?>
					</p>
				</div><?php */?>
				<?php /*?><div class="inline-svg full-width">
					<picture>
						<source media="(max-width: 500px)" srcset="<?php echo 'https://s.w.org/images/core/4.9/banner-mobile.svg'; ?>">
						<img src="https://s.w.org/images/core/4.9/banner.svg" alt="">
					</picture>
				</div><?php */?>
                
				<?php /*?><?php */?>
                
				<div class="feature-section one-col">
					<div class="col">
						<h2>
							<?php _e( 'New Admin UI, More Settings, Improved Currencies and much more!' ) ?>
						</h2>
						<p><?php _e( 'Welcome to a whole new user experience! Welcome to the next stage of WPCasa. It has been never so easy to use and setup while providing a solid foundation for future development. Easy-to-scan settings, useful help texts, direct links to documentation, modern and easy-to-understand controls. It just feels right!' ); ?></p>
					</div>
				</div>
		
		
				<div class="floating-header-section">
					<div class="section-header">
						<h2><?php _e( 'New Admin UI' ); ?></h2>
					</div>
		
					<div class="section-content">
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/draft-and-schedule.svg" alt="">
							</div>
							<h3><?php _e( 'Easy to use' ); ?></h3>
							<p><?php _e( 'It never has been so easy to control your WPCasa-powered website. All the options have been improved and new controls makes it even easier to setup your site. It also offers a solid foundation for future developments so stay tuned.' ); ?></p>
						</div>
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/design-preview-links.svg" alt="">
							</div>
							<h3><?php _e( 'Additional Options' ); ?></h3>
							<p><?php _e( 'In the same breath we also added a few new options to offer you even more customizability with just a few clicks. ' ); ?></p>
						</div>
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/locking.svg" alt="">
							</div>
							<h3><?php _e( 'Improved License Activation' ); ?></h3>
							<p><?php _e( 'License activation is now easier than before. No separate page which might get forgotten and which causes to miss out on important updates. You can now access all the licenses from the overview page and always see when a license expires with the option to directly renew without any hazzle' );?></p>
						</div>
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/prompt.svg" alt="">
							</div>
							<h3><?php _e( 'Useful Infos' ); ?></h3>
							<p><?php _e( 'What theme are you using? Does it officially support WPCasa? What are your server specs? What version of the addons are installed? Where do you find the documentation. We included a lot of additional info which should make it easier for you to understand how WPCasa works and if all your systems are working as expected.' ); ?></p>
						</div>
					</div>
				</div>
		
				<div class="floating-header-section">
					<div class="section-header">
						<h2><?php _e( 'GDPR Compatibility' ); ?></h2>
					</div>
		
					<div class="section-content">
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/syntax-highlighting.svg" alt="">
							</div>
							<h3><?php _e( 'GDPR? Yes, Please!' ); ?></h3>
							<p><?php _e( 'While it might be a controversial topic it still needs to be considered. And while there is actually nothing wrong with using Google Maps or similar services, since there is no clear official judgement it remains unclear if and how the GDPR will take effect. And according to our users we have got a few requests to have services such as Google Maps optionally disabled. So we did that!' ); ?></p>
						</div>
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/sandbox.svg" alt="">
							</div>
							<h3><?php _e( 'More GDPR? We are ready' ); ?></h3>
							<p><?php _e( 'And while we worked on that we also made WPCasa ready for possible future changes in terms of the GDPR. Soon various updates of our themes and addons will be released with full GDPR functionality' ); ?></p>
						</div>
					</div>
				</div>
		
				<div class="floating-header-section">
					<div class="section-header">
						<h2><?php _e( 'Hola! Welcome! Willkommen' ); ?></h2>
					</div>
		
					<div class="section-content">
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/gallery-widget.svg" alt="">
							</div>
							<h3><?php _e( 'More Translations' ); ?></h3>
							<p><?php _e( 'From Croatian to Farsi, Russia, Italian and Portoguese. This new version of WPCasa includes 9 new translatations and makes it even easier to use WPCasa in your language.' ); ?></p>
						</div>
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/media-button.svg" alt="">
							</div>
							<h3><?php _e( 'Press a Button, Add Media' ); ?></h3>
							<p><?php _e( 'Want to add media to your text widget? Embed images, video, and audio directly into the widget along with your text, with our simple but useful Add Media button. Woo!' ); ?></p>
						</div>
					</div>
				</div>
		
				<div class="floating-header-section">
					<div class="section-header">
						<h2><?php _e( 'PHP 7.2 Ready' ); ?></h2>
					</div>
		
					<div class="section-content">
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/theme-switching.svg" alt="">
							</div>
							<h3><?php _e( 'More Reliable Theme Switching' ); ?></h3>
							<p><?php _e( 'When you switch themes, widgets sometimes think they can just up and move location. Improvements in WordPress 4.9 offer more persistent menu and widget placement when you decide it&#8217;s time for a new theme. Additionally, you can preview installed themes or download, install, and preview new themes right. Nothing says handy like being able to preview before you deploy. ' ); ?></p>
						</div>
						<div class="section-item">
							<div class="inline-svg">
								<img src="https://s.w.org/images/core/4.9/menu-flow.svg" alt="">
							</div>
							<h3><?php _e( 'Better Menu Instructions = Less Confusion' ); ?></h3>
							<p><?php _e( 'Were you confused by the steps to create a new menu? Perhaps no longer! We&#8217;ve ironed out the UX for a smoother menu creation process. Newly updated copy will guide you.' ); ?></p>
						</div>
					</div>
				</div>
		
				<?php /*?><div class="inline-svg">
					<picture>
						<source media="(max-width: 500px)" srcset="<?php echo 'https://s.w.org/images/core/4.9/gutenberg-mobile.svg'; ?>">
						<img src="https://s.w.org/images/core/4.9/gutenberg.svg" alt="">
					</picture>
				</div>
		
				<div class="feature-section">
					<h2>
						<?php
							printf(
								__( 'Lend a Hand with Gutenberg %s' ),
								'&#x1F91D'
							);
						?>
					</h2>
					<p><?php printf(
						__( 'WordPress is working on a new way to create and control your content and we&#8217;d love to have your help. Interested in being an <a href="%s">early tester</a> or getting involved with the Gutenberg project? <a href="%s">Contribute on GitHub</a>.' ),
						__( 'https://wordpress.org/plugins/gutenberg/' ),
						'https://github.com/WordPress/gutenberg' ); ?></p>
				</div><?php */?>
		
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
		
				<?php /*?><div class="changelog">
					<h2><?php
						printf(
							__( 'Developer Happiness %s' ),
							'&#x1F60A'
						);
					?></h2>
		
					<div class="under-the-hood two-col">
						<div class="col">
							<h3><a href="https://make.wordpress.org/core/2017/11/01/improvements-to-the-customize-js-api-in-4-9/"><?php _e( 'Customizer JS API Improvements' ); ?></a></h3>
							<p><?php
								printf(
									__( 'We&#8217;ve made numerous improvements to the Customizer JS API in WordPress 4.9, eliminating many pain points and making it just as easy to work with as the PHP API. There are also new base control templates, a date/time control, and section/panel/global notifications to name a few. <a href="%s">Check out the full list.</a>' ),
									'https://make.wordpress.org/core/2017/11/01/improvements-to-the-customize-js-api-in-4-9/'
								);
							?></p>
						</div>
						<div class="col">
							<h3><a href="https://make.wordpress.org/core/2017/10/22/code-editing-improvements-in-wordpress-4-9/"><?php _e( 'CodeMirror available for use in your themes and plugins' ); ?></a></h3>
							<p><?php _e( 'We&#8217;ve introduced a new code editing library, CodeMirror, for use within core. Use it to improve any code writing or editing experiences within your plugins, like CSS or JavaScript include fields.' ); ?></p>
						</div>
						<div class="col">
							<h3><a href="https://make.wordpress.org/core/2017/10/30/mediaelement-upgrades-in-wordpress-4-9/"><?php _e( 'MediaElement.js upgraded to 4.2.6' ); ?></a></h3>
							<p><?php _e( 'WordPress 4.9 includes an upgraded version of MediaElement.js, which removes dependencies on jQuery, improves accessibility, modernizes the UI, and fixes many bugs.' ); ?></p>
						</div>
						<div class="col">
							<h3><a href="https://make.wordpress.org/core/2017/10/15/improvements-for-roles-and-capabilities-in-4-9/"><?php _e( 'Improvements to Roles and Capabilities' ); ?></a></h3>
							<p><?php _e( 'New capabilities have been introduced that allow granular management of plugins and translation files. In addition, the site switching process in multisite has been fine-tuned to update the available roles and capabilities in a more reliable and coherent way.' ); ?></p>
						</div>
					</div>
				</div>
				</div>
		
				<hr /><?php */?>
		
				<?php /*?><div class="return-to-dashboard">
					<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
						<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
							<?php is_multisite() ? _e( 'Return to Updates' ) : _e( 'Return to Dashboard &rarr; Updates' ); ?>
						</a> |
					<?php endif; ?>
					<a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? _e( 'Go to Dashboard &rarr; Home' ) : _e( 'Go to Dashboard' ); ?></a>
				</div><?php */?>
			</div>
		
			<script>
				(function( $ ) {
					$( function() {
						var $window = $( window );
						var $adminbar = $( '#wpadminbar' );
						var $sections = $( '.floating-header-section' );
						var offset = 0;
		
						// Account for Admin bar.
						if ( $adminbar.length ) {
							offset += $adminbar.height();
						}
		
						function setup() {
							$sections.each( function( i, section ) {
								var $section = $( section );
								// If the title is long, switch the layout
								var $title = $section.find( 'h2' );
								if ( $title.innerWidth() > 300 ) {
									$section.addClass( 'has-long-title' );
								}
							} );
						}
		
						var adjustScrollPosition = _.throttle( function adjustScrollPosition() {
							$sections.each( function( i, section ) {
								var $section = $( section );
								var $header = $section.find( 'h2' );
								var width = $header.innerWidth();
								var height = $header.innerHeight();
		
								if ( $section.hasClass( 'has-long-title' ) ) {
									return;
								}
		
								var sectionStart = $section.offset().top - offset;
								var sectionEnd = sectionStart + $section.innerHeight();
								var scrollPos = $window.scrollTop();
		
								// If we're scrolled into a section, stick the header
								if ( scrollPos >= sectionStart && scrollPos < sectionEnd - height ) {
									$header.css( {
										position: 'fixed',
										top: offset + 'px',
										bottom: 'auto',
										width: width + 'px'
									} );
								// If we're at the end of the section, stick the header to the bottom
								} else if ( scrollPos >= sectionEnd - height && scrollPos < sectionEnd ) {
									$header.css( {
										position: 'absolute',
										top: 'auto',
										bottom: 0,
										width: width + 'px'
									} );
								// Unstick the header
								} else {
									$header.css( {
										position: 'static',
										top: 'auto',
										bottom: 'auto',
										width: 'auto'
									} );
								}
							} );
						}, 100 );
		
						function enableFixedHeaders() {
							if ( $window.width() > 782 ) {
								setup();
								adjustScrollPosition();
								$window.on( 'scroll', adjustScrollPosition );
							} else {
								$window.off( 'scroll', adjustScrollPosition );
								$sections.find( '.section-header' )
									.css( {
										width: 'auto'
									} );
								$sections.find( 'h2' )
									.css( {
										position: 'static',
										top: 'auto',
										bottom: 'auto',
										width: 'auto'
									} );
							}
						}
						$( window ).resize( enableFixedHeaders );
						enableFixedHeaders();
					} );
				})( jQuery );
				
/*				jQuery(window).scroll(function($) { 
				
				   $('.bg-layer').css({
					  'top' : -($(this).scrollTop()/3)+"px"
				   }); 
				
				});				
*/				
				
				
				
			</script>
		
		<?php
		
		return;

	}
}

endif;

return new WPSight_About();
