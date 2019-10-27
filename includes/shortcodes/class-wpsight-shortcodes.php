<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcodes {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Include listings shortcode class
		include_once('class-wpsight-shortcode-listings.php');
		
		// Include listings search shortcode class
		include_once('class-wpsight-shortcode-listings-search.php');
		
		// Include listing shortcode class
		include_once('class-wpsight-shortcode-listing-single.php');
		
		// Include listing teaser shortcode class
		include_once('class-wpsight-shortcode-listing-teaser.php');
		
		// Include listing teasers shortcode class
		include_once('class-wpsight-shortcode-listing-teasers.php');
		
		// Enable shortcodes in text widget
		add_filter( 'widget_text', 'do_shortcode' );

	}

}

new WPSight_Shortcodes();
