<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcode_Listing_Single {

	/**
	 * Constructor
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_listing', array( $this, 'shortcode_listing_single' ) );
	}
	
	/**
	 * shortcode_listing_single()
	 *
	 * Show a single listing
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_listing()
	 * @uses wp_kses_allowed_html()
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */
	public function shortcode_listing_single( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'id'		=> '',
	        'full'		=> 'true',
            'before' 	=> '',
            'after'  	=> '',
            'wrap'	 	=> 'div'
        );
        
        // Merge shortcodes atts with defaults and extract
		extract( shortcode_atts( $defaults, $atts ) );
        
        // Make sure full is emtpy or false, or falls back to default (true)
        
        if( ( empty( $full ) || $full === 'false' ) && $full !== 'true' ) {
        	$full = false;
        } else {	        
	        $full = true;
        }
		
		ob_start();
		
		// Get listing output
		wpsight_listing( $id, $full );
        
        $output = sprintf( '%1$s%3$s%2$s', $before, $after, ob_get_clean() );
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-listing-sc">%1$s</%2$s>', $output, $wrap );
		
		return apply_filters( 'wpsight_shortcode_listing', $output, $atts );

	}

}

new WPSight_Shortcode_Listing_Single();
