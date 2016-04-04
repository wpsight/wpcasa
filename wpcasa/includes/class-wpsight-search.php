<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Search class
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
	 * @uses wpsight_taxonomies()
	 * @uses is_tax()
	 * @uses $query->is_main_query()
	 * @uses $query->set()
	 *
	 * @since 1.0.0
	 */
	public static function taxonomy_query_vars( $query ) {
		
		// Stop if no listing tax or not main query
		
		if( ! is_tax( wpsight_taxonomies( 'names' ) ) || ! $query->is_main_query() )
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
	 * @uses wpsight_get_template()
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
			'advanced'	  => '<span class="listings-search-advanced-toggle">' . __( 'Advanced Search', 'wpcasa' ) . '</span>', // Set (bool) false to hide advanced search
			'reset'		  => '<span class="listings-search-reset">' . __( 'Reset Search', 'wpcasa' ) . '</span>', // Set (bool) false to hide reset button
		);
		
		// Merge $defaults with $args
		$args = wp_parse_args( $args, $defaults );
		
		// Set form HTML ID
		$args['id'] = $args['id'] ? ' id="' . sanitize_html_class( $args['id'] ) . '"' : '';
		
		// Set default action from settings

		$page = wpsight_get_option( 'listings_page' );
		$args['action'] = empty( $args['action'] ) && ! empty( $page ) ? get_permalink( absint( $page ) ) : $args['action'];
		
		/**
		 * Loop through search fields and
		 * separate them into default
		 * and advanced search.
		 */

		// Setup default search
		$search_default = '';
		
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
				$search_default .= $search_field;				
			}
			
		} // endforeach $fields
		
		// Setup search form with template
		
		ob_start();
		
		wpsight_get_template( 'search-form.php', array( 'args' => $args, 'search_default' => $search_default, 'search_advanced' => $search_advanced ) );
		
		return apply_filters( 'wpsight_get_search', ob_get_clean(), $args, $search_fields, $search_default, $search_advanced );
		
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
				'label' 		=> __( 'Keyword or Listing ID', 'wpcasa' ) . '&hellip;',
				'type' 			=> 'text',
				'class'			=> 'width-3-4',
		    	'priority'		=> 10
			),			
			'submit' => array(
				'label' 		=> __( 'Search', 'wpcasa' ),
				'type' 			=> 'submit',
				'class'			=> 'width-1-4',
		    	'priority'		=> 20
			),
			'offer' => array(
				'label' 		=> __( 'Offer', 'wpcasa' ),
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
			    	'show_option_none' 	=> __( 'Location', 'wpcasa' ),
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
			    	'show_option_none' 	=> __( 'Type', 'wpcasa' ),
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
				$field_value = is_array( $_GET[ $field_var ] ) ? array_map( 'esc_attr', $_GET[ $field_var ] ) : esc_attr( $_GET[ $field_var ] );
			
			// If still empty, set field value to default
				
			if( ! isset( $field_value ) )
				$field_value = $default;
			
			// Check HTML class
			$class = isset( $fields[$field]['class'] ) ? esc_attr( $fields[$field]['class'] ) : false;
			
			// Get corresponding field template
			
			ob_start();
			wpsight_get_template( 'search-fields/field-' . $fields[$field]['type'] . '.php', array( 'fields' => $fields, 'field' => $field, 'field_value' => $field_value, 'class' => $class ) );
			
			$search_field = trim( ob_get_clean() );
			
			return apply_filters( 'wpsight_get_search_field', $search_field, $field, $formatted );
			
		}
		
	}

	/**
	 * cookie_query()
	 *
	 * Return query saved in cookie.
	 *
	 * @param string $field Key of specific field
	 * @uses wpsight_explode_array()
	 * @uses wpsight_get_query_var_by_detail()
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
