<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listings_Map_Shortcode class
 */
class WPSight_Listings_Map_Shortcode {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'wpsight_listings_map', array( $this, 'shortcode_listings_map' ) );
	}

	/**
	 * Shortcode callback for [wpsight_listings_map].
	 *
	 * @param array $attr Shortcode attributes.
	 *
	 * @return string $output Entire shortcode output.
	 *
	 * @uses wpsight_listings_map()
	 * @uses wp_kses_post()
	 * @uses shortcode_atts()
	 * @since 1.0.0
	 * @updated 1.4.2
	 */
	public function shortcode_listings_map( array $attr ): string	{

		// Define defaults
		$defaults = array(
			'nr'            => '',
			'width'         => '',
			'height'        => '',
			'map_type'      => '',
			'control_type'  => '',
			'scrollwheel'   => '',
			'streetview'    => '',
			'style'         => '',
			'map_id'        => uniqid( 'shortcode-' ),
			'toggle'        => false,
			'toggle_button' => __( 'Show Map', 'wpcasa-listings-map' ),
			'cluster_grid'  => 60,
			'before'        => '',
			'after'         => '',
			'wrap'          => 'div',
		);

		// Add custom vars to $defaults
		$defaults = array_merge( $defaults, wpsight_listing_query_vars() );

		// Merge shortcode attributes with defaults
		$atts = shortcode_atts( $defaults, $attr, 'wpsight_listings_map' );

		// Define allowed tags for wrapping (whitelist)
		$allowed_wrap_tags = array( 'div', 'section', 'span' );

		// Sanitize wrap attribute early
		$wrap = strtolower( sanitize_text_field( $atts['wrap'] ) );
		if ( ! in_array( $wrap, $allowed_wrap_tags, true ) ) {
			$wrap = 'div'; // Fallback to safe default
		}

		// Get the listings map with sanitized shortcode atts
		$listings_map = wpsight_get_listings_map( $atts );

		// Sanitize before and after safely
		$before = wp_kses_post( $atts['before'] );
		$after  = wp_kses_post( $atts['after'] );

		// Create shortcode output
		$output = sprintf( '%1$s%3$s%2$s', $before, $after, $listings_map );

		// Optionally wrap shortcode in HTML tags
		if ( ! empty( $wrap ) && 'false' !== $wrap ) {
			$output = sprintf(
				'<%2$s class="wpsight-listings-map-sc">%1$s</%2$s>',
				$output,
				esc_attr( $wrap )
			);
		}

		return apply_filters( 'wpsight_shortcode_listings_map', $output, $attr );
	}
}

new WPSight_Listings_Map_Shortcode();
