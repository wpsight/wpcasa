<?php
/**
 *	WPSight agent functions
 *	
 *	@package WPSight \ Functions
 */

/**
 *	wpsight_agent_roles()
 *	
 *	Define custom user roles for agents:
 *	
 *	- Listing Admin
 *	- Listing Agent
 *	- Listing Subscriber
 *	
 *	Capabilities for listings are created in
 *	/includes/class-wpsight-post-types.php with
 *	capability_type 'listing' and map_meta_cap true.
 *	
 *		[cap] => stdClass Object
 *		(
 *			[edit_post]				=> edit_listing
 *			[read_post]				=> read_listing
 *			[delete_post]			=> delete_listing
 *			[edit_posts]			=> edit_listings
 *			[edit_others_posts]		=> edit_others_listings
 *			[publish_posts]			=> publish_listings
 *			[read_private_posts]	=> read_private_listings
 *				[delete_posts]				=> delete_listings
 *				[delete_private_posts]		=> delete_private_listings
 *				[delete_published_posts]	=> delete_published_listings
 *				[delete_others_posts]		=> delete_others_listings
 *				[edit_private_posts]		=> edit_private_listings
 *				[edit_published_posts]		=> edit_published_listings
 *				[create_posts]				=> edit_listings
 *		)
 *	
 *	@uses	WPSight_Agents::agent_roles()
 *	@return	array	$roles	Array of roles to be used by add_role() in /includes/class-wpsight-install.php
 *	
 *	@since 1.0.0
 */
function wpsight_agent_roles() {
	return WPSight_Agents::agent_roles();
}

/**
 *	wpsight_listing_agent_image()
 *	
 *	Echo wpsight_get_listing_agent_image()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string|array	$size	Size of the image (WordPress sizes or custom width and height in array)
 *	@uses	wpsight_get_listing_agent_image()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_image( $post = null, $size = array( 75, 75 ) ) {
	echo wpsight_get_listing_agent_image( $post, $size );
}

/**
 *	wpsight_get_listing_agent_image()
 *	
 *	Return HTML image tag of the agent image of
 *	the current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string|array	$size			Size of the image (WordPress sizes or custom width and height in array)
 *	@uses	WPSight_Agents::wpsight_get_listing_agent_image()
 *	@return string|bool		$agent_image	HTML image tag of the listing agent image or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_image( $post = null, $size = array( 75, 75 ) ) {
	return WPSight_Agents::get_listing_agent_image( $post, $size ) ;
}

/**
 *	wpsight_agent_image()
 *	
 *	Echo wpsight_get_agent_image()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string|array	$size	Size of the image (WordPress sizes or custom width and height in array)
 *	@uses	wpsight_get_agent_image()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_image( $user_id, $size = array( 75, 75 ) ) {
	echo wpsight_get_agent_image( $user_id, $size );
}

/**
 *	wpsight_get_agent_image()
 *	
 *	Return HTML image tag of the agent image of
 *	the current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string|array	$size			Size of the image (WordPress sizes or custom width and height in array)
 *	@uses	WPSight_Agents::wpsight_get_listing_agent_image()
 *	@return string|bool		$agent_image	HTML image tag of the listing agent image or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_image( $user_id, $size = array( 75, 75 ) ) {
	return WPSight_Agents::get_agent_image( $user_id, $size ) ;
}

/**
 *	wpsight_listing_agent_name()
 *	
 *	Echo wpsight_get_listing_agent_name()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	wpsight_get_listing_agent_name()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_name( $post = null ) {
	echo wpsight_get_listing_agent_name( $post );
}

/**
 *	wpsight_get_listing_agent_name()
 *	
 *	Return agent name of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	WPSight_Agents::get_listing_agent_name()
 *	@return	string|bool	$agent_name	Agent name of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_name( $post = null ) {
	return WPSight_Agents::get_listing_agent_name( $post );
}

/**
 *	wpsight_agent_name()
 *	
 *	Echo wpsight_get_agent_name()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@param	string	$name		The name type to be returned (defaults to display_name)
 *	@uses	wpsight_get_agent_name()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_name( $user_id, $name = 'display_name' ) {
	echo wpsight_get_agent_name( $user_id, $name );
}

/**
 *	wpsight_get_agent_name()
 *	
 *	Return name of a specific agent.
 *	
 *	@param	integer		$user_id	User ID of corresponding agent (required)
 *	@param	string		$name		The name type to be returned (defaults to display_name)
 *	@uses	WPSight_Agents::get_listing_agent_name()
 *	@return	string|bool	$agent_name	Agent name or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_name( $user_id, $name = 'display_name' ) {
	return WPSight_Agents::get_agent_name( $user_id, $name );
}

/**
 *	wpsight_listing_agent_company()
 *	
 *	Echo wpsight_get_listing_agent_company()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	wpsight_get_listing_agent_company()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_company( $post = null ) {
	echo wpsight_get_listing_agent_company( $post );
}

/**
 *	wpsight_get_listing_agent_company()
 *	
 *	Return agent company of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@uses WPSight_Agents::get_listing_agent_company()
 *	@return	string|bool		$agent_company	Agent company of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_company( $post = null ) {
	return WPSight_Agents::get_listing_agent_company( $post );
}

/**
 *	wpsight_agent_company()
 *	
 *	Echo wpsight_get_agent_company()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@uses	wpsight_get_agent_company()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_company( $user_id ) {
	echo wpsight_get_agent_company( $user_id );
}

/**
 *	wpsight_get_agent_company()
 *	
 *	Return name of a specific agent.
 *	
 *	@param	integer		$user_id		User ID of corresponding agent (required)
 *	@uses	WPSight_Agents::get_agent_company()
 *	@return	string|bool	$agent_company	Agent company or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_company( $user_id ) {
	return WPSight_Agents::get_agent_company( $user_id );
}

/**
 *	wpsight_listing_agent_description()
 *	
 *	Echo wpsight_get_listing_agent_description()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	wpsight_get_listing_agent_description()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_description( $post = null ) {
	echo wpsight_get_listing_agent_description( $post );
}

/**
 *	wpsight_get_listing_agent_description()
 *	
 *	Return agent description of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post				Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	WPSight_Agents::get_listing_agent_description()
 *	@return	string|bool		$agent_description	Agent description of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_description( $post = null ) {
	return WPSight_Agents::get_listing_agent_description( $post );
}

/**
 *	wpsight_agent_description()
 *	
 *	Echo wpsight_get_agent_description()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@uses	wpsight_get_agent_description()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_description( $user_id ) {
	echo wpsight_get_agent_description( $user_id );
}

/**
 *	wpsight_get_agent_description()
 *	
 *	Return description of a specific agent.
 *	
 *	@param	integer		$user_id			User ID of corresponding agent (required)
 *	@uses	WPSight_Agents::get_agent_description()
 *	@return	string|bool	$agent_description	Agent description or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_description( $user_id ) {
	return WPSight_Agents::get_agent_description( $user_id );
}

/**
 *	wpsight_listing_agent_website()
 *	
 *	Echo wpsight_get_listing_agent_website()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	wpsight_get_listing_agent_website()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_website( $post = null ) {
	echo wpsight_get_listing_agent_website( $post );
}

/**
 *	wpsight_get_listing_agent_website()
 *	
 *	Return agent website of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	WPSight_Agents::get_listing_agent_website
 *	@return	string|bool		$agent_website	Agent website of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_website( $post = null ) {
	return WPSight_Agents::get_listing_agent_website( $post );
}

/**
 *	wpsight_agent_website()
 *	
 *	Echo wpsight_get_agent_website()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@uses	wpsight_get_agent_website()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_website( $user_id ) {
	echo wpsight_get_agent_website( $user_id );
}

/**
 *	wpsight_get_agent_website()
 *	
 *	Return website of a specific agent.
 *	
 *	@param	integer		$user_id		User ID of corresponding agent (required)
 *	@uses	WPSight_Agents::get_agent_website
 *	@return	string|bool	$agent_website	Agent website or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_website( $user_id ) {
	return WPSight_Agents::get_agent_website( $user_id );
}

/**
 *	wpsight_listing_agent_phone()
 *	
 *	Echo wpsight_get_listing_agent_phone()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	wpsight_get_listing_agent_phone()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_phone( $post = null ) {
	echo wpsight_get_listing_agent_phone( $post );
}

/**
 *	wpsight_get_listing_agent_phone()
 *	
 *	Return agent phone of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@uses	WPSight_Agents::get_listing_agent_phone
 *	@return	string|bool		$agent_phone	Agent phone of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_phone( $post = null ) {
	return WPSight_Agents::get_listing_agent_phone( $post );
}

/**
 *	wpsight_agent_phone()
 *	
 *	Echo wpsight_get_agent_phone()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@uses wpsight_get_agent_phone()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_phone( $user_id ) {
	echo wpsight_get_agent_phone( $user_id );
}

/**
 *	wpsight_get_agent_phone()
 *	
 *	Return phone of a specific agent.
 *	
 *	@param	integer		$user_id		User ID of corresponding agent (required)
 *	@uses	WPSight_Agents::get_agent_phone
 *	@return	string|bool	$agent_phone	Agent phone or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_phone( $user_id ) {
	return WPSight_Agents::get_agent_phone( $user_id );
}

/**
 *	wpsight_listing_agent_twitter()
 *	
 *	Echo wpsight_get_listing_agent_twitter()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string			$return	Return Twitter user or URL (defaults to 'user' - can be 'url')
 *	@uses	wpsight_get_listing_agent_twitter()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_twitter( $post = null, $return = 'user' ) {
	echo wpsight_get_listing_agent_twitter( $post, $return );
}

/**
 *	wpsight_get_listing_agent_twitter()
 *	
 *	Return agent twitter of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string			$return			Return Twitter user or URL (defaults to 'user' - can be 'url')
 *	@uses	WPSight_Agents::get_listing_agent_twitter()
 *	@return	string|bool		$agent_twitter	Agent twitter of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_twitter( $post = null, $return = 'user' ) {
	return WPSight_Agents::get_listing_agent_twitter( $post, $return );
}

/**
 *	wpsight_agent_twitter()
 *	
 *	Echo wpsight_get_agent_twitter()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@param	string	$return		Return Twitter user or URL (defaults to 'user' - can be 'url')
 *	@uses	wpsight_get_agent_twitter()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_twitter( $user_id, $return = 'user' ) {
	echo wpsight_get_agent_twitter( $user_id, $return );
}

/**
 *	wpsight_get_agent_twitter()
 *	
 *	Return twitter of a specific agent.
 *	
 *	@param	integer		$user_id		User ID of corresponding agent (required)
 *	@param	string		$return			Return Twitter user or URL (defaults to 'user' - can be 'url')
 *	@uses WPSight_Agents::get_agent_twitter()
 *	@return	string|bool	$agent_twitter 	Agent twitter or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_twitter( $user_id, $return = 'user' ) {
	return WPSight_Agents::get_agent_twitter( $user_id, $return );
}

/**
 *	wpsight_listing_agent_facebook()
 *	
 *	Echo wpsight_get_listing_agent_facebook()
 *	
 *	@param	integer|object	$post	Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string			$return	Return Facebook user or URL (defaults to 'user' - can be 'url')
 *	@uses	wpsight_get_listing_agent_facebook()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_facebook( $post = null, $return = 'user' ) {
	echo wpsight_get_listing_agent_facebook( $post, $return );
}

/**
 *	wpsight_get_listing_agent_facebook()
 *	
 *	Return agent facebook of the
 *	current or a specific listing.
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@param	string			$return			Return Twitter user or URL (defaults to 'user' - can be 'url')
 *	@uses	WPSight_Agents::get_listing_agent_facebook()
 *	@return	string|bool		$agent_facebook	Agent facebook of the listing agent or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_facebook( $post = null, $return = 'user' ) {
	return WPSight_Agents::get_listing_agent_facebook( $post, $return );
}

/**
 *	wpsight_agent_facebook()
 *	
 *	Echo wpsight_get_agent_facebook()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@param	string	$return		Return Facebook user or URL (defaults to 'user' - can be 'url')
 *	@uses	wpsight_get_agent_facebook()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_facebook( $user_id, $return = 'user' ) {
	echo wpsight_get_agent_facebook( $user_id, $return );
}

/**
 *	wpsight_get_agent_facebook()
 *	
 *	Return facebook of a specific agent.
 *	
 *	@param	integer		$user_id		User ID of corresponding agent (required)
 *	@param	string		$return			Return Facebook user or URL (defaults to 'user' - can be 'url')
 *	@uses	WPSight_Agents::get_agent_facebook
 *	@return string|bool	$agent_facebook	Agent facebook or false
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_facebook( $user_id, $return = 'user' ) {
	return WPSight_Agents::get_agent_facebook( $user_id, $return );
}

/**
 *	wpsight_listing_agent_archive()
 *	
 *	Echo wpsight_get_listing_agent_archive()
 *	
 *	@param	integer|object	$post		Post ID or object of specific listing (defaults to null = current listing)
 *	@param	integer			$user_id	User ID of the corresponding agent (defaults to post_author)
 *	@uses	wpsight_get_listing_agent_archive()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_agent_archive( $post = null, $user_id = false ) {
	echo wpsight_get_listing_agent_archive( $post, $user_id );
}

/**
 *	wpsight_get_listing_agent_archive()
 *	
 *	Get listing agent archive by adding
 *	"listings=1" to get_author_posts_url().
 *	
 *	@param	integer|object	$post			Post ID or object of specific listing (defaults to null = current listing)
 *	@param	integer			$user_id		User ID of the corresponding agent (defaults to post_author)
 *	@uses	WPSight_Agents::get_listing_agent_archive()
 *	@return	string			$agent_archive	Author posts URL with additional query arg "listing=1"
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_agent_archive( $post = null, $user_id = false ) {
	return WPSight_Agents::get_listing_agent_archive( $post, $user_id );
}

/**
 *	wpsight_agent_archive()
 *	
 *	Echo wpsight_get_agent_archive()
 *	
 *	@param	integer	$user_id	User ID of corresponding agent (required)
 *	@uses	wpsight_get_agent_archive()
 *	
 *	@since 1.0.0
 */
function wpsight_agent_archive( $user_id ) {
	echo wpsight_get_agent_archive( $user_id );
}

/**
 *	wpsight_get_agent_archive()
 *	
 *	Get archive link of a specific agent.
 *	
 *	@param	integer	$user_id		User ID of corresponding agent (required)
 *	@uses	WPSight_Agents::get_agent_archive()
 *	@return	string	$agent_archive	Author posts URL with additional query arg "listings=1"
 *	
 *	@since 1.0.0
 */
function wpsight_get_agent_archive( $user_id ) {
	return WPSight_Agents::get_agent_archive( $user_id );
}

/**
 *	Helper function to get posts
 *	by user and post type
 *	
 *	@param	integer	$user_id	User ID (defaults to current user)
 *	@param	string	$post_type	Post type (defaults to post)
 *	@uses	WPSight_Agents::get_user_posts_by_type()
 *	
 *	@since 1.0.0
 */
function wpsight_get_user_posts_by_type( $user_id = false, $post_type = 'post' ) {
	return WPSight_Agents::get_user_posts_by_type( $user_id, $post_type );
}

/**
 *	wpsight_profile_contact_fields()
 *	
 *	Return user contact fields
 *	
 *	@uses	WPSight_Agents::profile_contact_fields()
 *	@return	array	$fields	Array of contact fields
 *	
 *	@since 1.0.0
 */

function wpsight_profile_contact_fields() {
	return WPSight_Agents::profile_contact_fields();
}
