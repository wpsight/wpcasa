<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcode_Listing_Teaser {

	/**
	 * Constructor
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_listing_teaser', array( $this, 'shortcode_listing_teaser' ) );
	}
	
	/**
	 * shortcode_listing_teaser()
	 *
	 * Show a single listing teaser.
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_listing_teaser()
	 * @uses sanitize_html_class()
	 * @uses wp_kses_allowed_html()
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */
	public function shortcode_listing_teaser( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'id'	 	  => '',
            'before' 	  => '',
            'after'  	  => '',
            'wrap'	 	  => 'div',
            'class'		  => '', // additional css class
            'orientation' => 'horizontal' // can be vertical
        );
        
        // Merge shortcodes atts with defaults and extract
		extract( shortcode_atts( $defaults, $atts ) );
		
		ob_start();
		
		wpsight_listing_teaser( $id );
        
        $output = sprintf( '%1$s%3$s%2$s', $before, $after, ob_get_clean() );
        
        // Set css class
        $class = ! empty( $class ) ? ' ' . sanitize_html_class( $class ) : '';
        
        // Set orientation
        $orientation = in_array( $orientation, array( 'horizontal', 'vertical' ) ) ? ' ' . $orientation : '';
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-listing-teaser-sc%3$s%4$s">%1$s</%2$s>', $output, $wrap, $class, $orientation );
		
		return apply_filters( 'wpsight_shortcode_listing_teaser', $output, $atts );

	}

}

new WPSight_Shortcode_Listing_Teaser();
