<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Shortcode_Related_Listings {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'wpsight_related_listings', array( $this, 'shortcode_related_listings' ) );
	}

	/**
	 * shortcode_related_listings()
	 *
	 * Shortcode [wpsight_related_listings] to
	 * display listing queries.
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_listing_query_vars()
	 * @uses wpsight_listings()
	 * @uses wp_kses_allowed_html()
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */
    public function shortcode_related_listings( $atts ) {
        $taxonomy_filters	= array();

        foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {
            if ($key == 'listing-category') continue;
            $taxonomy_filters[ $key ] = isset( $atts[ $key ] ) ? esc_sql( $atts[ $key ] ) : false;
        }

        if ( !array_filter($taxonomy_filters) ) {
            if ( !is_array($atts) ) {
                $atts = [];
            }
            $atts[ 'location' ] = array_column( get_the_terms(get_the_ID(), 'location'), 'slug');
            $atts[ 'listing-type' ] = array_column( get_the_terms(get_the_ID(), 'listing-type'), 'slug');
            $atts[ 'feature' ] = array_column( get_the_terms(get_the_ID(), 'feature'), 'slug');
        };


        $defaults = array(
            'before'			=> '',
            'after'				=> '',
            'wrap'	 			=> 'div',
            'offset'			=> '',
            'posts_per_page'	=> get_query_var( 'nr' ) ? get_query_var( 'nr' ) : get_option( 'posts_per_page' ),
            'orderby'			=> get_query_var( 'orderby' ) ? get_query_var( 'orderby' ) : 'date',
            'order'				=> get_query_var( 'order' ) ? get_query_var( 'order' ) : 'DESC',
            'availability'		=> 'all', // can be all, available or unavailable
            'show_panel'		=> true, // can be false
            'show_paging'		=> true // can be false
        );



		// Add custom vars to $defaults
		$defaults = array_merge( $defaults, wpsight_listing_query_vars() );

		// Merge shortcodes atts with defaults and extract
        extract( shortcode_atts( $defaults, $atts ) );

		$args = shortcode_atts( $defaults, $atts );

        // Optionally Convert strings true|false to bool

		$args['show_panel'] = $args['show_panel'] === true || $args['show_panel'] == 'true' ? true : false;
		$args['show_paging'] = $args['show_paging'] === true || $args['show_paging'] == 'true' ? true : false;

        ob_start();

        wpsight_listings( $args );

        $output = sprintf( '%1$s%3$s%2$s', $before, $after, ob_get_clean() );

		// Optionally wrap shortcode in HTML tags

		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-listings-sc">%1$s</%2$s>', $output, $wrap );

        return apply_filters( 'wpsight_shortcode_listings', $output, $atts );

    }

}

new WPSight_Shortcode_Related_Listings();
