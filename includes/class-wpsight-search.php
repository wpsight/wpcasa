<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Search class
 */
class WPSight_Search {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_action( 'pre_get_posts', array( $this, 'taxonomy_query_vars' ) );
		add_action( 'init', array( $this, 'search_cookie' ) );
	}
	
	/**
	 * taxonomy_query_vars()
	 *
	 * Allow query vars on listing
	 * taxonomy archive pages.
	 *
	 * @uses wpsight_post_type()
	 * @uses get_object_taxonomies()
	 * @uses is_tax()
	 * @uses $query->is_main_query()
	 * @uses $query->set()
	 *
	 * @since 1.0.0
	 */

	public static function taxonomy_query_vars( $query ) {
		
		// Stop if no listing tax or not main query
		
		if( ! is_tax( get_object_taxonomies( wpsight_post_type() ) ) || ! $query->is_main_query() )
			return;
			
		// Only allow order vars	
		$allowed_vars = array( 'orderby', 'order' );
		
		// Check vars
		
		foreach( $allowed_vars as $var ) {
			
			if( isset( $_GET[$var] ) ) {
				
				// Set query vars
				
				if ( $_GET[$var] == 'price' ) {
					$query->set( 'orderby', 'meta_value_num' );
					$query->set( 'meta_key', '_price' );
				} else {				
					$query->set( $var, sanitize_key( $_GET[$var] ) );				
				}
				
			}
			
		}
		
	}
	/**
	 * search_cookie()
	 *
	 * Set cookie for search query on init
	 * action hook when conditions apply.
	 *
	 * @uses wpsight_implode_array()
	 * @uses setcookie()
	 *
	 * @since 1.0.0
	 */
	public static function search_cookie() {

		// Can be deactivated by filter
		$search_cookie = apply_filters( 'wpsight_search_cookie', true );
		
		// Stop if not search or cookie disabled

		if( ! isset( $_GET['keyword'] ) || $search_cookie == false || is_admin() )
			return;
		
		// Get query
		$get_query = array();
			
		// Get all query vars

		foreach( $_GET as $get => $get_v ) {

			if( is_array( $get_v ) )
				$get_v = implode( '|', $get_v );
			
			$get_query[$get] = $get_v;
		}
		
		// Make string from get array		
		$search_query = wpsight_implode_array( ',', $get_query );

		// Set cookie live without page load
		$_COOKIE[WPSIGHT_COOKIE_SEARCH_QUERY] = $search_query;
		
		// Set cookie with comma-separated parameters
	    setcookie( WPSIGHT_COOKIE_SEARCH_QUERY, $search_query, time()+1209600, COOKIEPATH, COOKIE_DOMAIN, false );
	    
	}

	/**
	 * get_search()
	 *
	 * Return search form
	 *
	 * @param array $args Search form arguments
	 * @param array $search_fields Array of search fields
	 * @uses wpsight_get_search_fields()
	 * @uses wpsight_get_search_field()
	 * @uses sanitize_html_class()
	 *
	 * @return array $search Return formatted search form fields
	 *
	 * @since 1.0.0
	 */
	 
	public static function get_search( $args = array(), $search_fields = array() ) {
		
		// Set defaults
		
		$defaults = array(
			'id' 		  => false,
			'class' 	  => 'wpsight-listings-search',
			'orientation' => 'horizontal', // can be vertical
			'action' 	  => '', // Empty action redirects to same page
			'fields' 	  => wpsight_get_search_fields( $search_fields ),
			'advanced'	  => '<span class="listings-search-advanced-toggle">' . __( 'Advanced Search', 'wpsight' ) . '</span>', // Set (bool) false to hide advanced search
			'reset'		  => '<span class="listings-search-reset">' . __( 'Reset Search', 'wpsight' ) . '</span>', // Set (bool) false to hide reset button
		);
		
		// Merge $defaults with $args
		$args = wp_parse_args( $args, $defaults );
		
		// Set form HTML ID
		$html_id = $args['id'] ? 'id="' . sanitize_html_class( $args['id'] ) . '"' : '';
		
		// Set default action from settings

		$page = wpsight_get_option( 'listings_page' );
		$args['action'] = empty( $args['action'] ) && ! empty( $page ) ? get_permalink( absint( $page ) ) : $args['action'];
		
		// Setup search form
		
		$search = '<form method="get" ' . $html_id . ' action="' . esc_url( $args['action'] ) . '" class="' . sanitize_html_class( $args['class'] ) . ' ' . sanitize_html_class( $args['orientation'] ) . '">' . "\n\n";
		
			// Setup advanced search
			$search_advanced = '';
		
			// Loop through fields
			
			foreach( $args['fields'] as $field => $value ) {
				
				// Get search field markup
				$search_field = wpsight_get_search_field( $field, true );
				
				// Separate default from advanced search
				
				if( isset( $value['advanced'] ) && $value['advanced'] === true ) {				
					$search_advanced .= $search_field;				
				} else {				
					$search .= $search_field;				
				}
				
			} // endforeach $fields
					
			// Append advanced search
			
			if( ! empty( $search_advanced ) && $args['advanced'] !== false ) {
				
				// Wrap advanced search
				$search .= '<div class="listings-search-advanced">' . "\n\n" . $search_advanced . '</div><!-- .listings-search-advanced -->' . "\n";
				
				// Add toggle button
				$search .= $args['advanced'];
				
			}
			
			// Append reset button
			
			if( $args['reset'] !== false ) {
				
				// Add toggle button
				$search .= $args['reset'];
				
			}
			
			// Add current page_id to GET parameters if permalinks are not pretty

			if( isset( $_GET['page_id'] ) )
				$search .= "\n" . '<input name="page_id" type="hidden" value="' . absint( $_GET['page_id'] ) . '" />' . "\n\n";
		
		// Close form tag
		$search .= '</form><!-- .' . sanitize_html_class( $args['class'] ) . ' -->' . "\n\n";
		
		return apply_filters( 'wpsight_get_search', $search, $args, $search_fields );
		
	}

	/**
	 * get_search_fields()
	 *
	 * Return search form fields array
	 *
	 * @param array $fields Array of search fields (empty array by default)
	 * @uses wpsight_details()
	 * @uses wpsight_offers()
	 * @uses wpsight_sort_array_by_priority()
	 *
	 * @return array $fields Array of search form fields
	 *
	 * @since 1.0.0
	 */
	 
	public static function get_search_fields( $fields = array() ) {
		
		// Get listing details
		$details = wpsight_details();
		
		// Set default form fields
		
		$defaults = array(
			
			'keyword' => array(
				'label' 		=> __( 'Keyword or Listing ID', 'wpsight' ) . '&hellip;',
				'type' 			=> 'text',
				'class'			=> 'width-3-4',
		    	'priority'		=> 10
			),
			
			'submit' => array(
				'label' 		=> __( 'Search', 'wpsight' ),
				'type' 			=> 'submit',
				'class'			=> 'width-1-4',
		    	'priority'		=> 20
			),

			'offer' => array(
				'label' 		=> __( 'Offer', 'wpsight' ),
				'key'			=> '_price_offer',
				'data' 			=> wpsight_offers(),
				'type' 			=> 'select',
				'data_compare' 	=> '=',
				'class'			=> 'width-1-5',
		    	'priority'		=> 30
			),

			'location' => array(
				'data' 			=> array(
					// wp_dropdown_categories() options
					'taxonomy'			=> 'location',
			    	'show_option_none' 	=> __( 'Location', 'wpsight' ),
			    	'option_none_value' => '',
			    	'hierarchical'		=> 1,
			    	'orderby'         	=> 'ID',
					'order'           	=> 'ASC'
				),
				'type' 			=> 'taxonomy_select',
				'class'			=> 'width-1-5',
		    	'priority'		=> 40
			),

			'listing-type' => array(
				'data' 			=> array(
					// wp_dropdown_categories() options
					'taxonomy'			=> 'listing-type',
			    	'show_option_none' 	=> __( 'Type', 'wpsight' ),
			    	'option_none_value' => '',
			    	'hierarchical'		=> 1,
			    	'orderby'         	=> 'ID', 
					'order'           	=> 'ASC'
				),
				'type' 			=> 'taxonomy_select',
				'class'			=> 'width-1-5',
		    	'priority'		=> 50
			),

			$details['details_1']['id'] => array(
				'label' 		=> $details['details_1']['label'],
				'key'			=> '_details_1',
				'data'			=> $details['details_1']['data'],
				'type'  		=> 'select',
				'data_compare' 	=> '>=',
				'class'			=> 'width-1-5',
		    	'priority'		=> 60
			),

			$details['details_2']['id'] => array(
				'label' 		=> $details['details_2']['label'],
				'key'			=> '_details_2',
				'data'			=> $details['details_2']['data'],
				'type'  		=> 'select',
				'data_compare' 	=> '>=',
				'class'			=> 'width-1-5',
		    	'priority'		=> 70
			),

			'min' => array(
				'label' 		=> __( 'Price (min)', 'wpsight' ),
				'key'			=> '_price',
				'type' 			=> 'text',
				'data_compare' 	=> '>=',
				'data_type' 	=> 'numeric',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 90
			),

			'max' => array(
				'label' 		=> __( 'Price (max)', 'wpsight' ),
				'key'			=> '_price',
				'type' 			=> 'text',
				'data_compare' 	=> '<=',
				'data_type' 	=> 'numeric',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 100
			),

			'orderby' => array(
				'label'			=> __( 'Order by', 'wpsight' ),
				'type' 			=> 'select',
				'data' 			=> array(
					'date'  => __( 'Date', 'wpsight' ),
					'price' => __( 'Price', 'wpsight' ),
					'title'	=> __( 'Title', 'wpsight' )
				),
				'default'		=> 'date',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 110
			),

			'order' => array(
				'label'			=> __( 'Order', 'wpsight' ),
				'type' 			=> 'select',
				'data' 			=> array(
					'asc'  => __( 'asc', 'wpsight' ),
					'desc' => __( 'desc', 'wpsight' )
				),
				'default'		=> 'desc',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 120
			),
			
			'feature' => array(
				'label'			=> '',
				'data' 			=> array(
					// get_terms() options
					'taxonomy'			=> 'feature',
			    	'orderby'         	=> 'count', 
					'order'           	=> 'DESC',
					'operator'			=> 'AND', // can be OR
					'number'			=> 8
				),
				'type' 			=> 'taxonomy_checkbox',
				'advanced'		=> true,
				'class'			=> 'width-auto',
		    	'priority'		=> 130
			)
			
		);
		
		// Set $defaults when $fields is empty
		
		if( empty( $fields ) )
			$fields = $defaults;
		
		// Apply filter and sort array by priority  
		return wpsight_sort_array_by_priority( apply_filters( 'wpsight_get_search_fields', $fields, $defaults ) );
		
	}

	/**
	 * get_search_field()
	 *
	 * Return specific search form field array or HTML markup
	 *
	 * @param string $field Key of the search field
	 * @param bool $formatted Set false to return search field array or true for formatted markup (defaults to false)
	 * @uses wpsight_get_search_fields()
	 * @uses wpsight_get_query_var_by_detail()
	 * @uses wpsight_cookie_query()
	 *
	 * @return array|string|bool $fields[$key] Array or HTML markup of specific search form field or false if key does not exist
	 *
	 * @since 1.0.0
	 */
	 
	public static function get_search_field( $field, $formatted = false ) {

		// Get all search fields
		$fields = wpsight_get_search_fields();
		
		// Return unformatted search field array
		
		if( $formatted === false ) {
		
			if( isset( $fields[$field] ) )
				return apply_filters( 'wpsight_get_search_field', $fields[$field], $field, $formatted );
			
			return false;
		
		// Return formatted search field markup
		
		} else {
			
			// Setup search field
			$search_field = '';
			
			// Check default value
			$default = isset( $fields[$field]['default'] ) ? $fields[$field]['default'] : false;
			
			// Check query var
			$field_var = wpsight_get_query_var_by_detail( $field );
			
			if( isset( $_GET[$field_var] ) && is_array( $_GET[$field_var] ) )
				$_GET[$field_var] = array_map( 'esc_attr', $_GET[$field_var] );
			
			// Set field value from cookie
			
			if( wpsight_cookie_query( $field ) ) {
				$field_value = wpsight_cookie_query( $field );
				if( strpos( wpsight_cookie_query( $field ), '|' ) )
					$field_value = explode( '|', wpsight_cookie_query( $field ) );
			}
			
			// If empty, set field value from GET
			
			if( isset( $_GET[$field_var] ) )
				$field_value = is_array( $_GET[$field_var] ) ? array_map( 'esc_attr', $_GET[$field_var] ) : sanitize_key( $_GET[$field_var] );
			
			// If still empty, set field value to default
				
			if( ! isset( $field_value ) )
				$field_value = $default;
			
			// Check HTML class
			$class = isset( $fields[$field]['class'] ) ? sanitize_html_class( $fields[$field]['class'] ) : false;
			
			// Create field markup
			    
			$search_field .= '<div class="listings-search-field listings-search-field-' . $fields[$field]['type'] . ' listings-search-field-' . $field . ' ' . $class . '">' . "\n";
			
				// Create field type text
				
				if( $fields[$field]['type'] == 'text' ) {
					
					$search_field .= "\t" . '<input class="listing-search-' . $field . ' text" title="' . $fields[$field]['label'] . '" name="' . $field . '" type="text" value="' . $field_value . '" placeholder="' . $fields[$field]['label'] . '" />' . "\n";
					
				// Create field type submit
				
				} elseif( $fields[$field]['type'] == 'submit' ) {
					
					$search_field .= "\t" . '<input type="submit" value="' . $fields[$field]['label'] . '">' . "\n";
					
				// Create field type select
					
				} elseif( $fields[$field]['type'] == 'select' ) {
					
					if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) {
					
						$search_field .= '<select class="listing-search-' . $field . ' select" name="' . wpsight_get_query_var_by_detail( $field ) . '">' . "\n";
						
							// Prepend empty option with label
							$search_field .= "\t" . '<option value="">' . $fields[$field]['label'] . '</option>' . "\n";
							
							foreach( $fields[$field]['data'] as $k => $v ) {
								
								$data_default = ( isset( $fields[$field]['default'] ) && $fields[$field]['default'] == $k ) ? 'true' : 'false';
							
								if( ! empty( $k ) ) {								
									$search_field .= "\t" . '<option value="' . $k . '"' . selected( $k, sanitize_key( $field_value ), false ) . ' data-default="' . $data_default . '">' . $v . '</option>' . "\n";
								}
						
							}
						
						$search_field .= '</select><!-- .listing-search-' . $field . ' -->' . "\n";
					
					} // endif $fields[$field]['data']
					
				// Create field type radio
					
				} elseif( $fields[$field]['type'] == 'radio' ) {
					
					if( ! empty( $fields[$field]['label'] ) )
				    	$search_field .= "\t" . '<label class="radiogroup" for="' . $field . '">' . $fields[$field]['label'] . '</label>' . "\n";
				    
				    foreach( $fields[$field]['data'] as $k => $v ) {
				    
				    	$data_default = ( isset( $fields[$field]['default'] ) && $fields[$field]['default'] == $k ) ? 'true' : 'false';
				    
				    	$search_field .= "\t" . '<label class="radio"><input type="radio" name="' . wpsight_get_query_var_by_detail( $field ) . '" value="' . $k . '"' . checked( $k, sanitize_key( $field_value ), false ) . ' data-default="' . $data_default . '"/> ' . $v . '</label>' . "\n";
				    	
				    }
				    
				// Create field type checkbox
				
				} elseif( $fields[$field]['type'] == 'checkbox' ) {
					
					if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) {
						
						if( ! empty( $fields[$field]['label'] ) )
				    		$search_field .= "\t" . '<label class="checkboxgroup" for="' . $field . '">' . $fields[$field]['label'] . '</label>' . "\n";
						
						// Loop through data
						
						foreach( $fields[$field]['data'] as $k => $v ) {
							
							if( is_array( $field_value ) ) {

								$field_option_key = array_search( $k, $field_value );
								
								$field_option_value = $field_option_key !== false ? $field_value[$field_option_key] : false;

							} else {

								$field_option_value = $field_value;

							}
								
							$search_field .= "\t" . '<label class="checkbox"><input type="checkbox" name="' . $field . '[' . $k . ']" value="' . $k . '" ' . checked( $k, $field_option_value, false ) . '>' . $v . '</label>' . "\n";
							
						} // endforeach get_terms()
					
					}
				    
				// Create field type taxonomy (select)
					
				} elseif( $fields[$field]['type'] == 'taxonomy_select' ) {
					
					if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) {
						
						$dropdown_defaults = array(
				    		'echo'				=> 0,
				    		'name'				=> $field,
				    		'class'           	=> 'listing-search-' . $field . ' select',
				    		'selected'			=> $field_value,
				    		'value_field'       => 'slug',
							'hide_if_empty'   	=> false,
							'cache'				=> true					
						);
						
						// Merge with form field $fields[$field]['data']
				    	$dropdown_args = wp_parse_args( $fields[$field]['data'], $dropdown_defaults );
					
						$search_field .= wp_dropdown_categories( $dropdown_args );
					
					}
					
				// Create field type taxonomy (checkbox)
					
				} elseif( $fields[$field]['type'] == 'taxonomy_checkbox' ) {
					
					if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) {
						
						$checklist_defaults = array(
				    		'hide_empty'		=> 1
						);
						
						if( ! empty( $fields[$field]['label'] ) )
				    		$search_field .= "\t" . '<label class="checkboxgroup" for="' . $field . '">' . $fields[$field]['label'] . '</label>' . "\n";
						
						// Merge with form field $fields[$field]['data']
				    	$checklist_args = wp_parse_args( $fields[$field]['data'], $checklist_defaults );
						
						// Loop through terms
						
						foreach( get_terms( $fields[$field]['data']['taxonomy'], $checklist_args ) as $k => $v ) {
								
							if( is_array( $field_value ) ) {

								$field_option_key = array_search( $v->slug, $field_value );
								
								$field_option_value = $field_option_key !== false ? $field_value[$field_option_key] : false;

							} else {

								$field_option_value = $field_value;

							}
								
							$search_field .= "\t" . '<label class="checkbox"><input type="checkbox" name="' . $field . '[' . $v->term_id . ']" value="' . $v->slug . '" ' . checked( $v->slug, $field_option_value, false ) . '>' . $v->name . '</label>' . "\n";
							
						} // endforeach get_terms()
					
					}
					
				}
			
			// Close HTML markup
			$search_field .= '</div><!-- .listings-search-field .listings-search-field-' . $field . ' -->';
			
			return apply_filters( 'wpsight_get_search_field', $search_field, $field, $formatted );
			
		}
		
	}

	/**
	 * cookie_query()
	 *
	 * Return query saved in cookie.
	 *
	 * @param string $field Key of specific field
	 * @return array|string Array of field vars or value of specific field
	 *
	 * @since 1.0.0
	 */

	public static function cookie_query( $field = false ) {
		
		if( ! isset( $_COOKIE[WPSIGHT_COOKIE_SEARCH_QUERY] ) )
			return false;
		
		$cookie_query = wpsight_explode_array( ',', $_COOKIE[WPSIGHT_COOKIE_SEARCH_QUERY] );
		
		if( $field === false )
			return $cookie_query;
		
		// Get field query var
		$field_var = wpsight_get_query_var_by_detail( $field );
		
		if( isset( $cookie_query[$field_var] ) )
			return $cookie_query[$field_var];
		
		return false;
		
	}

}
