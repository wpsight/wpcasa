<?php
/**
 *	WPSight listing functions
 *	
 *	@package WPSight \ Functions
 */

/**
 *	wpsight_listings()
 *	
 *	Output formatted listing query
 *	
 *	@param	array	$args Array of query arguments
 *	@param	string	$template_path Custom template path
 *	@uses	WPSight_Listings::listings()
 *	
 *	@since 1.0.0
 */
function wpsight_listings( $args = array(), $template_path = '' ) {
	return WPSight_Listings::listings( $args, $template_path );
}

/**
 *	wpsight_get_listings()
 *	
 *	Return listings WP_Query
 *	
 *	@param	array	$args	Array of query arguments
 *	@uses	WPSight_Listings::get_listings()
 *	@return	object	$result	WP_Query object
 *	
 *	@since 1.0.0
 */
function wpsight_get_listings( $args = array() ) {
	return WPSight_Listings::get_listings( $args );
}

/**
 *	wpsight_listing_teaser()
 *	
 *	Output formatted single listing teaser.
 *	
 *	@param	integer|object	$teaser_id	Post or listing ID or WP_Post object
 *	@uses	WPSight_Listings::listing_teaser()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_teaser( $listing_id = null ) {
	return WPSight_Listings::listing_teaser( $listing_id );
}

/**
 *	wpsight_listing()
 *	
 *	Output formatted single listing or
 *	archive teaser if $full is (bool) false.
 *	
 *	@param	integer|object	$listing_id	Post or listing ID or WP_Post object
 *	@param	bool			$full		Set true to show entire listing or false to show archive teaser
 *	@uses WPSight_Listings::listing()
 *	
 *	@since 1.0.0
 */
function wpsight_listing( $listing_id = null, $full = true ) {
	return WPSight_Listings::listing( $listing_id, $full );
}

/**
 *	wpsight_listing_teasers()
 *	
 *	Output list of listing teasers.
 *	
 *	@param	array	$args			Array of query arguments
 *	@param	string	$template_path	Custom template path
 *	@uses	WPSight_Listings::listing_teasers()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_teasers( $args = array(), $template_path = '' ) {
	return WPSight_Listings::listing_teasers( $args, $template_path );
}

/**
 *	wpsight_get_listing()
 *	
 *	Return single listing post object.
 *	
 *	@param	string|object	$post Post or listing ID or WP_Post object
 *	@uses	WPSight_Listings::wpsight_get_listing()
 *	@return	object			WP_Post object
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing( $post = null ) {
	return WPSight_Listings::get_listing( $post );
}

/**
 *	wpsight_get_listing_price_raw()
 *	
 *	Return listings price without formatting.
 *
 *	@param integer $post_id Post ID
 *	@uses WPSight_Listings::get_listing_price_raw()
 *	@return string Listing price meta value
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_price_raw( $post_id = '' ) {
	return WPSight_Listings::get_listing_price_raw( $post_id );
}

/**
 *	wpsight_listing_price_raw()
 *	
 *	Echo wpsight_get_listing_price_raw().
 *	
 *	@param	integer	$post_id	Post ID
 *	@uses	wpsight_get_listing_price_raw()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_price_raw( $post_id = '' ) {
	echo wpsight_get_listing_price_raw( $post_id );
}

/**
 *	wpsight_get_listing_offer()
 *	
 *	Return listings offer (e.g. sale, rent).
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	bool	$label		Optionally return offer key
 *	@uses	WPSight_Listings::get_listing_offer()
 *	@return	string	Offer label or key
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_offer( $post_id = '', $label = true ) {
	return WPSight_Listings::get_listing_offer( $post_id, $label );
}

/**
 *	wpsight_listing_offer()
 *	
 *	Echo wpsight_get_listing_offer().
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	bool	$label		Optionally return offer key
 *	@uses	wpsight_get_listing_offer()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_offer( $post_id = '', $label = true ) {
	echo wpsight_get_listing_offer( $post_id, $label );
}

/**
 *	wpsight_get_listing_period()
 *	
 *	Return listings rental period.
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	bool	$label		Optionally return period key
 *	@uses	WPSight_Listings::get_listing_period()
 *	@return	string	Period label or key
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_period( $post_id = '', $label = true ) {
	return WPSight_Listings::get_listing_period( $post_id, $label );
}

/**
 *	wpsight_listing_period()
 *	
 *	Echo wpsight_get_listing_period().
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	bool	$label		Optionally return period key
 *	@uses	wpsight_get_listing_period()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_period( $post_id = '', $label = true ) {
	echo wpsight_get_listing_period( $post_id, $label );
}

/**
 *	wpsight_get_listing_detail()
 *	
 *	Return specific detail value of a listing.
 *	
 *	@param	string			$detail wpsight_details() key
 *	@param	integer			$post_id Post ID
 *	@uses	WPSight_Listings::get_listing_detail()
 *	@return	string|false	Listing detail value or false if empty
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_detail( $detail, $post_id = '' ) {
	return WPSight_Listings::get_listing_detail( $detail, $post_id );
}

/**
 *	wpsight_listing_detail()
 *	
 *	Echo wpsight_get_listing_detail().
 *	
 *	@param	string	$detail wpsight_details() key
 *	@param	integer	$post_id Post ID
 *	@uses	wpsight_get_listing_detail()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_detail( $detail, $post_id = '' ) {
	echo wpsight_get_listing_detail( $detail, $post_id );
}

/**
 *	wpsight_get_listing_details()
 *	
 *	Return listings details.
 *	
 *	@param	integer			$post_id Post ID
 *	@param	array			$details Array of details (keys from wpsight_details())
 *	@param	string|bool		$formatted CSS class for container or false to return array
 *	@uses WPSight_Listings::get_listing_details()
 *	@return	string|array	Formatted details or unformatted array
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_details( $post_id = '', $details = false, $formatted = 'wpsight-listing-details' ) {
	return WPSight_Listings::get_listing_details( $post_id, $details, $formatted );
}

/**
 *	wpsight_listing_details()
 *	
 *	Echo formatted listing details or print_r if array/unformatted.
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	array	$details	Array of details (keys from wpsight_details())
 *	@param	bool	$formatted Function returns array if false
 *	@uses	wpsight_get_listing_details()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_details( $post_id = '', $details = false, $formatted = 'wpsight-listing-details' ) {

	$listing_details = wpsight_get_listing_details( $post_id, $details, $formatted );

	// Only echo if not array

	if ( ! is_array( $listing_details ) ) {
		echo $listing_details;
	} else {
		// Echo print_r array for debugging
		?><pre><?php print_r( $listing_details ); ?></pre><?php
	}
}

/**
 *	wpsight_get_listing_summary()
 *	
 *	Return specific set of listings details.
 *	
 *	@param	integer			$post_id	Post ID
 *	@param	array			$details	Array of details (keys from wpsight_details())
 *	@param	bool			$formatted Function returns array if false
 *	@uses	WPSight_Listings::get_listing_summary()
 *	@return	string|array	Formatted details or unformatted array
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_summary( $post_id = '', $details = false, $formatted = 'wpsight-listing-summary' ) {
	return WPSight_Listings::get_listing_summary( $post_id, $details, $formatted );
}

/**
 *	wpsight_listing_summary()
 *	
 *	Echo listing summary or print_r if array.
 *	
 *	@param	integer		$post_id Post ID
 *	@param	array		$details Array of details (keys from wpsight_details())
 *	@param	string|bool	$formatted CSS class for wrap or function returns array if false
 *	@uses	wpsight_get_listing_summary()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_summary( $post_id = '', $details = false, $formatted = 'wpsight-listing-summary' ) {

	$listing_summary = wpsight_get_listing_summary( $post_id, $details, $formatted );

	// Only echo if not array

	if ( ! is_array( $listing_summary ) ) {
		echo $listing_summary;
	} else {
		// Echo print_r array for debugging
		?><pre><?php print_r( $listing_summary ); ?></pre><?php
	}
}

/**
 *	wpsight_get_listing_id()
 *	
 *	Return listing ID. By default the listing ID
 *	is a prefix with the post ID. The listing ID
 *	can manually be changed in the listing details
 *	meta box and is saved as custom post meta '_listing_id'.
 *	
 *	@param	integer		$post_id	Post ID
 *	@param	string		$prefix		Lising ID prefix
 *	@uses	WPSight_Listings::get_listing_id()
 *	@return	string|bool	Listing ID or false if no post ID available
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_id( $post_id = '', $prefix = '' ) {
	return WPSight_Listings::get_listing_id( $post_id, $prefix );
}

/**
 *	wpsight_listing_id()
 *	
 *	Echo listing ID.
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	string	$prefix		Lising ID prefix
 *	@uses	WPSight_Listings::get_listing_id()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_id( $post_id = '', $prefix = '' ) {
	echo wpsight_get_listing_id( $post_id, $prefix );
}

/**
 *	wpsight_get_listing_price()
 *	
 *	Returns formatted listing price with
 *	with currency and rental period.
 *	
 *	@param	integer		$post_id				Post ID (defaults to get_the_ID())
 *	@param	bool		$args['number_format']	Apply number_format() or not
 *	@param	bool		$args['show_currency']	Show currency or not
 *	@param	bool		$args['show_period']	Show rental period or not
 *	@param	bool		$args['show_request']	Show 'price on request' or not
 *	@uses WPSight_Listings::get_listing_price()
 *	@return string|bool	Formatted listing price or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_price( $post_id = '', $before = '', $after = '', $args = array() ) {
	return WPSight_Listings::get_listing_price( $post_id, $before, $after, $args );
}

/**
 *	wpsight_listing_price()
 *	
 *	Echo wpsight_get_listing_price()
 *	
 *	@param	integer	$post_id				Post ID (defaults to get_the_ID())
 *	@param	bool	$args['number_format']	Apply number_format() or not
 *	@param	bool	$args['show_currency']	Show currency or not
 *	@param	bool	$args['show_period']	Show rental period or not
 *	@param	bool	$args['show_request']	Show 'price on request' or not
 *	@uses WPSight_Listings::get_listing_price()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_price( $post_id = '', $before = '', $after = '', $args = array() ) {
	echo wpsight_get_listing_price( $post_id, $before, $after, $args );
}

/**
 *	wpsight_get_listing_terms()
 *	
 *	Returns listing terms of a specific
 *	taxonomy ordered by hierarchy.
 *	
 *	@param	string	$taxonomy		Taxonomy of the terms to return (defaults to 'feature')
 *	@param	integer	$post_id		Post ID (defaults to get_the_ID())
 *	@param	string	$sep        	Separator between terms
 *	@param	string	$term_before	Content before each term
 *	@param	string	$term_after		Content after each term
 *	@param	bool	$linked			Link terms to their archive pages or not
 *	@param	bool	$reverse		Begin with lowest leven for hiearachical taxonomies
 *	@uses WPSight_Listings::get_listing_terms()
 *	@return string|null				List of terms or null if taxonomy does not exist
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_terms( $taxonomy = '', $post_id = '', $sep = '', $term_before = '', $term_after = '', $linked = true, $reverse = false ) {
	return WPSight_Listings::get_listing_terms( $taxonomy, $post_id, $sep, $term_before, $term_after, $linked, $reverse );
}

/**
 *	wpsight_listing_terms()
 *	
 *	Echo listing ID.
 *	
 *	@param	string	$taxonomy		Taxonomy of the terms to return (defaults to 'feature')
 *	@param	integer	$post_id		Post ID (defaults to get_the_ID())
 *	@param	string	$sep			Separator between terms
 *	@param	string	$term_before	Content before each term
 *	@param	string	$term_after		Content after each term
 *	@param	bool	$linked			Link terms to their archive pages or not
 *	@param	bool	$reverse		Begin with lowest leven for hiearachical taxonomies
 *	@uses WPSight_Listings::get_listing_terms()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_terms( $taxonomy = '', $post_id = '', $sep = '', $term_before = '', $term_after = '', $linked = true, $reverse = false ) {
	echo wpsight_get_listing_terms( $taxonomy, $post_id, $sep, $term_before, $term_after, $linked, $reverse );
}

/**
 *	wpsight_get_listing_thumbnail()
 *	
 *	Return a thumbnail of a specific listing.
 *	
 *	@param	integer			$post_id	Post ID
 *	@param	array			$attr	Array of attributes for the thumbnail (for get_the_post_thumbnail())
 *	@param	string|bool	$formatted CSS class of image container div or false to return wp_get_attachment_image_src()
 *	@uses	WPSight_Listings::get_listing_thumbnail()
 *	@return	string|array	HTML image tag with container div or array (see wp_get_attachment_image_src())
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_thumbnail( $post_id = '', $size = 'thumbnail', $attr = '', $default = '', $formatted = 'wpsight-listing-thumbnail' ) {
	return WPSight_Listings::get_listing_thumbnail( $post_id, $size, $attr, $default, $formatted );
}

/**
 *	wpsight_listing_thumbnail()
 *	
 *	Echo wpsight_get_listing_thumbnail().
 *	
 *	@param	integer		$post_id	Post ID
 *	@param	array		$attr		Array of attributes for the thumbnail (for get_the_post_thumbnail())
 *	@param	string|bool	$formatted	CSS class of image container div or false to return wp_get_attachment_image_src()
 *	@uses	WPSight_Listings::get_listing_thumbnail()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_thumbnail( $post_id = '', $size = 'thumbnail', $attr = '', $default = '', $formatted = 'wpsight-listing-thumbnail' ) {
	echo wpsight_get_listing_thumbnail( $post_id, $size, $attr, $default, $formatted );
}

/**
 *	wpsight_get_listing_thumbnail_url()
 *	
 *	Return a thumbnail URL of a specific listing.
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	string	$size		Size of the image (thumbnail, large etc.). Defaults to 'thumbnail'.
 *	@uses	WPSight_Listings::get_listing_thumbnail_url
 *	@return	string	URL of the thumbnail
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_thumbnail_url( $post_id = '', $size = 'thumbnail' ) {
	return WPSight_Listings::get_listing_thumbnail_url( $post_id, $size );
}

/**
 *	wpsight_listing_thumbnail_url()
 *	
 *	Echo wpsight_get_listing_thumbnail_url().
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	string	$size		Size of the image (thumbnail, large etc.). Defaults to 'thumbnail'.
 *	@uses	WPSight_Listings::get_listing_thumbnail_url()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_thumbnail_url( $post_id = '', $size = 'thumbnail' ) {
	echo wpsight_get_listing_thumbnail_url( $post_id, $size );
}

/**
 *	wpsight_is_listing_sticky()
 *	
 *	Check if a specific listing is sticky
 *	(custom field '_listing_sticky').
 *	
 *	@param	integer	$post_id	Post ID of the corresponding listing (defaults to current post)
 *	@uses	WPSight_Listings::is_listing_sticky()
 *	@return	bool	$result		True if _listing_sticky has value, else false
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_sticky( $post_id = '' ) {
	return WPSight_Listings::is_listing_sticky( $post_id );
}

/**
 *	wpsight_is_listing_featured()
 *	
 *	Check if a specific listing is featured
 *	(custom field '_listing_featured').
 *	
 *	@param	integer	$post_id	Post ID of the corresponding listing (defaults to current post)
 *	@uses	WPSight_Listings::is_listing_featured()
 *	@return	bool	$result		True if _listing_featured has value, else false
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_featured( $post_id = '' ) {
	return WPSight_Listings::is_listing_featured( $post_id );
}

/**
 *	wpsight_is_listing_not_available()
 *	
 *	Check if a specific listing item is no longer available
 *	(custom field '_listing_not_available').
 *	
 *	@param	integer	$post_id	Post ID of the corresponding listing (defaults to current post)
 *	@uses	WPSight_Listings::is_listing_not_available()
 *	@return	bool	$result		True if _listing_not_available has value, else false
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_not_available( $post_id = '' ) {
	return WPSight_Listings::is_listing_not_available( $post_id );
}

/**
 *	wpsight_is_listing_pending()
 *	
 *	Check if a specific listing has post
 *	status 'pending' or 'pending_payment'.
 *	
 *	@param	WPSight_Listings::is_listing_pending()
 *	@return	bool	True if post status is 'pending' or 'pending_payment', else false
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_pending( $post_id = '' ) {
	return WPSight_Listings::is_listing_pending( $post_id );
}

/**
 *	wpsight_is_listing_expired()
 *	
 *	Check if a specific listing has post status 'expired'
 *	
 *	@param	integer	$post_id	Post ID of the corresponding listing
 *	@uses	WPSight_Listings::is_listing_expired()
 *	@return	bool 	True if post status is 'expired', else false
 *	
 *	@since 1.0.0
 */
function wpsight_is_listing_expired( $post_id = '' ) {
	return WPSight_Listings::is_listing_expired( $post_id );
}

/**
 *	wpsight_user_can_edit_listing()
 *	
 *	@param	integer	$listing_id
 *	@uses	WPSight_Listings::user_can_edit_listing()
 *	@return	bool	True if an the user can edit a listing
 *	
 *	@since 1.0.0
 */
function wpsight_user_can_edit_listing( $listing_id ) {
	return WPSight_Listings::user_can_edit_listing( $listing_id );
}

/**
 *	wpsight_delete_listing_previews()
 *	
 *	Delete old expired listing previews if number of days
 *	have passed after last modification and status is preview.
 *	
 *	##### FUNCTION CALLED BY CRON ####
 *	
 *	@param	int			$days Number of days after that previews are deleted
 *	@uses	WPSight_Listings::delete_listing_previews()
 *	@return	array|bool	Array of post IDs, false if no previews deleted
 *	@see	/includes/class-wpsight-post-types.php
 *	
 *	@since 1.0.0
 */
function wpsight_delete_listing_previews( $days = '' ) {
	return WPSight_Listings::delete_listing_previews( $days );
}

/**
 *  wpsight_search_listing_id()
 *
 *  Perform a search in various listings fields for given string
 *
 *  @param	string	$search
 *  @uses	WPSight_Listings::search_listing_id()
 *  @return	mixed	Array of post IDs, false if no previews deleted
 *
 *  @since 1.0.0
 */

function wpsight_search_listing_id( $search ) {
	return WPSight_Listings::search_listing_id( $search );
}
