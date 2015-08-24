<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Agents API
 *
 * This class sets up agents.
 */
class WPSight_Agents {

	function __construct() {
		add_filter( 'pre_get_posts', array( $this, 'author_listings' ) );
	}

	/**
	 * agent_roles()
	 *
	 * Define custom user roles for agents:
	 *
	 * - Listing Admin
	 * - Listing Agent
	 * - Listing Subscriber
	 *
	 * Capabilities for listings are created in
	 * /includes/class-wpsight-post-types.php with
	 * capability_type 'listing' and map_meta_cap true.
	 *
	 *	[cap] => stdClass Object
	 *	(
	 *		[edit_post]		 		 => edit_listing
	 *		[read_post]		 		 => read_listing
	 *		[delete_post]		 	 => delete_listing
	 *		[edit_posts]		 	 => edit_listings
	 *		[edit_others_posts]	 	 => edit_others_listings
	 *		[publish_posts]		 	 => publish_listings
	 *		[read_private_posts]	 => read_private_listings
	 *	    [delete_posts]           => delete_listings
	 *	    [delete_private_posts]   => delete_private_listings
	 *	    [delete_published_posts] => delete_published_listings
	 *	    [delete_others_posts]    => delete_others_listings
	 *	    [edit_private_posts]     => edit_private_listings
	 *	    [edit_published_posts]   => edit_published_listings
	 *	    [create_posts]           => edit_listings
	 *	)
	 *
	 * @return array $roles Array of roles to be used by add_role() in /includes/class-wpsight-install.php
	 *
	 * @since 1.0.0
	 */

	public static function agent_roles() {

		$roles = array(

			'listing_admin' => array(
				'id'   => 'listing_admin',
				'name' => _x( 'Listing Admin', 'agent role', 'wpsight' ),
				'caps' => array(
					'read'                      => true,
					'upload_files'              => true,
					'unfiltered_html'           => true,
					'edit_listing'              => true,
					'edit_listing_id'           => true,
					'read_listing'              => true,
					'delete_listing'            => true,
					'edit_listings'             => true,
					'edit_others_listings'      => true,
					'publish_listings'          => true,
					'read_private_listings'     => true,
					'delete_listings'           => true,
					'delete_private_listings'   => true,
					'delete_published_listings' => true,
					'delete_others_listings'    => true,
					'edit_private_listings'     => true,
					'edit_published_listings'   => true,
					'edit_listings'             => true,
					'manage_listing_terms'      => true,
					'edit_listing_terms'        => true,
					'delete_listing_terms'      => true,
					'assign_listing_terms'      => true
				)
			),

			'listing_agent' => array(
				'id'   => 'listing_agent',
				'name' => _x( 'Listing Agent', 'agent role', 'wpsight' ),
				'caps' => array(
					'read'                 => true,
					'upload_files'         => true,
					'edit_listing'         => true,
					'read_listing'         => true,
					'delete_listing'       => true,
					'edit_listings'        => true,
					'delete_listings'      => true,
					'edit_listings'        => true,
					'assign_listing_terms' => true
				)
			),

			'listing_subscriber' => array(
				'id'   => 'listing_subscriber',
				'name' => _x( 'Listing Subscriber', 'agent role', 'wpsight' ),
				'caps' => array(
					'read'         => true,
					'read_listing' => true
				)
			)

		);

		return apply_filters( 'wpsight_agent_roles', $roles );

	}

	/**
	 * get_listing_agent_image()
	 *
	 * Return HTML image tag of the agent image of
	 * the current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @param string|array $size Size of the image (WordPress sizes or custom width and height in array)
	 * @return string|bool $agent_image HTML image tag of the listing agent image or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_image( $post = null, $size = array( 75, 75 ) ) {

		$agent_image   = '';
		$agent_image_url = '';

		// Get post object
		$post = get_post( $post );

		// Get image URL from post meta
		$agent_image_url = $post->_agent_logo;

		// If we have a URL, built the image

		if ( ! empty( $agent_image_url ) ) {

			// Get attachment ID
			$attachment_id = wpsight_get_attachment_id_by_url( $agent_image_url );

			// Get attachment URL for specific size
			$attachment = wp_get_attachment_image_src( $attachment_id, $size );

			// Build HTML image tag
			$agent_image = '<img src="' . $attachment[0] . '" width="' . $attachment[1] . '" height="' . $attachment[2] . '" alt="' . wpsight_get_listing_agent_name( $post ) . '" />';

		}

		// If no image, return false

		if ( empty( $agent_image ) )
			$agent_image = false;

		// Return agent image or false
		return apply_filters( 'wpsight_listing_agent_image', $agent_image, $post, $size );

	}

	/**
	 * get_listing_agent_name()
	 *
	 * Return agent name of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @return string|bool $agent_name Agent name of the listing agent or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_name( $post = null ) {

		$agent_name = '';

		// Get post object
		$post = get_post( $post );

		// Get agent name from post meta
		$agent_name = $post->_agent_name;

		// If no name, return false

		if ( empty( $agent_name ) )
			$agent_name = false;

		// Return agent name or false
		return apply_filters( 'wpsight_listing_agent_name', $agent_name, $post );

	}

	/**
	 * get_listing_agent_company()
	 *
	 * Return agent company of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @return string|bool $agent_company Agent company of the listing agent or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_company( $post = null ) {

		$agent_company = '';

		// Get post object
		$post = get_post( $post );

		// Get agent company from post meta
		$agent_company = $post->_agent_company;

		// If no name, return false

		if ( empty( $agent_company ) )
			$agent_company = false;

		// Return agent name or false
		return apply_filters( 'wpsight_listing_agent_company', $agent_company, $post );

	}

	/**
	 * listing_agent_description()
	 *
	 * Echo wpsight_get_listing_agent_description()
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 *
	 * @since 1.0.0
	 */

	public static function listing_agent_description( $post = null ) {
		echo wpsight_get_listing_agent_description( $post );
	}

	/**
	 * get_listing_agent_description()
	 *
	 * Return agent description of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @return string|bool $agent_description Agent description of the listing agent or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_description( $post = null ) {

		$agent_description = '';

		// Get post object
		$post = get_post( $post );

		// Get agent description from post meta
		$agent_description = ! empty( $post->_agent_description ) ? wpsight_format_content( $post->_agent_description ) : false;

		// Return agent description or false
		return apply_filters( 'wpsight_listing_agent_description', $agent_description, $post );

	}

	/**
	 * listing_agent_website()
	 *
	 * Echo wpsight_get_listing_agent_website()
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 *
	 * @since 1.0.0
	 */

	public static function listing_agent_website( $post = null ) {
		echo wpsight_get_listing_agent_website( $post );
	}

	/**
	 * get_listing_agent_website()
	 *
	 * Return agent website of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @return string|bool $agent_website Agent website of the listing agent or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_website( $post = null ) {

		$agent_website = '';

		// Get post object
		$post = get_post( $post );

		// Get agent website from post meta
		$agent_website = esc_url( $post->_agent_website );

		// If no website, return false

		if ( empty( $agent_website ) )
			$agent_website = false;

		// Return agent website or false
		return apply_filters( 'wpsight_listing_agent_website', $agent_website, $post );

	}

	/**
	 * listing_agent_twitter()
	 *
	 * Echo wpsight_get_listing_agent_twitter()
	 *
	 * @param integer|object $post   Post ID or object of required listing (defaults to null = current listing)
	 * @param string  $return Return Twitter user or URL (defaults to 'user' - can be 'url')
	 *
	 * @since 1.0.0
	 */

	public static function listing_agent_twitter( $post = null, $return = 'user' ) {
		echo wpsight_get_listing_agent_twitter( $post, $return );
	}

	/**
	 * get_listing_agent_twitter()
	 *
	 * Return agent twitter of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post   Post ID or object of required listing (defaults to null = current listing)
	 * @param string  $return Return Twitter user or URL (defaults to 'user' - can be 'url')
	 * @return string|bool $agent_twitter Agent twitter of the listing agent or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_twitter( $post = null, $return = 'user' ) {

		$agent_twitter = '';

		// Get post object
		$post = get_post( $post );

		// Get agent twitter from post meta
		$agent_twitter = $post->_agent_twitter;

		// Remove @ to get username only

		if ( strpos( $agent_twitter, '@' ) === 0 )
			$agent_twitter = substr( $agent_twitter, 1 );

		if ( $return == 'url' ) {
			// If no twitter, return false. Else prepend twitter.com if URL requested
			$agent_twitter = empty( $agent_twitter ) ? false : esc_url( 'https://twitter.com/' . $agent_twitter );
		}

		// Return agent twitter or false
		return apply_filters( 'wpsight_listing_agent_twitter', $agent_twitter, $post, $return );

	}

	/**
	 * listing_agent_facebook()
	 *
	 * Echo wpsight_get_listing_agent_facebook()
	 *
	 * @param integer|object $post   Post ID or object of required listing (defaults to null = current listing)
	 * @param string  $return Return Twitter user or URL (defaults to 'user' - can be 'url')
	 *
	 * @since 1.0.0
	 */

	public static function listing_agent_facebook( $post = null, $return = 'user' ) {
		echo wpsight_get_listing_agent_facebook( $post, $return );
	}

	/**
	 * get_listing_agent_facebook()
	 *
	 * Return agent facebook of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post   Post ID or object of required listing (defaults to null = current listing)
	 * @param string  $return Return Twitter user or URL (defaults to 'user' - can be 'url')
	 *
	 * @return string|bool $agent_facebook Agent facebook of the listing agent or false
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_facebook( $post = null, $return = 'user' ) {

		$agent_facebook = '';

		// Get post object
		$post = get_post( $post );

		// Get agent twitter from post meta
		$agent_facebook = $post->_agent_facebook;

		if ( $return == 'url' ) {
			// If no facebook, return false. Else prepend facebook.com if URL requested
			$agent_facebook = empty( $agent_facebook ) ? false : esc_url( 'https://www.facebook.com/' . $agent_facebook );
		}

		// Return agent twitter or false
		return apply_filters( 'wpsight_listing_agent_facebook', $agent_facebook, $post, $return );

	}

	/**
	 * listing_agent_archive()
	 *
	 * Echo wpsight_get_listing_agent_archive()
	 *
	 * @param integer|object $post    Post ID or object of required listing (defaults to null = current listing)
	 * @param integer $user_id User ID of the corresponding agent (defaults to post_author)
	 *
	 * @since 1.0.0
	 */

	public static function listing_agent_archive( $post = null, $user_id = false ) {
		echo wpsight_get_listing_agent_archive( $post, $user_id );
	}

	/**
	 * get_listing_agent_twitter()
	 *
	 * Get listing agent archive by adding
	 * "listings=1" to get_author_posts_url().
	 *
	 * @param integer|object $post    Post ID or object of required listing (defaults to null = current listing)
	 * @param integer $user_id User ID of the corresponding agent (defaults to post_author)
	 *
	 * @return string $agent_archive Author posts URL with additional query arg "listing=1"
	 *
	 * @since 1.0.0
	 */

	public static function get_listing_agent_archive( $post = null, $user_id = false ) {

		$agent_archive = '';

		// Get post object
		$post = get_post( $post );

		// Set user ID
		$user_id = $user_id == false ? absint( $post->post_author ) : absint( $user_id );

		// If user ID, set author posts URL with query arg, else false
		$agent_archive = ! empty( $user_id ) ? esc_url( add_query_arg( 'listings', '1', get_author_posts_url( $user_id ) ) ) : false;

		// Return agent archive link or false
		return apply_filters( 'wpsight_get_listing_agent_archive', $agent_archive, $post, $user_id );

	}

	/**
	 * author_listings()
	 *
	 * Limit author archive entries to listings
	 * when corresponding query arg is set.
	 *
	 * @param object  $query WP_Query object
	 * @uses wpsight_is_listing_agent_archive()
	 * @uses wpsight_post_type()
	 * @uses $query->set()
	 *
	 * @since 1.0.0
	 */

	public static function author_listings( $query ) {

		if ( wpsight_is_listing_agent_archive( $query ) )
			$query->set( 'post_type', array( wpsight_post_type() ) );

	}

	/**
	 * profile_contact_fields()
	 *
	 * Return user contact fields
	 *
	 * @return array $fields Array of contact fields
	 * @since 1.0.0
	 */

	public static function profile_contact_fields() {

		$fields = array(
			'company'   => array(
				'label' => __( 'Company', 'wpsight' )
			),
			'phone'     => array(
				'label' => __( 'Phone', 'wpsight' )
			),
			'twitter'   => array(
				'label' => 'Twitter'
			),
			'facebook'  => array(
				'label' => 'Facebook'
			)
		);

		return apply_filters( 'wpsight_profile_contact_fields', $fields );

	}

}
