<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_Agents Class
 */
class WPSight_Agents {

	/**
	 * Constructor
	 */
	function __construct() {
		add_filter( 'pre_get_posts', array( $this, 'author_listings' ) );
		add_filter( 'get_avatar' , array( $this, 'agent_avatar' ), 1, 5 );
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
				'name' => _x( 'Listing Admin', 'agent role', 'wpcasa' ),
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
					'manage_listing_terms'      => true,
					'edit_listing_terms'        => true,
					'delete_listing_terms'      => true,
					'assign_listing_terms'      => true
				)
			),

			'listing_agent' => array(
				'id'   => 'listing_agent',
				'name' => _x( 'Listing Agent', 'agent role', 'wpcasa' ),
				'caps' => array(
					'read'                 => true,
					'upload_files'         => true,
					'edit_listing'         => true,
					'read_listing'         => true,
					'delete_listing'       => true,
					'edit_listings'        => true,
					'delete_listings'      => true,
					'assign_listing_terms' => true
				)
			),

			'listing_subscriber' => array(
				'id'   => 'listing_subscriber',
				'name' => _x( 'Listing Subscriber', 'agent role', 'wpcasa' ),
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
	 * @uses wpsight_get_attachment_id_by_url()
	 * @uses wp_get_attachment_image_src()
	 * @uses wpsight_get_listing_agent_name()
	 * @uses wpsight_get_agent_image()
	 * @return string|bool $agent_image HTML image tag of the listing agent image or false
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_agent_image( $post = null, $size = array( 75, 75 ) ) {

		$agent_image   		= '';
		$agent_image_url 	= '';

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

		// If no image, return agent image

		if ( empty( $agent_image ) )
			$agent_image = wpsight_get_agent_image( $post->post_author );

		// Return agent image or false
		return apply_filters( 'wpsight_listing_agent_image', $agent_image, $post, $size );

	}

	/**
	 * get_agent_image()
	 *
	 * Return HTML image tag of image of a specific agent.
	 *
	 * @param integer $user User ID of corresponding agent (required)
	 * @param string|array $size Size of the image (WordPress sizes or custom width and height in array)
	 * @uses get_user_meta()
	 * @uses wp_get_attachment_image_src()
	 * @uses get_userdata()
	 * @return string|bool $agent_image HTML image tag of the listing agent image or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_image( $user_id, $size = array( 75, 75 ) ) {
		
		// Get image ID from user meta
		$agent_image_id = get_user_meta( $user_id, 'agent_logo_id', true );

		// If we have a URL, built the image

		if ( ! empty( $agent_image_id ) ) {

			// Get attachment URL for specific size
			$attachment = wp_get_attachment_image_src( $agent_image_id, $size );

			// Build HTML image tag
			$agent_image = '<img src="' . $attachment[0] . '" width="' . $attachment[1] . '" height="' . $attachment[2] . '" alt="' . esc_attr( get_userdata( $user_id )->display_name ) . '" />';

		}

		// If no image, return false

		if ( empty( $agent_image ) )
			$agent_image = false;

		// Return agent image or false
		return apply_filters( 'wpsight_agent_image', $agent_image, $user_id, $size );

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
	 * get_agent_name()
	 *
	 * Return name of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @param string $name The name type to be returned (defaults to display_name)
	 * @uses get_userdata()
	 * @uses get_user_meta()
	 * @return string|bool $agent_name Agent name or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_name( $user_id, $name = 'display_name' ) {

		$agent_name = '';

		// Get user object
		$user = get_userdata( $user_id );

		if( in_array( $name, array( 'display_name', 'user_login', 'user_nicename' ) ) ) {
			$agent_name = $user->$name;
		} elseif( in_array( $name, array( 'first_name', 'last_name', 'nickname' ) ) ) {
			$agent_name = get_user_meta( $user_id, $name, true );
		}

		// If no name, return false

		if ( empty( $agent_name ) )
			$agent_name = false;

		// Return agent name or false
		return apply_filters( 'wpsight_agent_name', $agent_name, $user_id, $name );

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
	 * get_agent_company()
	 *
	 * Return company of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @uses get_user_meta()
	 * @return string|bool $agent_company Agent company or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_company( $user_id ) {

		$agent_company = '';

		// Get agent company from user meta
		$agent_company = get_user_meta( $user_id, 'company', true );

		// If no name, return false

		if ( empty( $agent_company ) )
			$agent_company = false;

		// Return agent name or false
		return apply_filters( 'wpsight_agent_company', $agent_company, $user_id );

	}

	/**
	 * get_listing_agent_description()
	 *
	 * Return agent description of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @uses wpsight_format_content()
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
	 * get_agent_description()
	 *
	 * Return description of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @uses get_user_meta()
	 * @uses wpsight_format_content()
	 * @return string|bool $agent_description Agent description or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_description( $user_id ) {
		
		// Get agent description from user meta
		$description = get_user_meta( $user_id, 'description', true );

		// If not empty save formatted description, else false
		$agent_description = ! empty( $description ) ? wpsight_format_content( $description ) : false;

		// Return agent description or false
		return apply_filters( 'wpsight_agent_description', $agent_description, $user_id );

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
	 * get_agent_website()
	 *
	 * Return website of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @uses get_userdata()
	 * @return string|bool $agent_website Agent website or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_website( $user_id ) {

		// Get agent website from user data
		$agent_website = esc_url( get_userdata( $user_id )->user_url );

		// If no website, return false

		if ( empty( $agent_website ) )
			$agent_website = false;

		// Return agent website or false
		return apply_filters( 'wpsight_agent_website', $agent_website, $user_id );

	}

	/**
	 * get_listing_agent_phone()
	 *
	 * Return agent phone of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post Post ID or object of required listing (defaults to null = current listing)
	 * @return string|bool $agent_phone Agent phone of the listing agent or false
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_agent_phone( $post = null ) {

		$agent_phone = '';

		// Get post object
		$post = get_post( $post );

		// Get agent phone from post meta
		$agent_phone = $post->_agent_phone ? sanitize_text_field( $post->_agent_phone ) : false;

		// Return agent website or false
		return apply_filters( 'wpsight_listing_agent_phone', $agent_phone, $post );

	}

	/**
	 * get_agent_phone()
	 *
	 * Return phone a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @uses get_user_meta()
	 * @return string|bool $agent_phone Agent phone or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_phone( $user_id ) {
		
		// Get agent description from user meta
		$phone = get_user_meta( $user_id, 'phone', true );

		// Set sanitized phone or false
		$agent_phone = $phone ? sanitize_text_field( $phone ) : false;

		// Return agent website or false
		return apply_filters( 'wpsight_agent_phone', $agent_phone, $user_id );

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
		$agent_twitter = $post->_agent_twitter ? sanitize_text_field( $post->_agent_twitter ) : false;

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
	 * get_agent_twitter()
	 *
	 * Return twitter of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @param string  $return Return Twitter user or URL (defaults to 'user' - can be 'url')
	 * @uses get_user_meta()
	 * @return string|bool $agent_twitter Agent twitter or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_twitter( $user_id, $return = 'user' ) {

		// Get agent twitter from user meta
		$twitter = get_user_meta( $user_id, 'twitter', true );
		
		// Set sanitized twitter or false
		$agent_twitter = $twitter ? sanitize_text_field( $twitter ) : false;

		// Remove @ to get username only

		if ( strpos( $agent_twitter, '@' ) === 0 )
			$agent_twitter = substr( $agent_twitter, 1 );

		if ( $return == 'url' ) {
			// If no twitter, return false. Else prepend twitter.com if URL requested
			$agent_twitter = empty( $agent_twitter ) ? false : esc_url( 'https://twitter.com/' . $agent_twitter );
		}

		// Return agent twitter or false
		return apply_filters( 'wpsight_agent_twitter', $agent_twitter, $user_id, $return );

	}

	/**
	 * get_listing_agent_facebook()
	 *
	 * Return agent facebook of the
	 * current or a specific listing.
	 *
	 * @param integer|object $post   Post ID or object of required listing (defaults to null = current listing)
	 * @param string  $return Return Facebook user or URL (defaults to 'user' - can be 'url')
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
	 * get_agent_facebook()
	 *
	 * Return facebook of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @param string  $return Return Facebook user or URL (defaults to 'user' - can be 'url')
	 * @uses get_user_meta()
	 * @return string|bool $agent_facebook Agent facebook or false
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_facebook( $user_id, $return = 'user' ) {

		// Get agent twitter from user meta
		$facebook = get_user_meta( $user_id, 'facebook', true );
		
		// Set sanitized facebook or false
		$agent_facebook = $facebook ? sanitize_text_field( $facebook ) : false;

		if ( $return == 'url' ) {
			// If no facebook, return false. Else prepend facebook.com if URL requested
			$agent_facebook = empty( $agent_facebook ) ? false : esc_url( 'https://www.facebook.com/' . $agent_facebook );
		}

		// Return agent twitter or false
		return apply_filters( 'wpsight_listing_agent_facebook', $agent_facebook, $user_id, $return );

	}

	/**
	 * get_listing_agent_archive()
	 *
	 * Get listing agent archive by adding
	 * "listings=1" to get_author_posts_url().
	 *
	 * @param integer|object $post    Post ID or object of required listing (defaults to null = current listing)
	 * @param integer $user_id User ID of the corresponding agent (defaults to post_author)
	 * @uses get_author_posts_url()
	 * @uses add_query_arg()
	 * @return string $agent_archive Author posts URL with additional query arg "listings=1"
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
	 * get_agent_archive()
	 *
	 * Get archive link of a specific agent.
	 *
	 * @param integer $user_id User ID of corresponding agent (required)
	 * @uses get_author_posts_url()
	 * @uses add_query_arg()
	 * @return string $agent_archive Author posts URL with additional query arg "listings=1"
	 *
	 * @since 1.0.0
	 */
	public static function get_agent_archive( $user_id ) {

		// Add listings=1 to get_author_posts_url()
		$agent_archive = esc_url( add_query_arg( 'listings', '1', get_author_posts_url( absint( $user_id ) ) ) );

		// Return agent archive link or false
		return apply_filters( 'wpsight_get_listing_agent_archive', $agent_archive, $user_id );

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
	 * author_avatar()
	 *
	 * Filter get_avatar to optionally
	 * replace avatar with custom agent image.
	 *
	 * @param mixed $avatar Image tag for the user's avatar
	 * @param integer|string $id_or_email A user ID, email address, or comment object
	 * @param integer $size Square avatar width and height in pixels to retrieve
	 * @param string $alt Alternative text to use in the avatar image tag
	 * @uses get_user_by()
	 * @uses self::get_agent_image()
	 *
	 * @since 1.0.0
	 */
	function agent_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

	    $user = false;
	
	    if ( is_numeric( $id_or_email ) ) {
	        $id = (int) $id_or_email;
	        $user = get_user_by( 'id' , $id );
	    } elseif ( is_object( $id_or_email ) ) {	
	        if ( ! empty( $id_or_email->user_id ) ) {
	            $id = (int) $id_or_email->user_id;
	            $user = get_user_by( 'id' , $id );
	        }	
	    } else {
	        $user = get_user_by( 'email', $id_or_email );	
	    }
	
	    if ( $user && is_object( $user ) && self::get_agent_image( $user->ID ) )
			$avatar = self::get_agent_image( $user->ID, array( $size, $size ) );
	
	    return apply_filters( 'wpsight_agent_avatar', $avatar, $user, $size );

	}
	
	/**
	 * get_user_posts_by_type()
	 *
	 * Return posts created by a specific author.
	 *
	 * @param integer $user_id ID of the corresponding user
	 * @param string $post_type Post type
	 * @uses get_post_types()
	 * @uses get_current_user_id()
	 * @uses get_posts_by_author_sql()
	 * @uses $wpdb->get_col()
	 * @return array $post_ids Array of post IDs
	 *
	 * @since 1.0.0
	 */	
	public static function get_user_posts_by_type( $user_id = false, $post_type = 'post' ) {
		global $wpdb;
		
		// Stop if post type not valid
		
		if( ! in_array( $post_type, get_post_types() ) )
			return false;
		
		// Set user ID
		$user_id = $user_id === false ? get_current_user_id() : absint( $user_id );
		
		// Author SQL
		$where = get_posts_by_author_sql( $post_type, true, $user_id, false );
		
		// Set SQL query
		$query = "SELECT ID FROM $wpdb->posts $where";
		
		// Get post IDs
		$post_ids = $wpdb->get_col( $query );
		
		return apply_filters( 'wpsight_get_user_posts_by_type', $post_ids, $user_id );
	
	}

	/**
	 * profile_contact_fields()
	 *
	 * Return user contact fields
	 *
	 * @return array $fields Array of contact fields
	 *
	 * @since 1.0.0
	 */
	public static function profile_contact_fields() {

		$fields = array(
			'company'   => array(
				'label' => __( 'Company', 'wpcasa' )
			),
			'phone'     => array(
				'label' => __( 'Phone', 'wpcasa' )
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
