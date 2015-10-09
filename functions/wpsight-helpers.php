<?php
/**
 * wpSight helper functions
 *
 * @package WPSight
 * @subpackage Functions
 */

/**
 * wpsight_post_type()
 *
 * Helper function that returns the
 * post type used in the framework.
 *
 * @return string
 * @since 1.0.0
 */
function wpsight_post_type() {
	return WPSight_Helpers::post_type();
}

/**
 * wpsight_is_listing_single()
 *
 * Helper function that checks if
 * we are on a single listing page.
 *
 * @uses wpsight_post_type()
 * @return bool
 *
 * @since 1.0.0
 */
function wpsight_is_listing_single() {
	return WPSight_Helpers::is_listing_single();
}

/**
 * wpsight_is_listing_agent_archive()
 *
 * Helper function that checks if
 * we are on a listing agent archive page.
 *
 * @uses wpsight_post_type()
 * @uses is_admin()
 * @uses $query->is_main_query()
 * @return bool
 *
 * @since 1.0.0
 */
function wpsight_is_listing_agent_archive( $query = null ) {
	return WPSight_Helpers::is_listing_agent_archive( $query );
}

/**
 * wpsight_is_listing_archive()
 *
 * Helper function that checks if
 * we are on a listing archive page.
 *
 * @uses wpsight_post_type()
 * @uses is_admin()
 * @uses $query->is_main_query()
 * @return bool
 *
 * @since 1.0.0
 */
function wpsight_is_listing_archive( $query = null ) {
	return WPSight_Helpers::is_listing_archive( $query );
}

/**
 * wpsight_get_option()
 *
 * Return theme option value.
 * 
 * @param 	string $name Key of the wpSight option
 * @param 	bool|string Set (bool) true to return default from options array or string
 * @uses 	get_option()
 * @uses 	wpsight_options_defaults()
 * @return 	bool|string False if no value was found or option value as string
 * @since 	1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_option' ) ) {

	function wpsight_get_option( $name, $default = '' ) {
		return WPSight_Helpers::get_option( $name, $default );
	}

}

/**
 * wpsight_add_option()
 *
 * Add a specific wpSight option
 *
 * @param string $name Key of the option to add
 * @param mixed $value Value of the option to add
 * @uses get_option()
 * @uses update_option()
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_add_option' ) ) {

	function wpsight_add_option( $name, $value ) {
		return WPSight_Helpers::add_option( $name, $value );    
	}

}

/**
 * wpsight_delete_option()
 *
 * Delete a specific wpSight option
 *
 * @param string $name Key of the option to delete
 * @uses get_option()
 * @uses update_option()
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_delete_option' ) ) {

	function wpsight_delete_option( $name ) {
		return WPSight_Helpers::delete_option( $name );
	}

}

/**
 * wpsight_options_defaults()
 *
 * Get array of options with default values
 *
 * @see wpsight-admin.php
 * @since 1.0.0
 */

function wpsight_options_defaults() {
	return WPSight_Helpers::options_defaults();
}

/**
 * Helper function to get taxonomy name
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_tax_name' ) ) {

	function wpsight_get_tax_name() {	
		return WPSight_Helpers::get_tax_name();
	}

}

/**
 * Helper function to replace
 * the_content filter
 *
 * @param string $content Content to be formatted
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_format_content' ) ) {

	function wpsight_format_content( $content ) {
		return WPSight_Helpers::format_content( $content );
	}

}

/**
 * Helper function to convert
 * underscores to dashes
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_dashes' ) ) {

	function wpsight_dashes( $string ) {
		return WPSight_Helpers::dashes( $string );
	}

}

/**
 * Helper function to convert
 * dashes to underscores
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_underscores' ) ) {

	function wpsight_underscores( $string ) {
		return WPSight_Helpers::underscores( $string );
	}

}

/**
 * Helper function to
 * check multi-dimensional arrays
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'array_empty' ) ) {

	function array_empty( $mixed ) {
	   	return WPSight_Helpers::array_empty( $mixed );
	}

}

// Make function pluggable/overwritable
if ( ! function_exists( 'in_multiarray' ) ) {

	function in_multiarray( $elem, $array ) {
		return WPSight_Helpers::in_multiarray( $elem, $array );
	}

}

/**
 * wpsight_sort_array_by_priority()
 *
 * Helper function to sort array by position key
 *
 * @param array $array Array to be sorted
 * @param mixed $order Sort options
 * @see http://docs.php.net/manual/en/function.array-multisort.php
 * @return array Sorted array
 *
 * @since 1.1
 */

function wpsight_sort_array_by_priority( $array = array(), $order = SORT_NUMERIC ) {
	return WPSight_Helpers::sort_array_by_priority( $array, $order );
}

// Ensure backwards compatibility with wpsight_sort_array_by_position()

function wpsight_sort_array_by_position( $array = array(), $order = SORT_NUMERIC ) {
	return wpsight_sort_array_by_priority( $array, $order );
}

/**
 * Implode an array with the key and value pair
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_implode_array' ) ) {

	function wpsight_implode_array( $glue, $arr ) {
		return WPSight_Helpers::implode_array( $glue, $arr );
	}

}

/**
 * Explode string to associative array
 *
 * @since 1.0.0
 */
 
// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_explode_array' ) ) {

	function wpsight_explode_array( $glue, $str ) {
		return WPSight_Helpers::explode_array( $glue, $str );
	}

}

/**
 * Helper function to display
 * theme_mods CSS
 *
 * @since 1.2
 */
 
function wpsight_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false ) {
	return WPSight_Helpers::generate_css( $selector, $style, $mod_name, $prefix, $postfix, $echo );
}

/**
 * Helper function to allow
 * DECIMAL precision (hacky)
 *
 * @since 1.2
 */

function wpsight_cast_decimal_precision( $sql ) {
	return WPSight_Helpers::cast_decimal_precision( $sql );
}

/**
 * Helper functions to return taxonomy
 * terms ordered by hierarchy
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_the_term_list' ) ) {

	function wpsight_get_the_term_list( $post_id, $taxonomy, $sep = '', $term_before = '', $term_after = '', $linked = true, $reverse = false ) {
		return WPSight_Helpers::get_the_term_list( $post_id, $taxonomy, $sep, $term_before, $term_after, $linked, $reverse );
	}

}

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_sort_taxonomies_by_parents' ) ) {

	function wpsight_sort_taxonomies_by_parents( $data, $parent_id = 0 ) {
		return WPSight_Helpers::get_the_term_list( $data, $parent_id );
	}

}

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_the_term_list_links' ) ) {

	function wpsight_get_the_term_list_links( $taxonomy, $data, $term_before = '', $term_after = '', $linked = 'true' ) {
		return WPSight_Helpers::get_the_term_list_links( $taxonomy, $data, $term_before, $term_after, $linked );
	}

}

/**
 * Helper functions to get
 * attachment ID by URL.
 *
 * @since 1.0.0
 * @credit https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_attachment_id_by_url' ) ) {

	function wpsight_get_attachment_id_by_url( $url ) {
		return WPSight_Helpers::get_attachment_id_by_url( $url );
	}

}

/**
 * Helper functions to get
 * attachment by URL.
 *
 * @since 1.0.0
 * @credit https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_attachment_by_url' ) ) {

	function wpsight_get_attachment_by_url( $url, $size = 'thumbnail' ) {
		return WPSight_Helpers::get_attachment_by_url( $url, $size );
	}

}

/**
 * Helper function to update image gallery.
 *
 * @param integer $listing_id Post ID of the corresponding listing
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_maybe_update_gallery' ) ) {
	
	function wpsight_maybe_update_gallery( $listing_id ) {
		return WPSight_Helpers::maybe_update_gallery( $listing_id );
	}

}

/**
 * Helper function to get all image sizes.
 *
 * @param string $size Limit to a specific size
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_image_sizes' ) ) {
	
	function wpsight_get_image_sizes( $size = '' ) {
		return WPSight_Helpers::get_image_sizes( $size );
	}

}
