<?php
/**
 *	WPSight helper functions
 *	
 *	@package WPSight \ Functions
 */

/**
 *	wpsight_post_type()
 *	
 *	Helper function that returns the
 *	post type used in the framework.
 *	
 *	@return	string
 *	
 *	@since 1.0.0
 */
function wpsight_post_type() {
	return WPSight_Helpers::post_type();
}

/**
 *	wpsight_taxonomies()
 *	
 *	Helper function that returns the
 *	taxonomies used in the framework.
 *	
 *	@return	array
 *	
 *	@since 1.0.0
 */
if( ! function_exists( 'wpsight_taxonomies' ) ) {
	
	function wpsight_taxonomies( $output = 'objects' ) {
		return WPSight_Helpers::taxonomies( $output );
	}

}

/**
 *	wpsight_is_listing_single()
 *	
 *	Helper function that checks if
 *	we are on a single listing page.
 *	
 *	@uses	wpsight_post_type()
 *	@return	bool
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_single() {
	return WPSight_Helpers::is_listing_single();
}

/**
 *	wpsight_is_listing_agent_archive()
 *	
 *	Helper function that checks if
 *	we are on a listing agent archive page.
 *	
 *	@uses	WPSight_Helpers::is_listing_agent_archive()
 *	@return	bool
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_agent_archive( $query = null ) {
	return WPSight_Helpers::is_listing_agent_archive( $query );
}

/**
 *	wpsight_is_listings_archive()
 *	
 *	Helper function that checks if
 *	we are on a listing archive page.
 *	
 *	@uses	WPSight_Helpers::is_listings_archive()
 *	@return	bool
 *	
 *	@since 1.0.0
 */
function wpsight_is_listings_archive( $query = null ) {
	return WPSight_Helpers::is_listings_archive( $query );
}

/**
 *	wpsight_get_option()
 *	
 *	Return theme option value.
 *	
 *	@param 	string		$name Key of the WPSight option
 *	@param 	bool|string	Set (bool) true to return default from options array or string
 *	@uses 	WPSight_Helpers::get_option()
 *	@return bool|string	False if no value was found or option value as string
 *	
 *	@since 	1.0.0
 */
function wpsight_get_option( $name, $default = '' ) {
	return WPSight_Helpers::get_option( $name, $default );
}

/**
 *	wpsight_add_option()
 *	
 *	Add a specific WPSight option
 *	
 *	@param	string	$name	Key of the option to add
 *	@param	mixed	$value	Value of the option to add
 *	@uses	WPSight_Helpers::add_option()
 *	
 *	@since 1.0.0
 */
function wpsight_add_option( $name, $value ) {
	return WPSight_Helpers::add_option( $name, $value );    
}

/**
 *	wpsight_delete_option()
 *	
 *	Delete a specific WPSight option
 *	
 *	@param	string	$name	Key of the option to delete
 *	@uses	WPSight_Helpers::delete_option()
 *	
 *	@since 1.0.0
 */
function wpsight_delete_option( $name ) {
	return WPSight_Helpers::delete_option( $name );
}

/**
 *	wpsight_options_defaults()
 *	
 *	Get array of options with default values
 *	
 *	@uses	WPSight_Helpers::options_defaults()
 *	@see	wpsight-admin.php
 *	
 *	@since 1.0.0
 */
function wpsight_options_defaults() {
	return WPSight_Helpers::options_defaults();
}

/**
 *	wpsight_get_tax_name()
 *	
 *	Helper function to get taxonomy name
 *	
 *	@uses	WPSight_Helpers::get_tax_name()
 *	
 *	@since 1.0.0
 */
function wpsight_get_tax_name() {	
	return WPSight_Helpers::get_tax_name();
}

/**
 *	wpsight_format_content()
 *	
 *	Helper function to replace the_content filter
 *	
 *	@param	string	$content	Content to be formatted
 *	@uses	WPSight_Helpers::format_content()
 *	
 *	@since 1.0.0
 */
function wpsight_format_content( $content ) {
	return WPSight_Helpers::format_content( $content );
}

/**
 *	wpsight_dashes()
 *	
 *	Helper function to convert underscores to dashes
 *	
 *	@uses	WPSight_Helpers::dashes()
 *	
 *	@since 1.0.0
 */
function wpsight_dashes( $string ) {
	return WPSight_Helpers::dashes( $string );
}

/**
 *	wpsight_underscores()
 *	
 *	Helper function to convert dashes to underscores
 *	
 *	@uses	WPSight_Helpers::underscores()
 *	
 *	@since 1.0.0
 */
function wpsight_underscores( $string ) {
	return WPSight_Helpers::underscores( $string );
}

/**
 *	array_empty()
 *	
 *	Helper function to check multi-dimensional arrays
 *	
 *	@uses	WPSight_Helpers::array_empty()
 *	
 *	@since 1.0.0
 */
function wpsight_array_empty( $mixed ) {
   	return WPSight_Helpers::array_empty( $mixed );
}

/**
 *	wpsight_in_multiarray()
 *	
 *	Helper function to check multi-dimensional arrays
 *	
 *	@uses	WPSight_Helpers::in_multiarray()
 *	
 *	@since 1.0.0
 */
function wpsight_in_multiarray( $elem, $array ) {
	return WPSight_Helpers::in_multiarray( $elem, $array );
}

/**
 *	wpsight_sort_array_by_priority()
 *	
 *	Helper function to sort array by position key
 *	
 *	@param	array	$array	Array to be sorted
 *	@param	mixed	$order	Sort options
 *	@uses	WPSight_Helpers::sort_array_by_priority()
 *	@see	http://docs.php.net/manual/en/function.array-multisort.php
 *	@return	array	Sorted array
 *	
 *	@since 1.0.0
 */
function wpsight_sort_array_by_priority( $array = array(), $order = SORT_NUMERIC ) {
	return WPSight_Helpers::sort_array_by_priority( $array, $order );
}

// Ensure backwards compatibility with wpsight_sort_array_by_position()
function wpsight_sort_array_by_position( $array = array(), $order = SORT_NUMERIC ) {
	return wpsight_sort_array_by_priority( $array, $order );
}

/**
 *	wpsight_implode_array()
 *	
 *	Implode an array with the key and value pair
 *	
 *	@uses	WPSight_Helpers::implode_array()
 *	
 *	@since 1.0.0
 */
function wpsight_implode_array( $glue, $arr ) {
	return WPSight_Helpers::implode_array( $glue, $arr );
}

/**
 *	wpsight_explode_array()
 *	
 *	Explode string to associative array
 *	
 *	@uses	WPSight_Helpers::explode_array()
 *	
 *	@since 1.0.0
 */
function wpsight_explode_array( $glue, $str ) {
	return WPSight_Helpers::explode_array( $glue, $str );
}

/**
 *	wpsight_generate_css()
 *	
 *	Helper function to display theme_mods CSS
 *	
 *	@param	string	$selector	CSS selector
 *	@param	string	$style		CSS style
 *	@param	string	$mod_name	Name of theme_mod
 *	@param	string	$prefix
 *	@param	string	$postfix
 *	@param	bool	$echo		Echo (true) or return (false)
 *	@uses WPSight_Helpers::generate_css()
 *	
 *	@since 1.0.0
 */
function wpsight_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false ) {
	return WPSight_Helpers::generate_css( $selector, $style, $mod_name, $prefix, $postfix, $echo );
}

/**
 *	wpsight_cast_decimal_precision()
 *	
 *	Helper function to allow
 *	DECIMAL precision (hacky)
 *	
 *	@param	string	$sql	SQL
 *	@uses	WPSight_Helpers::cast_decimal_precision()
 *	
 *	@since 1.0.0
 */
function wpsight_cast_decimal_precision( $sql ) {
	return WPSight_Helpers::cast_decimal_precision( $sql );
}

/**
 *	Helper functions to return taxonomy
 *	terms ordered by hierarchy
 *	
 *	@param	integer	$post_id Post ID of specific listing
 *	@param	string	$taxonomy Taxonomy
 *	@param	string	$sep Separator between terms
 *	@param	string	$term_before String before each term
 *	@param	string	$term_after String after each term
 *	@param	bool	$linked Link terms to their term archives
 *	@param	bool	$reverse Reverse order of terms
 *	@uses	WPSight_Helpers::get_the_term_list()
 *	
 *	@since 1.0.0
 */
function wpsight_get_the_term_list( $post_id, $taxonomy, $sep = '', $term_before = '', $term_after = '', $linked = true, $reverse = false ) {
	return WPSight_Helpers::get_the_term_list( $post_id, $taxonomy, $sep, $term_before, $term_after, $linked, $reverse );
}

function wpsight_sort_taxonomies_by_parents( $data, $parent_id = 0 ) {
	return WPSight_Helpers::get_the_term_list( $data, $parent_id );
}

function wpsight_get_the_term_list_links( $taxonomy, $data, $term_before = '', $term_after = '', $linked = 'true' ) {
	return WPSight_Helpers::get_the_term_list_links( $taxonomy, $data, $term_before, $term_after, $linked );
}

/**
 *	wpsight_get_attachment_id_by_url()
 *	
 *	Helper functions to get attachment ID by URL.
 *	
 *	@param	string	$url	Image attachment URL
 *	@uses	WPSight_Helpers::get_attachment_id_by_url()
 *	@credit	https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *	
 *	@since 1.0.0
 */
function wpsight_get_attachment_id_by_url( $url ) {
	return WPSight_Helpers::get_attachment_id_by_url( $url );
}

/**
 *	wpsight_get_attachment_by_url()
 *	
 *	Helper functions to get attachment by URL.
 *	
 *	@param	string			$url	Image attachment URL
 *	@param	string|array	WordPress image size or custom with and height in array
 *	@uses WPSight_Helpers::get_attachment_by_url()
 *	@credit https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *	
 *	@since 1.0.0
 */
function wpsight_get_attachment_by_url( $url, $size = 'thumbnail' ) {
	return WPSight_Helpers::get_attachment_by_url( $url, $size );
}

/**
 *	wpsight_maybe_update_gallery()
 *	
 *	Helper function to update image gallery.
 *	
 *	@param	integer	$listing_id	Post ID of the corresponding listing
 *	@uses	WPSight_Helpers::maybe_update_gallery()
 *	
 *	@since 1.0.0
 */
function wpsight_maybe_update_gallery( $listing_id ) {
	return WPSight_Helpers::maybe_update_gallery( $listing_id );
}

/**
 *	wpsight_user_can_edit_listing_id()
 *	
 *	Check if current user can edit the listing ID.
 *	
 *	@uses	WPSight_Helpers::user_can_edit_listing_id
 *	@return	bool True if user is eligible, else false
 *	
 *	@since 1.0.0
 */
function wpsight_user_can_edit_listing_id() {
	return WPSight_Helpers::user_can_edit_listing_id();
}
