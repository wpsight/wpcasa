<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcode_Listing_Teasers {
	
	/**
	 * __construct()
	 *
	 * @access public
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_listing_teasers', array( $this, 'shortcode_listing_teasers' ) );
	}
	
	/**
	 * shortcode_listing_teasers()
	 *
	 * Show a list of listing teasers.
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_listing_query_vars()
	 * @uses wpsight_listing_teasers()
	 * @uses sanitize_html_class()
	 * @uses wp_kses_allowed_html()
	 *
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */

    public function shortcode_listing_teasers( $atts ) {
        
        $defaults = array(
            'before' 		 => '',
            'after'  		 => '',
            'wrap'	 		 => 'div',
            'offset'         => '',
			'posts_per_page' => get_query_var( 'nr' ) ? get_query_var( 'nr' ) : get_option( 'posts_per_page' ),
			'orderby'        => get_query_var( 'orderby' ) ? get_query_var( 'orderby' ) : 'date',
			'order'          => get_query_var( 'order' ) ? get_query_var( 'order' ) : 'DESC',
			'featured'       => get_query_var( 'featured' ) ? get_query_var( 'featured' ) : null,
            'class'		  	 => '', // additional css class
            'orientation' 	 => 'horizontal' // can be vertical
        );
        
		// Add custom vars to $defaults
		$defaults = array_merge( $defaults, wpsight_listing_query_vars() );

		// Merge shortcodes atts with defaults and extract
        extract( shortcode_atts( $defaults, $atts ) );
        
        ob_start();
        
        wpsight_listing_teasers( shortcode_atts( $defaults, $atts ) );
        
        $output = sprintf( '%1$s%3$s%2$s', $before, $after, ob_get_clean() );
        
        // Set css class
        $class = ! empty( $class ) ? ' ' . sanitize_html_class( $class ) : '';
        
        // Set orientation
        $orientation = in_array( $orientation, array( 'horizontal', 'vertical' ) ) ? ' ' . $orientation : '';
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-listing-teasers-sc%3$s%4$s">%1$s</%2$s>', $output, $wrap, $class, $orientation );

        return apply_filters( 'wpsight_shortcode_listing_teasers', $output, $atts );
        
    }

}

new WPSight_Shortcode_Listing_Teasers();
