<?php
/**
 *	wpsight_listings_map()
 *	
 *	Echo wpsight_get_listings_map()
 *	
 *	@param	array			$atts		Array of arguments for the map display
 *	@param	array|object	$map_query	Custom query
 *	@uses	wpsight_get_listings_map()
 *	
 *	@since 1.1.0
 */
function wpsight_listings_map( $atts = array(), $map_query = array() ) {
	echo apply_filters( 'wpsight_listings_map', wpsight_get_listings_map( $atts, $map_query ), $atts, $map_query );
}

/**
 *	wpsight_get_listings_map()
 *	
 *	Create a Google map displaying listings.
 *	
 *	@param	array			$atts		Array of arguments for the map display
 *	@param	array|object	$map_query	Custom query
 *	@uses	wpsight_get_option()
 *	@uses	wpsight_get_listings()
 *	@uses	wpsight_get_template_part()
 *	@uses	WPSight_Listings_Map_Styles::get_map_styles_choices()
 *	@uses	WPSight_Listings_Map_Styles::get_map_style()
 *	
 *	@since 1.1.0
 */
function wpsight_get_listings_map( $atts = array(), $map_query = array() ) {
	
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
		'map_id'		=> uniqid(),
		'toggle'		=> false,
		'toggle_button'	=> __( 'Show Map', 'wpcasa-listings-map' ),
		'cluster_grid'	=> 60,
		'map_page'		=> get_the_id() == wpsight_get_option( 'listings_map_page' ),
		'infobox_event'	=> '',
		'infobox_close'	=> ''
	);

	// Parse incoming $atts into an array and merge it with $defaults
	$args = wp_parse_args( $atts, $defaults );

	// Check number of listings

	if ( ! $args['nr'] ) {
		$option = wpsight_get_option( 'listings_map_nr' );
		$args['nr'] = intval( $option );
	} else {
		$args['nr'] = intval( $args['nr'] );
	}

	// Check default width

	if ( ! $args['width'] ) {
		$option = wpsight_get_option( 'listings_map_width' );
		$args['width'] = sanitize_text_field( $option );
	} else {
		$args['width'] = sanitize_text_field( $args['width'] );
	}

	// Check default height

	if ( ! $args['height'] ) {
		$option = wpsight_get_option( 'listings_map_height' );
		$args['height'] = tag_escape( $option );
	} else {
		$args['height'] = tag_escape( $args['height'] );
		if( strpos( $args['height'], 'px' ) === false )
			$args['height'] = $args['height'] . 'px';
	}

	// Check default option 'map_style'

	if ( ! $args['style'] ) {
		$option = wpsight_get_option( 'listings_map_style' );		
		if ( $option ) {
			$args['style'] = in_array( $option, array_keys( WPSight_Listings_Map_Styles::get_map_styles_choices() ) ) ? $option : $defaults['style'];
		}
	}

	// Check default option 'map_type'

	if ( ! $args['map_type'] ) {
		$option = wpsight_get_option( 'listings_map_type' );
		if ( $option ) {
			$args['map_type'] = in_array( $option, array( 'ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN' ) ) ? $option : $defaults['map_type'];
		} else {
			$args['map_type'] = 'ROADMAP';
		}
	}

	// Check default option 'control_type'

	if ( ! $args['control_type'] ) {
		$option = wpsight_get_option( 'listings_map_control_type' );
		$args['control_type'] = $option ? 'true' : 'false';
	} else {		
		$args['control_type'] = in_array( $args['control_type'], array( 'true', 'false' ) ) ? $args['control_type'] : $defaults['control_type'];
	}

	// Check default option 'scrollwheel'

	if ( ! $args['scrollwheel'] ) {
		$option = wpsight_get_option( 'listings_map_scrollwheel' );
		$args['scrollwheel'] = $option ? 'true' : 'false';
	} else {
		$args['scrollwheel'] = in_array( $args['scrollwheel'], array( 'true', 'false' ) ) ? $args['scrollwheel'] : $defaults['scrollwheel'];
	}

	// Check default option 'streetview'

	if ( ! $args['streetview'] ) {
		$option = wpsight_get_option( 'listings_map_streetview' );
		$args['streetview'] = $option ? 'true' : 'false';
	} else {
		$args['streetview'] = in_array( $args['streetview'], array( 'true', 'false' ) ) ? $args['streetview'] : $defaults['streetview'];
	}
	
	// Check default option 'infobox_event'

	if ( ! $args['infobox_event'] ) {
		$option = wpsight_get_option( 'listings_map_infobox_event' );
		if ( $option ) {
			$args['infobox_event'] = in_array( $option, array( 'mouseover', 'click' ) ) ? $option : $defaults['infobox_event'];
		} else {
			$args['infobox_event'] = 'mouseover';
		}
	}
	
	// Check default option 'infobox_close'

	if ( ! $args['infobox_close'] ) {
		$option = wpsight_get_option( 'listings_map_infobox_close' );
		$args['infobox_close'] = $option ? 'true' : 'false';
	} else {
		$args['infobox_close'] = in_array( $args['infobox_close'], array( 'true', 'false' ) ) ? $args['infobox_close'] : $defaults['infobox_close'];
	}
	
	// Check default option 'toggle'

	if ( ! isset( $args['toggle'] ) || ( empty( $args['toggle'] ) && ! is_bool( $args['toggle'] ) ) ) {
		$option = wpsight_get_option( 'listings_map_toggle' );		
		$args['toggle'] = $option ? true : false;
	} else {
		$args['toggle'] = true === $args['toggle'] || 'true' === $args['toggle'] ? true : false;
	}

	// Get map listings
	
	$map_query_args = array(
		'nr'			=> $args['nr'],
		'paged'			=> 1,
		'meta_query'	=> array(
			'relation' => 'AND',
			array(
				'key'		=> '_geolocation_lat',
				'compare'	=> 'EXISTS'
			),
			array(
				'key'		=> '_geolocation_long',
				'compare'	=> 'EXISTS'
			),
			array(
				'key'		=> '_map_exclude',
				'compare'	=> 'NOT EXISTS'
			)
		)
	);

	// Set tax query for listing taxonomies

	foreach ( wpsight_taxonomies( 'names' ) as $k ) {

		if ( ! empty( $args[$k] ) ) {

			// Set operator
			$operator = 'IN';

			// Get search field
			$search_field = wpsight_get_search_field( $k );

			// Get search field operator
			$search_field_operator = isset( $search_field['data']['operator'] ) ? $search_field['data']['operator'] : false;

			if ( $search_field_operator == 'AND' )
				$operator = 'AND';

			// If multiple $_GET terms, implode comma

			if ( is_array( $args[$k] ) )
				$args[$k] = implode( ',', $args[$k] );

			// Check URL for multiple terms

			if ( strpos( $args[$k], ',' ) ) {
				$args[$k] = explode( ',', $args[$k] );
			} elseif ( strpos( $args[$k], '|' ) ) {
				$args[$k] = explode( '|', $args[$k] );
				$operator = 'AND';
			}

			if ( ! empty( $args[$k] ) ) {

				$map_query_args['tax_query'][$k] = array(
					'taxonomy' => $k,
					'field'    => 'slug',
					'terms'    => $args[$k],
					'operator' => $operator
				);

			}

		}

	}

	// Remove tax_query if empty
	if ( empty( $map_query_args['tax_query'] ) )
		unset( $map_query_args['tax_query'] );
		
	if( is_object( $map_query ) ) {
		
		$map_query_args = wp_parse_args( $map_query->query, $map_query_args );
		
	} elseif( is_array( $map_query ) ) {
		
		$map_query_args = wp_parse_args( $map_query, $map_query_args );
		
	}
	
	$map_query = wpsight_get_listings( apply_filters( 'wpsight_listings_map_query_args', $map_query_args ) );

	// build the options
	$map_options = array(
		'map' => array(
			'mapTypeId'         => esc_js( $args['map_type'] ),
			'mapTypeControl'    => esc_js( $args['control_type'] ),
			'scrollwheel'       => esc_js( $args['scrollwheel'] ),
			'streetViewControl' => esc_js( $args['streetview'] ),
			'infobox_event'		=> esc_js( $args['infobox_event'] ),
			'infobox_close'		=> esc_js( $args['infobox_close'] ),
			'id'                => esc_attr( $args['map_id'] ),
			'markers'           => array(),
			'styles'			=> WPSight_Listings_Map_Styles::get_map_style( $args['style'] ),
			'cookie'			=> WPSIGHT_LISTINGS_MAP_COOKIE,
			'cookie_path'		=> COOKIEPATH,
			'map_page'			=> $args['map_page'] ? 'true' : 'false',
		),
		'cluster' => array(
			'gridSize'			=> esc_js( $args['cluster_grid'] ),
			'styles'			=> array(
				array(
					'textColor'	=> 'white',
					'url'		=> WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/m1.png',
					'height'	=> 53,
					'width'		=> 52,
				),
				array(
					'textColor'	=> 'white',
					'url'		=> WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/m2.png',
					'height'	=> 56,
					'width'		=> 55,
				),
				array(
					'textColor'	=> 'white',
					'url'		=> WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/m3.png',
					'height'	=> 66,
					'width'		=> 65,
				),
				array(
					'textColor'	=> 'white',
					'url'		=> WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/m4.png',
					'height'	=> 78,
					'width'		=> 77,
				),
				array(
					'textColor'	=> 'white',
					'url'		=> WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/m5.png',
					'height'	=> 90,
					'width'		=> 89,
				),
			)
		)
	);

	// build the markers
	while ( $map_query->have_posts() ) : $map_query->the_post();
	
		$geo_lat = esc_js( get_post_meta( get_the_id(), '_geolocation_lat', true ) );
		$geo_lng = esc_js( get_post_meta( get_the_id(), '_geolocation_long', true ) );
		
		// continue if no lat/long is available
		if ( ! $geo_lat || ! $geo_lng )
			continue;

		// set up filtrable icon options
		$icon_options = apply_filters( 'wpsight_listings_map_icon', array(
			'url'        => WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/spotlight-poi.png',
			'size'       => array( 22, 40 ),
			'origin'     => array( 0, 0 ),
			'anchor'     => array( 11, 40 ),
			'scaledSize' => array( 22, 40 )
		), get_the_id() );

		// set up marker
		$map_options['map']['markers'][] = array(
			'title' => esc_js( get_post_meta( get_the_id(), '_listing_title', true ) ),
			'lat'   => $geo_lat,
			'lng'   => $geo_lng,
			'icon'	=> $icon_options,
			// build the infobox
			'infobox' => array(
				'content'		=> wpsight_listings_map_infobox( $args ),
				'closeBoxURL'	=> ($args['infobox_close'] == 'true') ? apply_filters( 'wpsight_listings_map_infobox_close_icon', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/images/close.png') : '',
				'pixelOffset'	=> array( 24, -20 )
			)
		);

	endwhile;
    wp_reset_query();

	wp_enqueue_script( 'wpsight-listings-map' );
	wp_localize_script( 'wpsight-listings-map', 'wpsightMap', apply_filters( 'wpsight_listings_map_options', $map_options ) );
	
	if( $map_query->have_posts() ) {

		ob_start();		
		wpsight_get_template( 'listings-map.php', array( 'args' => $args, 'map_query' => $map_query ), WPSIGHT_LISTINGS_MAP_PLUGIN_DIR . '/templates/' );
		
		return apply_filters( 'wpsight_get_listings_map', ob_get_clean(), $args, $map_query );
	
	}

}

/**
 *	Loads and returns the map infobox template.
 *	
 *	@param	array	$args	Listings map arguments
 *	@uses	wpsight_get_template()
 *	@return	string	Infobox HTML
 *	
 *	@since 1.0.0
 */
function wpsight_listings_map_infobox( $args ) {

	ob_start();
	wpsight_get_template( 'listings-map-infobox.php', $args, WPSIGHT_LISTINGS_MAP_PLUGIN_DIR . '/templates/' );

	return apply_filters( 'wpsight_listings_map_infobox', ob_get_clean(), $args );

}
