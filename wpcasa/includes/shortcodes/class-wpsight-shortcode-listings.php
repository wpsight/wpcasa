<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcode_Listings {
	
	/**
	 * __construct()
	 *
	 * @access public
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_listings', array( $this, 'shortcode_listings' ) );
	}
	
	/**
	 * shortcode_listings()
	 *
	 * Shortcode [wpsight_listings] to
	 * display listing queries.
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_listing_query_vars()
	 * @uses wpsight_listings()
	 * @uses wp_kses_allowed_html()
	 *
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */

    public function shortcode_listings( $atts ) {
	    
	    $atts['show_panel']		= isset( $atts['show_panel'] ) && $atts['show_panel'] == 'false' ? false : true;
	    $atts['show_paging']	= isset( $atts['show_paging'] ) && $atts['show_paging'] == 'false' ? false : true;
        
        $defaults = array(
            'before'			=> '',
            'after'				=> '',
            'wrap'	 			=> 'div',
            'offset'			=> '',
			'nr'				=> '',
			'orderby'			=> 'date',
			'order'				=> 'desc',
			'class'		 		=> '', // additional css class
			'orientation'		=> 'horizontal', // can be vertical
			'show_panel'		=> true, // can be false
			'show_paging'		=> true // can be false
        );
        
		// Add custom vars to $defaults
		
		if( $atts['show_panel'] || $atts['show_paging'] )
			$defaults = array_merge( $defaults, wpsight_listing_query_vars() );

		// Merge shortcodes atts with defaults and extract
        extract( shortcode_atts( $defaults, $atts ) );
		
		$args = shortcode_atts( $defaults, $atts );
		
		// Respect orderby panel
		
		$args['orderby']	= $atts['show_panel'] && ! empty( get_query_var( 'orderby' ) ) ? get_query_var( 'orderby' ) : $args['orderby'];
		$args['order']		= $atts['show_panel'] && ! empty( get_query_var( 'orderby' ) ) ? get_query_var( 'order' ) : $args['order'];
		
		// Get the listings
        
        ob_start();
        
        wpsight_listings( $args );
        
        $output = sprintf( '%1$s%3$s%2$s', $before, $after, ob_get_clean() );
        
        // Set css class
		$class = ! empty( $class ) ? ' ' . sanitize_html_class( $class ) : '';
		
		// Set orientation
		$orientation = in_array( $orientation, array( 'horizontal', 'vertical' ) ) ? ' ' . $orientation : '';
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-listing-sc%3$s%4$s">%1$s</%2$s>', $output, $wrap, $class, $orientation );

        return apply_filters( 'wpsight_shortcode_listings', $output, $atts );
        
    }

}

new WPSight_Shortcode_Listings();
