<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcode_Listings_Search {

	/**
	 * Constructor
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_listings_search', array( $this, 'shortcode_listings_search' ) );
	}
	
	/**
	 * shortcode_listings_search()
	 *
	 * Show the listings search form.
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_search()
	 * @uses wp_kses_allowed_html()
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */
	public function shortcode_listings_search( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'id' 		  => '',
			'class' 	  => 'wpsight-listings-search',
			'orientation' => 'horizontal', // can be vertical
			'action' 	  => '', // Empty action redirects to same page
			'advanced'	  => 'true', // Set false to hide
			'reset'		  => 'true', // Set false to hide
            'before' 	  => '',
            'after'  	  => '',
            'wrap'	 	  => 'div'
        );
        
        // Merge shortcodes atts with defaults
        $args = shortcode_atts( $defaults, $atts );
        
        // Make sure advanced is emtpy or false, or falls back to default
        
        if( empty( $args['advanced'] ) || $args['advanced'] === 'false' ) {
        	$args['advanced'] = false;
        } else {	        
	        unset( $args['advanced'] );
        }
        
        // Make sure reset is emtpy or false, or falls back to default
        
        if( empty( $args['reset'] ) || $args['reset'] === 'false' ) {
        	$args['reset'] = false;
        } else {	        
	        unset( $args['reset'] );
        }
        
        // Extract args
		extract( $args );
		
		ob_start();

        wpsight_search( $args );
        
        $output = sprintf( '%1$s%3$s%2$s', $before, $after, ob_get_clean() );
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-listings-search-sc">%1$s</%2$s>', $output, $wrap );
		
		return apply_filters( 'wpsight_shortcode_listings_search', $output, $atts );

	}

}

new WPSight_Shortcode_Listings_Search();
