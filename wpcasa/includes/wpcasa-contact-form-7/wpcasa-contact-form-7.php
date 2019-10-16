<?php
/*
Plugin Name: WPCasa Contact Form 7
Plugin URI: https://wpcasa.com/downloads/wpcasa-contact-form-7
Description: Add support for Contact Form 7 to attach property details to the contact email sent from WPCasa listing pages.
Version: 1.1.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.8
Text Domain: wpcasa-contact-form-7
Domain Path: /languages

	Copyright: 2015 WPSight
	License: GNU General Public License v2.0 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Contact_Form_7 class
 */
class WPSight_Contact_Form_7 {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );

		define( 'WPSIGHT_CONTACT_FORM_7_NAME', 'WPCasa Contact Form 7' );
		define( 'WPSIGHT_CONTACT_FORM_7_DOMAIN', 'wpcasa-contact-form-7' );
		define( 'WPSIGHT_CONTACT_FORM_7_VERSION', '1.1.0' );
		define( 'WPSIGHT_CONTACT_FORM_7_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_CONTACT_FORM_7_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		if ( is_admin() ){
			include( WPSIGHT_CONTACT_FORM_7_PLUGIN_DIR . '/includes/admin/class-wpsight-contact-form-7-admin.php' );
			$this->admin = new WPSight_Contact_Form_7_Admin();
		}

		// Actions

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		
		add_action( 'template_redirect', array( $this, 'listing_form_display' ) );
		
		// Add CF7 Shortcodes
		
		if( function_exists( 'wpcf7_add_form_tag' ) ) {
		
			wpcf7_add_form_tag( 'listing_agent', array( $this, 'listing_agent_tag' ), true );
			wpcf7_add_form_tag( 'listing_id', array( $this, 'listing_id_tag' ), true );
			wpcf7_add_form_tag( 'listing_url', array( $this, 'listing_url_tag' ), true );
			wpcf7_add_form_tag( 'listing_title', array( $this, 'listing_title_tag' ), true );
		
		}

	}

	/**
	 *	init()
	 *
	 *  Initialize the plugin when WPCasa is loaded
	 *
	 *  @param  object  $wpsight
	 *	@uses	do_action_ref_array()
	 *  @return object
	 *
	 *	@since	1.0.0
	 */
	public static function init( $wpsight ) {
		if ( ! isset( $wpsight->contact_form_7 ) ) {
			$wpsight->contact_form_7 = new self();
		}
		do_action_ref_array( 'wpsight_init_contact_form_7', array( &$wpsight ) );

		return $wpsight->contact_form_7;
	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since	1.0.0
	 */

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-contact-form-7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue scripts and css.
	 *	
	 *	@uses	wp_enqueue_style()
	 *	@uses	wpsight_get_option()
	 *	
	 *	@since	1.0.0
	 */
	public function frontend_scripts() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		
		if( is_singular( wpsight_post_type() ) && wpsight_get_option( 'contact_form_7_listing_form_css' ) )
			wp_enqueue_style( 'wpcasa-contact-form-7', WPSIGHT_CONTACT_FORM_7_PLUGIN_URL . '/assets/css/wpsight-contact-form-7' . $suffix . '.css' );

	}
	
	/**
	 *	listing_form_display()
	 *
	 *  Gets display option and current page.
	 *	Then fires corresponding action hook
	 *	to display the form on the listing page.
	 *
	 *	@uses	wpsight_post_type()
	 *	@uses	wpsight_get_option()
	 *	@uses	add_action()
	 *
	 *	@since 1.0.0
	 */
	public function listing_form_display() {
		
		if( is_singular( wpsight_post_type() ) && wpsight_get_option( 'contact_form_7_listing_form_display' ) )

			add_action( wpsight_get_option( 'contact_form_7_listing_form_display' ), array( $this, 'listing_form' ) );
		
	}
	
	/**
	 *	listing_form()
	 *
	 *  Displays a form when there is one
	 *	selected on the settings page.
	 *
	 *	@uses	wpsight_get_option()
	 *	@uses	wpcf7_contact_form()
	 *
	 *	@since 1.0.0
	 */
	public function listing_form() {
		
		if( wpsight_get_option( 'contact_form_7_listing_form_id' ) ) {
			
			$contact_form = wpcf7_contact_form( wpsight_get_option( 'contact_form_7_listing_form_id' ) );
			
			if( is_object( $contact_form ) )
				echo $contact_form->form_html( array( 'html_class' => 'wpsight-wpcf7' ) );
			
		}
		
	}
	
	/**
	 *	listing_agent_tag()
	 *
	 *  Add CF7 shortcode to display the email
	 *	of a listing agent in a hidden field
	 *	protected by antispambot().
	 *
	 *	@uses	get_the_author_email()
	 *	@uses	antispambot()
	 *
	 *	@since	1.0.0
	 */
	function listing_agent_tag( $tag ) {

		if ( ! is_object( $tag ) || empty( $tag->name ) )
			return;
	
		return '<input type="hidden" name="' . esc_attr( $tag->name ) . '" value="' . esc_attr( antispambot( get_the_author_meta( 'email' ) ) ) . '" />';
	
	}
	
	/**
	 *	listing_id_tag()
	 *
	 *  Add CF7 shortcode to display the
	 *	listing ID in a hidden field.
	 *
	 *	@uses	wpsight_get_listing_id()
	 *
	 *	@since	1.0.0
	 */
	function listing_id_tag( $tag ) {

		if ( ! is_object( $tag ) || empty( $tag->name ) )
			return;
	
		return '<input type="hidden" name="' . esc_attr( $tag->name ) . '" value="' . esc_attr( wpsight_get_listing_id() ) . '" />';
	
	}
	
	/**
	 *	listing_url_tag()
	 *
	 *  Add CF7 shortcode to display the
	 *	listing URL in a hidden field.
	 *
	 *	@uses	get_permalink()
	 *
	 *	@since	1.0.0
	 */
	function listing_url_tag( $tag ) {

		if ( ! is_object( $tag ) || empty( $tag->name ) )
			return;
	
		return '<input type="hidden" name="' . esc_attr( $tag->name ) . '" value="' . esc_attr( esc_url( get_permalink() ) ) . '" />';
	
	}
	
	/**
	 *	listing_title_tag()
	 *
	 *  Add CF7 shortcode to display the
	 *	listing title in a hidden field.
	 *
	 *	@uses	get_the_title()
	 *
	 *	@since	1.0.0
	 */
	function listing_title_tag( $tag ) {

		if ( ! is_object( $tag ) || empty( $tag->name ) )
			return;
	
		return '<input type="hidden" name="' . esc_attr( $tag->name ) . '" value="' . esc_attr( get_the_title() ) . '" />';
	
	}
	
	/**
	 *	default_form()
	 *
	 *  Create the default listing contact
	 *	form that is created when this
	 *	add-on is activated.
	 *
	 *	@return	string
	 *
	 *	@since	1.0.0
	 */
	public static function default_form() {

		$template =
			'<p>' . __( 'Your Name', 'wpcasa-contact-form-7' )
			. ' ' . __( '(required)', 'wpcasa-contact-form-7' ) . '<br />' . "\n"
			. '    [text* your-name] </p>' . "\n\n"
			. '<p>' . __( 'Your Email', 'wpcasa-contact-form-7' )
			. ' ' . __( '(required)', 'wpcasa-contact-form-7' ) . '<br />' . "\n"
			. '    [email* your-email] </p>' . "\n\n"
			. '<p>' . __( 'Your Message', 'wpcasa-contact-form-7' ) . '<br />' . "\n"
			. '    [textarea your-message] </p>' . "\n\n"
			. '<p>[submit "' . __( 'Submit Request', 'wpcasa-contact-form-7' ) . '"]</p>' . "\n\n"
			. '<div class="hidden">[listing_agent listing_agent][listing_id listing_id][listing_url listing_url][listing_title listing_title]</div>';

		return $template;

	}
	
	/**
	 *	default_mail()
	 *
	 *  Create the default listing contact
	 *	mail that is created when this
	 *	add-on is activated.
	 *
	 *	@return	@array
	 *
	 *	@since	1.0.0
	 */
	public static function default_mail() {

		$template = array(
			'subject'				=> __( 'Request', 'wpcasa-contact-form-7' ) . ': [title]',
			'sender'				=> '[your-name] <[your-email]>',
			'body'					=> sprintf( '<strong>[your-name]</strong> %s:' . "\n\n" . ' [your-message]' . "\n\n" . '<strong>%s</strong>:' . "\n" . '[listing_id]: <a href="[listing_url]">[listing_title]</a>', __( 'sent you a message', 'wpcasa-contact-form-7' ), __( 'Listing', 'wpcasa-contact-form-7' ) ),
			'recipient' 			=> '[listing_agent]',
			'additional_headers'	=> 'Reply-To: [your-email]',
			'attachments'			=> '',
			'use_html'				=> 1,
			'exclude_blank'			=> 0
		);

		return $template;

	}

	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook
	 *	to create some default options to be
	 *	used by this plugin.
	 *	
	 *	@uses	self::default_form()
	 *	@uses	self::default_mail()
	 *	@uses	PCF7_ContactForm::get_template()
	 *	@uses	WPCF7::get_option()
	 *	@uses	WPCF7::update_option()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_add_option()
	 *	
	 *	@since	1.0.0
	 */
	public static function activation() {
		
		$contact_form_id = false;
		
		$default_form = self::default_form();
		$default_mail = self::default_mail();
		
		if( class_exists( 'WPCF7_ContactForm' ) ) {
		
			// Create listing contact form
			
			$contact_form = WPCF7_ContactForm::get_template( array( 'title'	=> __( 'Listing Contact', 'wpcasa-contact-form-7' ) ) );
			$contact_form->set_properties( array( 'form' => $default_form, 'mail' => $default_mail ) );
			$contact_form_id = ! wpsight_get_option( 'contact_form_7_listing_form_id' ) ? $contact_form->save() : false;
			
			if( $contact_form_id ) {
				
				$validate = WPCF7::get_option( 'bulk_validate' );
				
				if( isset( $validate['count_valid'] ) ) {
					$validate['count_valid'] = absint( $validate['count_valid'] ) + 1;
					WPCF7::update_option( 'bulk_validate', $validate );
				}
			
			}
		
		}

		// Add some default options

		$options = array(
			'contact_form_7_listing_form_id'		=> $contact_form_id,
			'contact_form_7_listing_form_css'		=> '1',
			'contact_form_7_listing_form_display'	=> 'wpsight_listing_single_after'
		);

		foreach( $options as $option => $value ) {

			if( wpsight_get_option( $option ) )
				continue;

			wpsight_add_option( $option, $value );

		}

	}
	
}

/**
 *	Check if Contact Form 7 plugin is active
 *	and activate our add-on if yes.
 *
 *	@since	1.0.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
	// Run activation hook
	register_activation_hook( __FILE__, array( 'WPSight_Contact_Form_7', 'activation' ) );
		
	// Initialize plugin on wpsight_init
	add_action( 'wpsight_init', array( 'WPSight_Contact_Form_7', 'init' ) );

}
