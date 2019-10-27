<?php
/**
 *	WPSight search functions
 *	
 *	@package WPSight \ Functions
 */
 
/**
 *	wpsight_search()
 *	
 *	Echo wpsight_get_search()
 *	
 *	@param	array	$args			Arguments for wpsight_get_search()
 *	@param	array	$search_fields	Array of search fields
 *	@uses	wpsight_get_search()
 *	
 *	@since 1.0.0
 */
function wpsight_search( $args = array(), $search_fields = array() ) {
	echo wpsight_get_search( $args, $search_fields );	
}

/**
 *	wpsight_get_search()
 *	
 *	Return search form
 *	
 *	@param	array	$args			Search form arguments
 *	@param	array	$search_fields	Array of search fields
 *	@uses	return	WPSight_Search::get_search()
 *	@return	array	$search			Return formatted search form fields
 *	
 *	@since 1.0.0
 */
function wpsight_get_search( $args = array(), $search_fields = array() ) {
	return WPSight_Search::get_search( $args, $search_fields );
}

/**
 *	wpsight_get_search_fields()
 *	
 *	Return search form fields array
 *	
 *	@param	array	$fields	Array of search fields (empty array by default)
 *	@uses	WPSight_Search::get_search_fields()
 *	@return	array	$fields	Array of search form fields
 *	
 *	@since 1.0.0
 */
function wpsight_get_search_fields( $fields = array() ) {
	return WPSight_Search::get_search_fields( $fields );
}

/**
 *	wpsight_get_search_field()
 *	
 *	Return specific search form field array or HTML markup
 *	
 *	@param	string				$field			Key of the search field
 *	@param	bool				$formatted		Set false to return search field array or true for formatted markup (defaults to false)
 *	@uses	WPSight_Search::get_search_field()
 *	@return	array|string|bool	$fields[$key]	Array or HTML markup of specific search form field or false if key does not exist
 *	
 *	@since 1.0.0
 */
function wpsight_get_search_field( $field, $formatted = false ) {
	return WPSight_Search::get_search_field( $field, $formatted );
}

/**
 *	wpsight_cookie_query()
 *	
 *	Return query saved in cookie.
 *	
 *	@param	string			$field Key of specific field
 *	@return	array|string	Array of field vars or value of specific field
 *	@uses	WPSight_Search::cookie_query()
 *	
 *	@since 1.0.0
 */
function wpsight_cookie_query( $field = false ) {
	return WPSight_Search::cookie_query( $field );	
}
