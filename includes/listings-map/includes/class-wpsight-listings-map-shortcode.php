<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listings_Map_Shortcode class
 */
class WPSight_Listings_Map_Shortcode {

	/**
	 *	Constructor
	 */
	public function __construct() {
		add_shortcode( 'wpsight_listings_map', array( $this, 'shortcode_listings_map' ) );
	}

	/**
	 *	shortcode_listings_map()
	 *	
	 *	Show the listings map.
	 *	
	 *	@param	array   $attr	Shortcode attributes
	 *	@uses	shortcode_atts()
	 *	@uses	wpsight_listings_map()
	 *	@uses	wp_kses_post()
	 *	@return string	$output	Entire shortcode output
	 *	
	 *	@since 1.0.0
	 */
	public function shortcode_listings_map( $attr ) {

		// Define defaults

		$defaults = array(
			'nr'			=> '',
			'width'			=> '',
			'height'		=> '',
			'map_type'		=> '',
			'control_type'	=> '',
			'scrollwheel'	=> '',
			'streetview'	=> '',
			'style'			=> '',
			'map_id'		=> uniqid( 'shortcode-' ),
			'toggle'		=> false,
			'toggle_button'	=> __( 'Show Map', 'wpcasa-listings-map' ),
			'cluster_grid'	=> 60,
			'before'		=> '',
			'after'			=> '',
			'wrap'			=> 'div'
		);
		
		// Add custom vars to $defaults
		$defaults = array_merge( $defaults, wpsight_listing_query_vars() );

		// Merge shortcodes atts with defaults
		$atts = shortcode_atts( $defaults, $attr, 'wpsight_listings_map' );

		// Get the listings map with shortocde atts
		$listings_map = wpsight_get_listings_map( $atts );

		// Create shortcode output
		$output = sprintf( '%1$s%3$s%2$s', wp_kses_post( $atts['before'] ), wp_kses_post( $atts['after'] ), $listings_map );

		// Optionally wrap shortcode in HTML tags

		if ( ! empty( $atts['wrap'] ) && $atts['wrap'] != 'false' )
			$output = sprintf( '<%2$s class="wpsight-listings-map-sc">%1$s</%2$s>', $output, tag_escape( $atts['wrap'] ) );

		return apply_filters( 'wpsight_shortcode_listings_map', $output, $attr );

	}

}

new WPSight_Listings_Map_Shortcode();
