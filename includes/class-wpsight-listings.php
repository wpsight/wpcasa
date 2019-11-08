<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listings class
 */
class WPSight_Listings {

	/**
	 * listings()
	 *
	 * Output formatted listing query
	 *
	 * @param array $args Array of query arguments
	 * @param string $template_path Template path for wpsight_get_template()
	 * @uses wpsight_get_listings()
	 * @uses wpsight_get_template()
	 * @uses wpsight_get_template_part()
	 * @uses wp_reset_query()
	 *
	 * @since 1.0.0
	 */
	public static function listings( $args = array(), $template_path = '' ) {
		global $wpsight_query;

		// Get listings query
		$wpsight_query = wpsight_get_listings( $args );

		if ( $wpsight_query->have_posts() ) {

			// Get template before loop
			wpsight_get_template( 'listings-before.php', $args, $template_path );

			// Loop through listings

			while ( $wpsight_query->have_posts() ) {

				// Setup listing data
				$wpsight_query->the_post();

				// Get listing loop template
				wpsight_get_template( 'listing-archive.php', $args, $template_path );

			}

			// Get template after loop
			wpsight_get_template( 'listings-after.php', $args, $template_path );

		} else {

			// Get template for no listings
			wpsight_get_template( 'listings-no.php', $args, $template_path );

		}

		wp_reset_query();

	}

	/**
	 * get_listings()
	 *
	 * Return listings WP_Query
	 *
	 * @param array $args Array of query arguments
	 * @uses get_query_var()
	 * @uses wpsight_listing_query_vars()
	 * @uses wp_parse_args()
	 * @uses wpsight_post_type()
	 * @uses is_user_logged_in()
	 * @uses wpsight_details()
	 * @uses wpsight_get_query_var_by_detail()
	 * @uses wpsight_taxonomies()
	 * @uses wpsight_get_search_field()
	 * @return object $result WP_Query object
	 *
	 * @since 1.0.0
	 */
	public static function get_listings( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'p'						=> '',
			'post__in'				=> '',
			'offset'				=> '',
			'post_status'			=> '',
			'posts_per_page'		=> get_query_var( 'nr' )   ? get_query_var( 'nr' ) : get_option( 'posts_per_page' ),
			'orderby'				=> get_query_var( 'orderby' )  ? get_query_var( 'orderby' ) : 'date',
			'order'					=> get_query_var( 'order' )  ? get_query_var( 'order' ) : 'DESC',
			'author'				=> '',
			'tax_query'				=> array(),
			'meta_query'			=> array(),
			'ignore_sticky_posts'	=> 1,
			'show_panel'			=> true,
			'show_paging'			=> true
		);

		// Add custom vars to $defaults
		$defaults = array_merge( $defaults, wpsight_listing_query_vars() );

		// Get args from WP_Query object

		if ( is_object( $args ) && isset( $args->query_vars ) )
			$args = $args->query_vars;

		// Merge $defaults with $args
		$args = wp_parse_args( $args, $defaults );

		// Make sure paging works

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		if ( isset( $args['paged'] ) )
			$paged = absint( $args['paged'] );
		
		if( false === $args['show_paging'] )
			$paged = 1;

		// Make sure nr arg works too
		if ( ! empty( $args['nr'] ) )
			$args['posts_per_page'] = intval( $args['nr'] );
		
		// Make sure offset is intval or empty
		$args['offset'] = ! empty( $args['offset'] ) ? absint( $args['offset'] ) : '';

		$query_args = array(
			'p'                   => absint( $args['p'] ),
			'post__in'            => $args['post__in'],
			'post_type'           => wpsight_post_type(),
			'ignore_sticky_posts' => $args['ignore_sticky_posts'],
			'offset'              => $args['offset'],
			'posts_per_page'      => intval( $args['posts_per_page'] ),
			'orderby'             => $args['orderby'],
			'order'               => $args['order'],
			'tax_query'           => $args['tax_query'],
			'meta_query'          => $args['meta_query'],
			'paged'               => $paged,
			'author'              => $args['author'],
			'post_status'         => $args['post_status']
		);

		// Set post_status

		if ( empty( $args['post_status'] ) || ! is_user_logged_in() ) {

			// When emtpy or unlogged user, set publish

			$query_args['post_status'] = 'publish';

		} else {

			$query_args['post_status'] = $args['post_status'];

			// When comma-separated, explode to array

			if ( ! is_array( $args['post_status'] ) && strpos( $args['post_status'], ',' ) )
				$query_args['post_status'] = explode( ',', $args['post_status'] );

		}

		// Check if orderby price

		if ( $args['orderby'] == 'price' ) {
			$query_args['meta_query']['price'] = array(
				'key'     => '_price',
				'type'    => 'numeric',
				'compare' => 'EXISTS'
			);
			$query_args['orderby'] = array( 'price' => $query_args['order'] );

		}
        
		// Set meta query for offer (sale, rent)

		if ( ! empty( $args['offer'] ) ) {

			if ( ! array( $args['offer'] ) && strpos( $args['offer'], ',' ) )
				$args['offer'] = explode( ',', $args['offer'] );

			if ( is_array( $args['offer'] ) ) {

				$query_args['meta_query']['offer'] = array(
					'key'     => '_price_offer',
					'value'   => $args['offer'],
					'compare' => 'IN'
				);

			} else {

				$query_args['meta_query']['offer'] = array(
					'key'     => '_price_offer',
					'value'   => $args['offer'],
					'compare' => '='
				);

			}

		}
        
        // Set meta query for availability
		
		if ( ! empty( $args['availability'] ) && $args['availability'] != 'all' ) {
            
 			if( $args['availability'] == 'unavailable' )
				$args['availability'] = 1;
			elseif( $args['availability'] == 'available' )
				$args['availability'] = 0;
            
 			$query_args['meta_query']['availability'] = array(
				'key'     => '_listing_not_available',
				'value'   => $args['availability'],
				'compare' => '='
			);
				
 		}
        
		// Set meta query for min (minimum price)

		if ( ! empty( $args['min'] ) ) {

			$query_args['meta_query']['min'] = array(
				'key'     => '_price',
				'value'   => preg_replace( '/\D/', '', $args['min'] ),
				'compare' => '>=',
				'type'    => 'numeric'
			);

		}

		// Set meta query for max (maximum price)

		if ( ! empty( $args['max'] ) ) {

			$query_args['meta_query']['max'] = array(
				'key'     => '_price',
				'value'   => preg_replace( '/\D/', '', $args['max'] ),
				'compare' => '<=',
				'type'    => 'numeric'
			);

		}

		// Set meta queries for listing details

		foreach ( wpsight_details() as $k => $v ) {

			$detail_var = wpsight_get_query_var_by_detail( $k );

			if ( ! empty( $args[$detail_var] ) ) {

				$compare = $v['data_compare'];

				if ( strpos( $args[$detail_var], ',' ) ) {
					$args[$detail_var] = explode( ',', $args[$detail_var] );
					$compare = 'IN';
				} elseif ( strpos( $args[$detail_var], '|' ) ) {
					$args[$detail_var] = explode( '|', $args[$detail_var] );
					$compare = 'AND';
				}

				$query_args['meta_query'][$detail_var] = array(
					'key'    => '_' . $v['id'],
					'value'   => $args[$detail_var],
					'compare' => $compare,
					'type'   => $v['data_type']
				);

			}

		}

		// Remove meta_query if empty

		if ( empty( $query_args['meta_query'] ) )
			unset( $query_args['meta_query'] );

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

					$query_args['tax_query'][$k] = array(
						'taxonomy' => $k,
						'field'    => 'slug',
						'terms'    => $args[$k],
						'operator' => $operator
					);

				}

			}

		}

		// Remove tax_query if empty

		if ( empty( $query_args['tax_query'] ) )
			unset( $query_args['tax_query'] );

		// Set post__in for keyword search

		if ( $args['keyword'] ) {

			// Trim and explode keywords
			$keywords = array_map( 'trim', explode( ',', $args['keyword'] ) );

			// Setup SQL

			$posts_keywords_sql    = array();
			$postmeta_keywords_sql = array();

			// Loop through keywords and create SQL snippets

			foreach ( $keywords as $keyword ) {

				// Create post meta SQL
				$postmeta_keywords_sql[] = " meta_value LIKE '%" . esc_sql( $keyword ) . "%' ";

				// Create post title and content SQL
				$posts_keywords_sql[]    = " post_title LIKE '%" . esc_sql( $keyword ) . "%' OR post_content LIKE '%" . esc_sql( $keyword ) . "%' ";

			}

			// Get post IDs from post meta search

			$post_ids = $wpdb->get_col( "
			    SELECT DISTINCT post_id FROM {$wpdb->postmeta}
			    WHERE " . implode( ' OR ', $postmeta_keywords_sql ) . "
			" );

			// Merge with post IDs from post title and content search

			$post_ids = array_merge( $post_ids, $wpdb->get_col( "
			    SELECT ID FROM {$wpdb->posts}
			    WHERE ( " . implode( ' OR ', $posts_keywords_sql ) . " )
			    AND post_type = '" . esc_sql( wpsight_post_type() ) . "'
			    AND post_status = '" . esc_sql( $query_args['post_status'] ) . "'
			" ), array( 0 ) );

			/* array( 0 ) is set to return no result when no keyword was found */

		}

		// Set post__in with post IDs

		if ( ! empty( $post_ids ) )
			$query_args['post__in'] = $post_ids;

		// Set post__not_in for unavailable (sold, rented etc.) listings

		$exclude_unavailable = $wpdb->get_col( $wpdb->prepare( "
		    SELECT DISTINCT post_id FROM {$wpdb->postmeta}
		    WHERE ( meta_key = '%s' OR meta_key = '%s' )
		    AND ( meta_value = '%s' OR meta_value = '%s' )
		", '_price_sold_rented', '_listing_not_available', '1', 'on' ) );

		if ( ! empty( $exclude_unavailable ) && apply_filters( 'wpsight_exclude_unavailable', false ) == true )
			$query_args['post__not_in'] = $exclude_unavailable;

		// Remove tax_query if empty

		if ( empty( $query_args['post__not_in'] ) )
			unset( $query_args['post__not_in'] );

		// Filter args
		$query_args = apply_filters( 'wpsight_get_listings_query_args', $query_args, $args );

		do_action( 'wpsight_get_listings_before', $query_args, $args );

		$result = new WP_Query( $query_args );

		do_action( 'wpsight_get_listings_after', $query_args, $args );

		// Reset query
		wp_reset_query();

		return apply_filters( 'wpsight_get_listings', $result, $query_args, $args );

	}

	/**
	 * listing()
	 *
	 * Output formatted single listing or
	 * archive teaser if $full is (bool) false.
	 *
	 * @param integer|object $listing_id Post or listing ID or WP_Post object
	 * @param bool $full Set true to show entire listing or false to show archive teaser
	 * @uses wpsight_get_listing()
	 * @uses setup_postdata()
	 * @uses wpsight_get_template()
	 * @uses wpsight_get_template_part()
	 * @uses wp_reset_postdata()
	 *
	 * @since 1.0.0
	 */
	public static function listing( $listing_id = null, $full = true ) {		
		global $listing;
		
		// check for single listing output
        if ( apply_filters('wpsight_listing_single_output', true) != true )
			return;
			
       $listing = wpsight_get_listing( $listing_id );        
		
		// Show listing if found
		if ( $listing ) {
		
			// Set up post data for required listing
			setup_postdata( $GLOBALS['post'] =& $listing );
		
			if ( $full === true ) {
		
				// Get template before single
				wpsight_get_template( 'listing-single-before.php' );
		
				// Get listing single template
				wpsight_get_template_part( 'listing', 'single' );
		
				// Get template after single
				wpsight_get_template( 'listing-single-after.php' );
		
			} else {
		
				// Get listing archive template
				wpsight_get_template_part( 'listing', 'archive' );
			}
		
			// Reset post data
			wp_reset_postdata();

		} else {
			// Get template for no listings
			wpsight_get_template_part( 'listing', 'no' );
		}

	}
	
	/**
	 * listing_teaser()
	 *
	 * Output formatted single listing teaser.
	 *
	 * @param integer|object $listing_id Post or listing ID or WP_Post object
	 * @uses wpsight_get_listing()
	 * @uses setup_postdata()
	 * @uses wpsight_get_template()
	 * @uses wpsight_get_template_part()
	 * @uses wp_reset_postdata()
	 *
	 * @since 1.0.0
	 */
	public static function listing_teaser( $listing_id = null ) {		
		global $listing;
		
		$listing = wpsight_get_listing( $listing_id );
		
		// Show listing if found
		if ( $listing ) {
		
			// Set up post data for required listing
			setup_postdata( $GLOBALS['post'] =& $listing );
		
			// Get listing teaser template
			wpsight_get_template_part( 'listing', 'teaser' );
		
			// Reset post data
			wp_reset_postdata();

		} else {
			// Get template for no listings
			wpsight_get_template_part( 'listing', 'no' );
		}

	}

	/**
	 * listing_teasers()
	 *
	 * Output list of listing teasers.
	 *
	 * @param array $args Array of query arguments
	 * @param string $template_path Template path for wpsight_get_template()
	 * @uses get_query_var()
	 * @uses wpsight_get_listings()
	 * @uses wpsight_get_template()
	 * @uses wpsight_listing_teaser()
	 * @uses wp_reset_query()
	 *
	 * @since 1.0.0
	 */
	public static function listing_teasers( $args = array(), $template_path = '' ) {
		global $wpsight_query;
		
		// Make sure paging works

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		
		if( false === $args['show_paging'] )
			$paged = 1;

		// Make sure other listing queries don't set paged
		$args = array_merge( array( 'paged' => $paged ), $args );

		// Get listings query
		$wpsight_query = wpsight_get_listings( $args );

		if ( $wpsight_query->have_posts() ) {

			// Get template before loop
			wpsight_get_template( 'listing-teasers-before.php', $args, $template_path );

			// Loop through listings

			while ( $wpsight_query->have_posts() ) {

				// Setup listing data
				$wpsight_query->the_post();
				
				// Get listing teaser loop template
				wpsight_get_template( 'listing-teaser.php', $args, $template_path );

			}
			
			// Get template after loop
			wpsight_get_template( 'listing-teasers-after.php', $args, $template_path );

		} else {
			// Get template for no listings
			wpsight_get_template( 'listings-no.php', $args, $template_path );
		}

		// Reset query
		wp_reset_query();

	}

	/**
	 * get_listing()
	 *
	 * Return single listing post object.
	 *
	 * @param string|object $post Post or listing ID or WP_Post object
	 * @uses wpsight_post_type()
	 * @uses get_post()
	 * @uses wp_reset_query()
	 * @return object WP_Post object
	 *
	 * @since 1.0.0
	 */
	public static function get_listing( $post = null ) {
		global $wpdb;

		if ( ! is_object( $post ) ) {

			// Search custom fields for listing ID

			$post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
			SELECT DISTINCT post_id FROM {$wpdb->postmeta}
			WHERE meta_value LIKE '%s'
			AND ( meta_key = '_listing_id' OR meta_key = '_property_id' )
			", $post ) );

			if ( ! empty( $post_ids_meta ) ) {

				$query = new WP_Query( array( 'post_type' => wpsight_post_type(), 'post__in' => $post_ids_meta ) );

				$post = $query->posts[0];

				wp_reset_query();

			} else {

				$post = get_post( absint( $post ) );

				if ( get_post_type( $post ) != wpsight_post_type() )
					$post = false;

			}

		}

		return apply_filters( 'wpsight_get_listing', $post );

	}

	/**
	 * get_listing_price_raw()
	 *
	 * Return listings price without formatting.
	 *
	 * @param integer $post_id Post ID
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @return string Listing price meta value
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_price_raw( $post_id = '' ) {

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get listing price
		$price_raw = get_post_meta( $post_id, '_price', true );

		if ( empty( $price_raw ) )
			$price_raw = false;

		// Return listing price
		return apply_filters( 'get_listing_price_raw', $price_raw, $post_id );

	}
	
	/**
	 * get_listing_offer()
	 *
	 * Return listings offer (e.g. sale, rent).
	 *
	 * @param integer $post_id Post ID
	 * @param bool $label Optionally return offer key
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @uses wpsight_offers()
	 * @return string Offer label or key
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_offer( $post_id = '', $label = true ) {

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get listing offer
		$offer = get_post_meta( $post_id, '_price_offer', true );

		if ( ! empty( $offer ) ) {

			// Return label if desired
			
			if ( $label === true ) {			
				// Get all offers
				$offers = wpsight_offers();			
				// Set offer label
				$offer = $offers[ $offer ];			
			}
		
		} else {			
			$offer = false;			
		}

		// Return offer key or label
		return apply_filters( 'wpsight_get_listing_offer', $offer, $post_id, $label );

	}
	
	/**
	 * get_listing_period()
	 *
	 * Return listings rental period.
	 *
	 * @param integer $post_id Post ID
	 * @param bool $label Optionally return period key
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @uses wpsight_rental_periods()
	 * @return string Period label or key
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_period( $post_id = '', $label = true ) {

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get listing offer
		$period = get_post_meta( $post_id, '_price_period', true );

		if ( ! empty( $period ) ) {

			// Return label if desired
			
			if ( $label === true ) {			
				// Get all periods
				$periods = wpsight_rental_periods();			
				// Set period label
				$period = $periods[ $period ];			
			}
		
		} else {			
			$period = false;			
		}

		// Return period key or label
		return apply_filters( 'wpsight_get_listing_period', $period, $post_id, $label );

	}

	/**
	 * get_listing_detail()
	 *
	 * Return specific detail value of a listing.
	 *
	 * @param string  $detail  wpsight_details() key
	 * @param integer $post_id Post ID
	 * @uses wpsight_get_option()
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @uses wpsight_get_detail()
	 * @return string|false Listing detail value or false if empty
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_detail( $detail, $post_id = '' ) {

		if ( empty( $detail ) )
			return;
		
		// Check if label is settings is empty

		$option = wpsight_get_option( $detail );
		
		if( empty( $option['label'] ) )
			return;

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Optionally remove underscore

		$pos = strpos( $detail, '_' );

		if ( $pos !== false && $pos == 0 )
			$detail = substr_replace( $detail, '', $pos, strlen( '_' ) );

		// Get listing detail value
		$listing_detail = get_post_meta( $post_id, '_' . $detail, true );

		if ( $listing_detail ) {

			// Check if value is data key			
			$detail_get = wpsight_get_detail( $detail );			
			if ( ! empty( $detail_get['data'] ) )
				$listing_detail = $detail_get['data'][$listing_detail];
			
		} else {
			$listing_detail = false;
		}

		return apply_filters( 'wpsight_get_listing_detail', $listing_detail, $detail, $post_id );

	}

	/**
	 * get_listing_details()
	 *
	 * Return listings details.
	 *
	 * @param integer $post_id Post ID
	 * @param array $details Array of details (keys from wpsight_details())
	 * @param string|bool $formatted CSS class for container or false to return array
	 * @uses get_the_ID()
	 * @uses get_post_custom()
	 * @uses wpsight_details()
	 * @uses wpsight_get_listing_detail()
	 * @uses wpsight_dashes()
	 * @uses wpsight_get_detail()
	 * @uses wpsight_get_measurement()
	 * @uses sanitize_html_class()
	 * @return string|array Formatted details or unformatted array
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_details( $post_id = '', $details = false, $formatted = 'wpsight-listing-details' ) {

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get post meta data
		$post_meta = get_post_custom( $post_id );

		// Get standard listing details
		$standard_details = wpsight_details();

		// Set default details

		if ( $details === false || ! is_array( $details ) )
			$details = array_keys( $standard_details );

		// Loop through details

		$listing_details = '';

		// Set formatted details

		if ( $formatted !== false ) {

			foreach ( $details as $detail ) {

				if ( wpsight_get_listing_detail( $detail, $post_id ) ) {

					$listing_details .= '<span class="listing-' . wpsight_dashes( $detail ) . ' listing-details-detail" title="' . wpsight_get_detail( $detail, 'label' ) . '">';

					$listing_details .= '<span class="listing-details-label">' . wpsight_get_detail( $detail, 'label' ) . '</span> ';
					$listing_details .= '<span class="listing-details-value">' . wpsight_get_listing_detail( $detail, $post_id );

					if ( wpsight_get_detail( $detail, 'unit' ) )
						$listing_details .= ' ' . wpsight_get_measurement( wpsight_get_detail( $detail, 'unit' ) );
					
					$listing_details .= '</span>';

					$listing_details .= '</span><!-- .listing-' . wpsight_dashes( $detail ) . ' -->' . "\n";

				}

			}

			if ( $listing_details )
				$listing_details = '<div class="' . sanitize_html_class( $formatted ) . ' clearfix">' . $listing_details . '</div><!-- .' . sanitize_html_class( $formatted ) . ' -->';

			// Set array of unformatted details

		} else {
            
            $listing_details = array();

			foreach ( $details as $detail ) {

				$listing_details[$detail] = array(
					'label' => wpsight_get_detail( $detail, 'label' ),
					'unit'  => wpsight_get_detail( $detail, 'unit' ),
					'value' => wpsight_get_listing_detail( $detail )
				);

			}

		}

		return apply_filters( 'wpsight_get_listing_details', $listing_details, $post_id, $details, $formatted );

	}

	/**
	 * get_listing_summary()
	 *
	 * Return specific set of listings details.
	 *
	 * @param integer $post_id Post ID
	 * @param array $details Array of details (keys from wpsight_details())
	 * @param bool $formatted Function returns array if false
	 * @uses wpsight_get_listing_details()
	 * @return string|array Formatted details or unformatted array
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_summary( $post_id = '', $details = false, $formatted = 'wpsight-listing-summary' ) {

		// Define set of details

		if ( $details === false || ! is_array( $details ) )
			$details = array( 'details_1', 'details_2', 'details_3' );

		$listing_summary = wpsight_get_listing_details( $post_id, $details, $formatted );

		return apply_filters( 'wpsight_get_listing_summary', $listing_summary, $post_id, $details, $formatted );

	}

	/**
	 * get_listing_id()
	 *
	 * Return listing ID. By default the listing ID
	 * is a prefix with the post ID. The listing ID
	 * can manually be changed in the listing details
	 * meta box and is saved as custom post meta '_listing_id'.
	 *
	 * @param integer $post_id Post ID
	 * @param string  $prefix  Lising ID prefix
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @uses wpsight_get_option()
	 * @return string|bool Listing ID or false if no post ID available
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_id( $post_id = '', $prefix = '' ) {

		// Check if custom post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Check if custom or global post ID

		if ( ! $post_id )
			return false;

		// Check if post meta listing ID available
		$listing_id = get_post_meta( $post_id, '_listing_id', true );
		
		if( empty( $listing_id ) ) {
		
			// Get listing ID prefix
			
			if( empty( $prefix ) )
				$prefix = wpsight_get_option( 'listing_id' );
			
			// Combine post ID with prefix
			$listing_id = $prefix . $post_id;
		
		}

		// Return default listing ID
		return apply_filters( 'wpsight_get_listing_id', $listing_id );

	}

	/**
	 * get_listing_price()
	 *
	 * Returns formatted listing price with
	 * with currency and rental period.
	 *
	 * @param integer $post_id               Post ID (defaults to get_the_ID())
	 * @param bool    $args['number_format'] Apply number_format() or not
	 * @param bool    $args['show_currency'] Show currency or not
	 * @param bool    $args['show_period']   Show rental period or not
	 * @param bool    $args['show_request']  Show 'price on request' or not
	 * @uses get_the_ID()
	 * @uses get_post_custom()
	 * @uses wpsight_get_option()
	 * @uses wpsight_get_currency()
	 * @uses wpsight_get_currency_abbr()
	 * @uses wpsight_get_rental_period()
	 * @return string|bool Formatted listing price or false
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_price( $post_id = '', $before = '', $after = '', $args = array() ) {

		// Check if custom post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Check if custom or global post ID

		if ( ! $post_id )
			return false;

		// Set listing price labels

		$listing_price_labels = array(
			'sold'    => __( 'Sold', 'wpcasa'  ),
			'rented'  => __( 'Rented', 'wpcasa'  ),
			'request' => __( 'Price on request', 'wpcasa' )
		);

		$listing_price_labels = apply_filters( 'wpsight_get_listing_price_labels', $listing_price_labels );

		// Set listing price args

		$defauts = array(
			'number_format' => true,
			'show_currency' => true,
			'show_period'  => true,
			'show_request'  => true
		);

		$args = wp_parse_args( $args, $defauts );

		// Get price info

		$listing_price  = self::get_listing_price_raw( $post_id );
		$listing_offer  = self::get_listing_offer( $post_id, false );
		$listing_period = self::get_listing_period( $post_id, false );

		// Return false if no price

		if ( empty( $listing_price ) ) {

			if ( $args['show_request'] !== false ) {
				$listing_price = '<span class="listing-price-on-request">' . $listing_price_labels['request'] . '</span><!-- .listing-price-on-request -->';
			} else {
				return false;
			}

		} else {

			if ( $args['number_format'] == true ) {

				$listing_price_arr = false;

				// Remove white spaces
				$listing_price = preg_replace( '/\s+/', '', $listing_price );

				if ( strpos( $listing_price, ',' ) )
					$listing_price_arr = explode( ',', $listing_price );

				if ( strpos( $listing_price, '.' ) )
					$listing_price_arr = explode( '.', $listing_price );

				if ( is_array( $listing_price_arr ) )
					$listing_price = $listing_price_arr[0];

				// remove dots and commas

				$listing_price = str_replace( '.', '', $listing_price );
				$listing_price = str_replace( ',', '', $listing_price );

				if ( is_numeric( $listing_price ) ) {

					// Get thousands separator
					$listing_price_format = wpsight_get_option( 'currency_separator', true );

					// Add thousands separators

					if ( $listing_price_format == 'dot' ) {
						$listing_price = number_format( $listing_price, 0, ',', '.' );
						if ( is_array( $listing_price_arr ) )
							$listing_price .= ',' . $listing_price_arr[1];
					} else {
						$listing_price = number_format( $listing_price, 0, '.', ',' );
						if ( is_array( $listing_price_arr ) )
							$listing_price .= '.' . $listing_price_arr[1];
					}

				}

			} // endif $args['number_format']

			// Get currency symbol and place before or after value
			$currency_symbol = wpsight_get_option( 'currency_symbol', true );

			$listing_price_symbol = '';

            // Create price markup and place currency before or after value
			if ( $args['show_currency'] == true && ( $currency_symbol == 'before' || $currency_symbol == 'before_space' ) ) {
				
				if ( $currency_symbol == 'before' ) {
					$listing_price_symbol .= '<span class="listing-price-symbol">' . wpsight_get_currency() . '</span><!-- .listing-price-symbol -->';
				} elseif ( $currency_symbol == 'before_space' ) {
					$listing_price_symbol .= '<span class="listing-price-symbol">' . wpsight_get_currency() . '&nbsp;</span><!-- .listing-price-symbol -->';
				}
                
			}
			
            $listing_price_symbol .= '<span class="listing-price-value" itemprop="price" content="'. esc_attr( wpsight_get_listing_price_raw( $post_id ) ) .'">' . $listing_price . '</span><!-- .listing-price-value -->';
            
			if ( $args['show_currency'] == true && ( $currency_symbol == 'after' || $currency_symbol == 'after_space' ) ) {
				
				if ( $currency_symbol == 'after' ) {
					$listing_price_symbol .= '<span class="listing-price-symbol">' . wpsight_get_currency() . '</span><!-- .listing-price-symbol -->';
				} elseif ( $currency_symbol == 'after_space' ) {
					$listing_price_symbol .= '<span class="listing-price-symbol">&nbsp;' . wpsight_get_currency() . '</span><!-- .listing-price-symbol -->';
				}
				
			}
            
			// Add currency for microformat
			$listing_price_symbol .= '<meta itemprop="priceCurrency" content="' . wpsight_get_currency_abbr() . '" />';

			// Merge price with markup and currency
			$listing_price = $listing_price_symbol;

			// Add period if listing is for rent and period is set

			if ( $args['show_period'] == true ) {

				$listing_period = get_post_meta( $post_id, '_price_period', true );

				if ( $listing_offer == 'rent' && ! empty( $listing_period ) ) {

					$listing_price = $listing_price . ' <span class="listing-rental-period">/ ' . wpsight_get_rental_period( $listing_period ) . '</span><!-- .listing-rental-period -->';

				}

			} // endif $show_period

		}

		// Make before and after filtrable

		$before = apply_filters( 'wpsight_get_listing_price_before', $before, $post_id, $args );
		$after  = apply_filters( 'wpsight_get_listing_price_after', $after, $post_id, $args );

		// Add before and after output if desired

		$listing_price_before = ! empty( $before ) ? '<span class="wpsight-listing-price-before">' . strip_tags( $before, '<span><b><strong><i><em><small>' ) . '</span>' : '';
		$listing_price_after  = ! empty( $after ) ? '<span class="wpsight-listing-price-after">' . strip_tags( $after, '<span><b><strong><i><em><small>' ) . '</span>' : '';

		// Create final listing price markup
		$listing_price  = '<div class="wpsight-listing-price">' . $listing_price_before . $listing_price . $listing_price_after . '</div>';

		return apply_filters( 'wpsight_get_listing_price', $listing_price, $post_id, $before, $after, $args );

	}

	/**
	 * get_listing_terms()
	 *
	 * Returns listing terms of a specific
	 * taxonomy ordered by hierarchy.
	 *
	 * @param string  $taxonomy    Taxonomy of the terms to return (defaults to 'feature')
	 * @param integer $post_id     Post ID (defaults to get_the_ID())
	 * @param string  $sep         Separator between terms
	 * @param string  $term_before Content before each term
	 * @param string  $term_after  Content after each term
	 * @param bool    $linked      Link terms to their archive pages or not
	 * @param bool    $reverse     Begin with lowest leven for hiearachical taxonomies
	 * @uses get_the_ID()
	 * @uses is_taxonomy_hierarchical()
	 * @uses wpsight_get_the_term_list()
	 * @return string|null List of terms or null if taxonomy does not exist
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_terms( $taxonomy = '', $post_id = '', $sep = '', $term_before = '', $term_after = '', $linked = true, $reverse = false ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Set default taxonomy

		if ( empty( $taxonomy ) )
			$taxonomy = 'feature';

		$term_list = wpsight_get_the_term_list( $post_id, $taxonomy, $sep, $term_before, $term_after, $linked, $reverse );

		return apply_filters( 'wpsight_get_listing_terms', $term_list, $post_id, $taxonomy, $sep, $term_before, $term_after, $linked, $reverse );

	}

	/**
	 * get_listing_thumbnail()
	 *
	 * Return a thumbnail of a specific listing.
	 *
	 * @param integer $post_id   Post ID
	 * @param array   $attr      Array of attributes for the thumbnail (for get_the_post_thumbnail())
	 * @param string|bool $formatted CSS class of image container div or false to return wp_get_attachment_image_src()
	 * @uses get_the_ID()
	 * @uses get_the_title()
	 * @uses wp_parse_args()
	 * @uses has_post_thumbnail()
	 * @uses get_the_post_thumbnail()
	 * @uses sanitize_html_class()
	 * @uses get_post_thumbnail_id()
	 * @uses wp_get_attachment_image_src()
	 * @return string|array HTML image tag with container div or array (see wp_get_attachment_image_src())
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_thumbnail( $post_id = '', $size = 'thumbnail', $attr = '', $default = '', $formatted = 'wpsight-listing-thumbnail' ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		if ( $formatted !== false ) {

			$default_attr = array(
				'alt' 	=> the_title_attribute( array( 'post' => $post_id, 'echo' => false ) ),
				'title' => the_title_attribute( array( 'post' => $post_id, 'echo' => false ) )
			);

			$attr = wp_parse_args( $attr, $default_attr );

			// Set default output (dashicon admin-home)
			$default = ! empty( $default ) ? $default : '<span class="dashicons dashicons-admin-home"></span>';

			// Get featured image
			$thumb = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail( $post_id, $size, $attr ) : $default;

			// Set overlay

			$overlay = apply_filters( 'wpsight_listing_thumbnail_overlay', '', $post_id, $size, $attr, $default, $formatted );
			$thumb_overlay = ! empty( $overlay ) ? wp_kses_post( $overlay ) : '';

			$thumb = '<div class="' . sanitize_html_class( $formatted ) . '">' . $thumb_overlay . $thumb . '</div><!-- .' . sanitize_html_class( $formatted ) . ' -->';

		} else {

			$thumb_id = has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : false;

			$thumb = $thumb_id ? wp_get_attachment_image_src( $thumb_id, $size ) : false;

		}

		return apply_filters( 'wpsight_get_listing_thumbnail', $thumb, $post_id, $size, $attr, $default, $formatted );

	}

	/**
	 * get_listing_thumbnail_url()
	 *
	 * Return a thumbnail URL of a specific listing.
	 *
	 * @param integer $post_id Post ID
	 * @param string $size Size of the image (thumbnail, large etc.). Defaults to 'thumbnail'.
	 * @uses wpsight_get_listing_thumbnail()
	 * @return string URL of the thumbnail
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_thumbnail_url( $post_id = '', $size = 'thumbnail' ) {

		// Get attachment
		$thumb = wpsight_get_listing_thumbnail( $post_id, $size, '', '', false );

		// Set false if no thumb
		$result = $thumb ? $thumb[0] : false;

		return apply_filters( 'wpsight_get_listing_thumbnail_url', $result );
	}

	/**
	 * is_listing_sticky()
	 *
	 * Check if a specific listing is sticky
	 * (custom field '_listing_sticky').
	 *
	 * @param integer $post_id Post ID of the corresponding listing (defaults to current post)
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @return bool $result True if _listing_sticky has value, else false
	 *
	 * @since 1.0.0
	 */
	public static function is_listing_sticky( $post_id = '' ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get custom post meta and set result
		$result = get_post_meta( $post_id, '_listing_sticky', true ) ? true : false;

		return apply_filters( 'wpsight_is_listing_sticky', $result, $post_id );

	}

	/**
	 * is_listing_featured()
	 *
	 * Check if a specific listing is featured
	 * (custom field '_listing_featured').
	 *
	 * @param integer $post_id Post ID of the corresponding listing (defaults to current post)
	 * @uses get_the_ID()
	 * @uses get_post_meta()
	 * @return bool $result True if _listing_featured has value, else false
	 *
	 * @since 1.0.0
	 */
	public static function is_listing_featured( $post_id = '' ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get custom post meta and set result
		$result = get_post_meta( $post_id, '_listing_featured', true ) ? true : false;

		return apply_filters( 'wpsight_is_listing_featured', $result, $post_id );

	}

	/**
	 * is_listing_not_available()
	 *
	 * Check if a specific listing item is no longer available
	 * (custom field '_listing_not_available').
	 *
	 * @param integer $post_id Post ID of the corresponding listing (defaults to current post)
	 * @uses result()
	 * @uses get_post_meta()
	 * @uses update_post_meta()
	 * @uses delete_post_meta()
	 * @return bool $result True if _listing_not_available has value, else false
	 *
	 * @since 1.0.0
	 */
	public static function is_listing_not_available( $post_id = '' ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Get old _price_sold_rented value
		$sold_rented = get_post_meta( $post_id, '_price_sold_rented', true );

		if ( ! empty( $sold_rented ) ) {

			// Update new field with old field value
			update_post_meta( $post_id, '_listing_not_available', $sold_rented );

			// Remove old field
			delete_post_meta( $post_id, '_price_sold_rented' );

		}

		// Get custom post meta and set result
		$result = get_post_meta( $post_id, '_listing_not_available', true ) ? true : false;

		return apply_filters( 'wpsight_is_listing_not_available', $result, $post_id );

	}

	/**
	 * is_listing_pending()
	 *
	 * Check if a specific listing has post
	 * status 'pending' or 'pending_payment'.
	 *
	 * @param integer $post_id Post ID of the corresponding listing
	 * @uses get_the_ID()
	 * @uses get_post_status()
	 * @return bool True if post status is 'pending' or 'pending_payment', else false
	 *
	 * @since 1.0.0
	 */
	public static function is_listing_pending( $post_id = '' ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		$pending = array( 'pending', 'pending_payment' );

		// Check listing status and set result
		$result = in_array( get_post_status( $post_id ), $pending ) ? true : false;

		return apply_filters( 'wpsight_is_listing_pending', $result, $post_id );

	}

	/**
	 * is_listing_expired()
	 *
	 * Check if a specific listing has post status 'expired'
	 *
	 * @param integer $post_id Post ID of the corresponding listing
	 * @uses get_the_ID()
	 * @uses get_post_status()
	 * @return bool True if post status is 'expired', else false
	 *
	 * @since 1.0.0
	 */
	public static function is_listing_expired( $post_id = '' ) {

		// Set default post ID

		if ( ! $post_id )
			$post_id = get_the_ID();

		// Check listing status and set result
		$result = get_post_status( $post_id ) == 'expired' ? true : false;

		return apply_filters( 'wpsight_is_listing_expired', $result, $post_id );

	}

	/**
	 * user_can_edit_listing()
	 *
	 * True if an the user can edit a listing.
	 *
	 * @param integer $post_id Post ID of specific listing
	 * @uses get_post()
	 * @uses is_user_logged_in()
	 * @uses current_user_can()
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public static function user_can_edit_listing( $post_id ) {

		$can_edit = true;
		$listing  = get_post( $post_id );

		if ( ! is_user_logged_in() ) {
			$can_edit = false;
		} elseif ( ! current_user_can( 'edit_listing', $post_id ) ) {
			$can_edit = false;
		}

		return apply_filters( 'wpsight_user_can_edit_listing', $can_edit, $post_id );
	}

	/**
	 * delete_listing_previews()
	 *
	 * Delete old expired listing previews if number of days
	 * have passed after last modification and status is preview.
	 *
	 * ##### FUNCTION CALLED BY CRON ####
	 *
	 * @param int $days Number of days after that previews are deleted
	 * @uses $wpdb->prepare()
	 * @uses $wpdb->get_col()
	 * @uses current_time()
	 * @uses strtotime()
	 * @uses date()
	 * @uses wp_trash_post()
	 * @return array|bool Array of post IDs, false if no previews deleted
	 * @see /includes/class-wpsight-post-types.php
	 *
	 * @since 1.0.0
	 */
	public static function delete_listing_previews( $days = '' ) {
		global $wpdb;

		// Set number of days
		$max = ! empty( $days ) ? $days : apply_filters( 'wpsight_delete_listing_previews_days', 30 );

		// Make sure $max is positive integer
		$max = absint( $max );

		// Get old listing previews

		$post_ids = $wpdb->get_col( $wpdb->prepare( "
			SELECT posts.ID FROM {$wpdb->posts} as posts
			WHERE posts.post_type = 'listing'
			AND posts.post_modified < %s
			AND posts.post_status = 'preview'
		", date( 'Y-m-d', strtotime( '-' . $max . ' days', current_time( 'timestamp' ) ) ) ) );

		if ( $post_ids ) {

			// If any, delete them all

			foreach ( $post_ids as $post_id )
				wp_delete_post( $post_id, true );

			return $post_ids;

		}

		return false;

	}

	/**
	 *  search_listing_id()
	 *
	 *  Perform a search in various listings fields for given string
	 *
	 *  @param string $search
	 *  @uses wpsight_get_option()
	 *  @uses wpsight_post_type()
	 *  @uses WP_Query()
	 *  @uses wp_list_pluck()
	 *  @return  mixed Array of post IDs, false if no previews deleted
	 *
	 *  @since 1.0.0
	 */
	public static function search_listing_id( $search ) {

		$prefix = wpsight_get_option( 'listing_id' );

		// Built ID search query

		$id_args = array(
			'post_type'  => wpsight_post_type(),
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'    => '_listing_id',
					'value'   => urlencode( $search ),
					'compare' => '='
				),
				array(
					'key'    => '_property_id',
					'value'   => urlencode( $search ),
					'compare' => '='
				),
				array(
					'key'    => '_listing_id',
					'value'   => $prefix . urlencode( $search ),
					'compare' => '='
				),
				array(
					'key'    => '_property_id',
					'value'   => $prefix . urlencode( $search ),
					'compare' => '='
				)
			)
		);

		// Execute ID search query
		$id_query = new WP_Query( $id_args );

		if ( ! empty( $id_query->posts ) )
			return wp_list_pluck( $id_query->posts, 'ID' );

		return false;

	}

}
