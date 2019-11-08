<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_Helpers class
 */
class WPSight_Helpers {

	/**
	 * Constructor
	 */
	function __construct() {
		add_filter( 'get_meta_sql', array( $this, 'cast_decimal_precision' ) );
		add_action( 'init', array( $this, 'check_gallery_upgrade' ) );
	}

	/**
	 * post_type()
	 *
	 * Helper function that returns the
	 * post type used in the framework.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function post_type() {
		return apply_filters( 'wpsight_post_type', 'listing' );
	}

	/**
	 * taxonomies()
	 *
	 * Helper function that returns the
	 * taxonomies used in the framework.
	 *
	 * @return array
	 *
	 * @since 1.0.2
	 */
	public static function taxonomies( $output = 'objects' ) {
		
		// Set framework taxonomies

		$taxonomies = array(
			'location'			=> get_taxonomy( 'location' ),
			'feature'			=> get_taxonomy( 'feature' ),
			'listing-type'		=> get_taxonomy( 'listing-type' ),
			'listing-category'	=> get_taxonomy( 'listing-category' )
		);
		
		if( 'names' == $output )
			$taxonomies = array_keys( $taxonomies );
		
		return apply_filters( 'wpsight_taxonomies', $taxonomies, $output );

	}

	/**
	 * is_listing_single()
	 *
	 * Helper function that checks if
	 * we are on a single listing page.
	 *
	 * @uses wpsight_post_type()
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public static function is_listing_single() {

		$is = false;

		if ( is_singular( wpsight_post_type() ) )
			$is = true;

		return apply_filters( 'wpsight_is_listing_single', $is );

	}

	/**
	 * is_listing_agent_archive()
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
	public static function is_listing_agent_archive( $query = null ) {
		global $wp_query;

		if ( $query === null )
			$query = $wp_query;

		$is = false;

		if ( ! is_admin() && $query->is_author && $query->is_main_query() && isset( $_REQUEST['listings'] ) && $_REQUEST['listings'] == 1 )
			$is = true;

		return apply_filters( 'wpsight_is_listing_agent_archive', $is, $query );

	}

	/**
	 * is_listings_archive()
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
	public static function is_listings_archive( $query = null ) {
		global $wp_query;

		if ( $query === null )
			$query = $wp_query;

		$is = false;

		if ( is_tax( wpsight_taxonomies( 'names' ) ) || wpsight_is_listing_agent_archive( $query ) )
			$is = true;

		return apply_filters( 'wpsight_is_listings_archive', $is, $query );

	}

	/**
	 * get_option()
	 *
	 * Return theme option value.
	 *
	 * @param string  $name Key of the wpSight option
	 * @param bool|string Set (bool) true to return default from options array or string
	 * @uses get_option()
	 * @uses wpsight_options_defaults()
	 * @return bool|string False if no value was found or option value as string
	 *
	 * @since  1.0.0
	 */
	public static function get_option( $name, $default = '' ) {

		// Get WPSight options
		$options = get_option( WPSIGHT_DOMAIN );
		
		// Set return
		$return = false;

		// When option is set, return it

		if ( isset( $options[ $name ] ) ) {
			
			$return = $options[ $name ];

		} else {

			// Option is not set, but default is true
			
			if ( $default === true ) {
			
				// Get default options
				$defaults = self::options_defaults();
			
				// When default is set, return it, else false
				$return = isset( $defaults[ $name ] ) ? $defaults[ $name ] : false;
			
			} elseif ( ! empty( $default ) ) {
				
				// When default is not empty, return it
				$return = $default;

			}
		
		}

		// If nothing matches, return false
		return apply_filters( 'wpsight_get_option', $return, $name, $default );

	}

	/**
	 * add_option()
	 *
	 * Add a specific wpSight option
	 *
	 * @param string  $name  Key of the option to add
	 * @param mixed   $value Value of the option to add
	 * @uses get_option()
	 * @uses update_option()
	 *
	 * @since 1.0.0
	 */
	public static function add_option( $name, $value ) {

		// Get wpSight options
		$options = get_option( WPSIGHT_DOMAIN );

		// Add new option to array
		$options[$name] = $value;

		// Update option with new array
		update_option( WPSIGHT_DOMAIN, $options );

	}

	/**
	 * delete_option()
	 *
	 * Delete a specific wpSight option
	 *
	 * @param string  $name Key of the option to delete
	 * @uses get_option()
	 * @uses update_option()
	 *
	 * @since 1.0.0
	 */
	public static function delete_option( $name ) {

		// Get wpSight options
		$options = get_option( WPSIGHT_DOMAIN );

		if ( isset( $options[$name] ) ) {

			// Remove option from array
			unset( $options[$name] );

			// Update option with new array
			update_option( WPSIGHT_DOMAIN, $options );

		}

	}

	/**
	 * options_defaults()
	 *
	 * Get array of options with default values
	 *
	 * @uses wpsight_options()
	 * @see wpsight-admin.php
	 *
	 * @since 1.0.0
	 */
	public static function options_defaults() {

		// Get WPSight options
		$settings = wpsight_options();

		// Set up $defaults
		$defaults = array();

		// Loop through settings

		foreach ( $settings as $section ) {

			// Loop through sections

			foreach ( $section[1] as $option ) {

				// If no id, next one

				if ( ! isset( $option['id'] ) )
					continue;

				// If no default value, next one

				if ( ! isset( $option['default'] ) )
					continue;

				// If no type, next one

				if ( ! isset( $option['type'] ) )
					continue;

				// Add option if default is provided
				$defaults[$option['id']] = $option['default'];

			}
		}

		// Return options array with defaults
		return apply_filters( 'wpsight_options_defaults', $defaults );

	}

	/**
	 * get_tax_name()
	 *
	 * Helper function to get taxonomy name
	 *
	 * @uses get_taxonomies()
	 * @uses get_query_var()
	 * @uses get_term_by()
	 *
	 * @since 1.0.0
	 */
	public static function get_tax_name() {
		global $post;

		// loop through custom taxonomies

		$current_term = array();

		$args = array(
			'public'   => true,
			'_builtin' => false
		);

		foreach ( get_taxonomies( $args ) as $taxonomy ) {
			$current_term[] = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
		}

		// remove empty to get current taxonomy

		foreach ( $current_term as $key => $value ) {
			if ( $value == '' ) {
				unset( $current_term[$key] );
			}
		}

		$current_term = array_values( $current_term );

		return $current_term[0]->name;

	}

	/**
	 * format_content()
	 *
	 * Helper function to replace the_content filter
	 *
	 * @param string $content Content to be formatted
	 * @uses wptexturize()
	 * @uses convert_smilies()
	 * @uses convert_chars()
	 * @uses wpautop()
	 * @uses shortcode_unautop()
	 * @uses do_shortcode()
	 *
	 * @since 1.0.0
	 */
	public static function format_content( $content ) {

		if ( ! $content )
			return;

		$formatted = do_shortcode( shortcode_unautop( wpautop( convert_chars( convert_smilies( wptexturize( $content ) ) ) ) ) );

		return apply_filters( 'wpsight_format_content', $formatted, $content );

	}

	/**
	 * dashes()
	 *
	 * Helper function to convert underscores to dashes
	 *
	 * @since 1.0.0
	 */
	public static function dashes( $string ) {
		return str_replace( '_', '-', $string );
	}

	/**
	 * underscores()
	 *
	 * Helper function to convert dashes to underscores
	 *
	 * @since 1.0.0
	 */
	public static function underscores( $string ) {
		return str_replace( '-', '_', $string );
	}

	/**
	 * array_empty()
	 *
	 * Helper function to check multi-dimensional arrays
	 *
	 * @since 1.0.0
	 */
	public static function array_empty( $mixed ) {
		if ( is_array( $mixed ) ) {
			foreach ( $mixed as $value ) {
				if ( ! array_empty( $value ) ) {
					return false;
				}
			}
		}
		elseif ( ! empty( $mixed ) ) {
			return false;
		}
		return true;
	}

	/**
	 * in_multiarray()
	 *
	 * Helper function to check multi-dimensional arrays
	 *
	 * @since 1.0.0
	 */
	public static function in_multiarray( $elem, $array ) {

		// if the $array is an array or is an object
		if ( is_array( $array ) || is_object( $array ) ) {
			// if $elem is in $array object
			if ( is_object( $array ) ) {
				$temp_array = get_object_vars( $array );
				if ( in_array( $elem, $temp_array ) )
					return TRUE;
			}

			// if $elem is in $array return true
			if ( is_array( $array ) && in_array( $elem, $array ) )
				return TRUE;


			// if $elem isn't in $array, then check foreach element
			foreach ( $array as $array_element ) {
				// if $array_element is an array or is an object call the in_multiarray function to this element
				// if in_multiarray returns TRUE, than the element is in array, else check next element
				if ( ( is_array( $array_element ) || is_object( $array_element ) ) && in_multiarray( $elem, $array_element ) ) {
					return TRUE;
					exit;
				}
			}
		}

		// if isn't in array return FALSE
		return FALSE;
	}

	/**
	 * sort_array_by_priority()
	 *
	 * Helper function to sort array by position key
	 *
	 * @param array $array Array to be sorted
	 * @param mixed $order Sort options
	 * @see http://docs.php.net/manual/en/function.array-multisort.php
	 * @return array Sorted array
	 *
	 * @since 1.0.0
	 */
	public static function sort_array_by_priority( $array = array(), $order = SORT_NUMERIC ) {

		if ( ! is_array( $array ) )
			return;

		// Sort array by priority

		$priority = array();

		foreach ( $array as $key => $row ) {

			if ( isset( $row['position'] ) ) {
				$row['priority'] = $row['position'];
				unset( $row['position'] );
			}

			$priority[$key] = isset( $row['priority'] ) ? absint( $row['priority'] ) : false;
		}

		array_multisort( $priority, $order, $array );

		return apply_filters( 'wpsight_sort_array_by_priority', $array, $order );

	}

	// Ensure backwards compatibility with wpsight_sort_array_by_position()

	public static function sort_array_by_position( $array = array(), $order = SORT_NUMERIC ) {
		return wpsight_sort_array_by_priority( $array, $order );
	}

	/**
	 * implode_array()
	 *
	 * Implode an array with the key and value pair
	 *
	 * @since 1.0.0
	 */
	public static function implode_array( $glue, $arr ) {

		$arr_keys   = array_keys( $arr );
		$arr_values = array_values( $arr );

		$keys  = implode( $glue, $arr_keys );
		$values = implode( $glue, $arr_values );

		return $keys . $glue . $values;

	}

	/**
	 * explode_array()
	 *
	 * Explode string to associative array
	 *
	 * @since 1.0.0
	 */
	public static function explode_array( $glue, $str ) {

		$arr  = explode( $glue, $str );
		$size = count( $arr );

		for ( $i=0; $i < $size/2; $i++ )
			$out[$arr[$i]] = $arr[$i+( $size/2 )];

		return $out;
	}

	/**
	 * generate_css()
	 *
	 * Helper function to display
	 * theme_mods CSS
	 *
	 * @param string $selector CSS selector
	 * @param string $style CSS style
	 * @param string $mod_name Name of theme_mod
	 * @param string $prefix
	 * @param string $postfix
	 * @param bool $echo Echo (true) or return (false)
	 * @uses get_theme_mod()
	 * @return Inline CSS for theme_mods
	 *
	 * @since 1.0.0
	 */
	public static function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false ) {

		$output = '';
		$mod = get_theme_mod( $mod_name );

		if ( ! empty( $mod ) ) {

			$output = "\n\t" . sprintf( '%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix ) . "\n";

			if ( $echo )
				echo $output;
		}

		return $output;

	}

	/**
	 * cast_decimal_precision()
	 *
	 * Helper function to allow
	 * DECIMAL precision (hacky)
	 *
	 * @param string $sql SQL
	 * @uses WPSight_Helpers::cast_decimal_precision()
	 *
	 * @since 1.0.0
	 */
	public static function cast_decimal_precision( $sql ) {

		$sql['where'] = str_replace( 'DECIMAL', 'DECIMAL(10,2)', $sql['where'] );

		return $sql;
	}

	/**
	 * get_the_term_list()
	 *
	 * Helper functions to return taxonomy
	 * terms ordered by hierarchy
	 *
	 * @param integer $post_id Post ID of specific listing
	 * @param string $taxonomy Taxonomy
	 * @param string $sep Separator between terms
	 * @param string $term_before String before each term
	 * @param string $term_after String after each term
	 * @param bool $linked Link terms to their term archives
	 * @param bool $reverse Reverse order of terms
	 * @uses taxonomy_exists()
	 * @uses get_the_terms()
	 * @uses self::sort_taxonomies_by_parents()
	 * @uses self::get_the_term_list_links()
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function get_the_term_list( $post_id, $taxonomy, $sep = '', $term_before = '', $term_after = '', $linked = true, $reverse = false ) {

		// Check taxonomy
		if ( ! taxonomy_exists( $taxonomy ) )
			return;

		$object_terms = get_the_terms( $post_id, $taxonomy );

		// If there are more than one terms, sort them

		if ( is_array( $object_terms ) && count( $object_terms ) > 1 ) {

			$parents_assembled_array = array();

			if ( ! empty( $object_terms ) ) {
				foreach ( $object_terms as $term ) {
					$parents_assembled_array[$term->parent][] = $term;
				}
			}

			$object_terms = self::sort_taxonomies_by_parents( $parents_assembled_array );

		}

		// Create terms list
		$term_list = self::get_the_term_list_links( $taxonomy, $object_terms, $term_before, $term_after, $linked );

		// Reorder if required
		if ( $reverse )
			$term_list = array_reverse( $term_list );

		$result = implode( $sep, $term_list );

		return $result;
	}

	/**
	 * sort_taxonomies_by_parents()
	 *
	 * @param array $data
	 * @param integer $parent_id
	 *
	 * @return array
	 */
	public static function sort_taxonomies_by_parents( $data, $parent_id = 0 ) {

		if ( isset( $data[$parent_id] ) ) {

			if ( ! empty( $data[$parent_id] ) ) {
				foreach ( $data[$parent_id] as $key => $taxonomy_object ) {
					if ( isset( $data[$taxonomy_object->term_id] ) ) {
						$data[$parent_id][$key]->childs = self::sort_taxonomies_by_parents( $data, $taxonomy_object->term_id );
					}
				}
				return $data[$parent_id];
			}
		}

		return array();
	}

	/**
	 * get_the_term_list_links()
	 *
	 * Returns a list of linked terms
	 *
	 * @param string $taxonomy
	 * @param array $data
	 * @param string $term_before
	 * @param string $term_after
	 * @param string $linked
	 *
	 * @return  array
	 */
	public static function get_the_term_list_links( $taxonomy, $data, $term_before = '', $term_after = '', $linked = 'true' ) {

		$result = array();

		if ( ! empty( $data ) ) {

			foreach ( $data as $term ) {

				if ( $linked === true ) {
					$result_term = '<a rel="tag" class="listing-term listing-term-' . $term->slug . '" href="' . get_term_link( $term->slug, $taxonomy ) . '">' . $term->name . '</a>';
				} else {
					$result_term = '<span class="listing-term listing-term-' . $term->slug . '">' . $term->name . '</span>';
				}

				$result[] = '<span class="listing-term-wrap listing-term-' . $term->slug . '-wrap">' . $term_before . $result_term . $term_after . '</span>';

				if ( ! empty( $term->childs ) ) {

					$res = self::get_the_term_list_links( $taxonomy, $term->childs, $term_before, $term_after, $linked );

					if ( ! empty( $res ) ) {

						foreach ( $res as $val ) {
							if ( !is_array( $val ) ) {
								$result[] = $val;
							}
						} // endforeach

					} // endif

				} // endif

			} // endforeach

		} // endif

		return $result;
	}

	/**
	 * get_attachment_id_by_url()
	 *
	 * Helper functions to get attachment ID by URL.
	 *
	 * @param string $url Image attachment URL
	 * @uses WPSight_Helpers::get_attachment_id_by_url()
	 * @return integer Attachment ID
	 * @credit https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 *
	 * @since 1.0.0
	 */
	public static function get_attachment_id_by_url( $url ) {
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) );

		return isset( $attachment[0] ) ? $attachment[0] : false;

	}

	/**
	 * get_attachment_by_url()
	 *
	 * Helper functions to get attachment by URL.
	 *
	 * @param string $url Image attachment URL
	 * @param string|array WordPress image size or custom with and height in array
	 * @uses WPSight_Helpers::get_attachment_by_url()
	 * @return string Attachment URL
	 * @credit https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 *
	 * @since 1.0.0
	 */
	public static function get_attachment_by_url( $url, $size = 'thumbnail' ) {
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) );

		$image = wp_get_attachment_image_src( $attachment[0], $size );

		return $image[0];

	}

	/**
	 * check_gallery_upgrade()
	 *
	 * Helper function to run image
	 * gallery update on all listings.
	 *
	 * @uses wpsight_get_option()
	 * @uses get_posts()
	 * @uses wpsight_post_type()
	 * @uses get_post_stati()
	 * @uses wpsight_maybe_update_gallery()
	 * @uses wpsight_add_option()
	 *
	 * @since 1.0.0
	 */
	public function check_gallery_upgrade() {

		// run the upgrade only once per site
		if ( false === wpsight_get_option( 'wpsight_gallery_upgraded', false ) ) {

			$listings = get_posts( array( 
				'no_paging'   => true, 
				'post_type'   => wpsight_post_type(), 
				'post_status' => get_post_stati(),
				'fields'      => 'ids'
			) );

			foreach ( $listings as $listing_id )
				self::maybe_update_gallery( $listing_id );
			
			// save the option for future checks
			wpsight_add_option( 'wpsight_gallery_upgraded', true );

		}
		
	}

	/**
	 * maybe_update_gallery()
	 *
	 * Helper function to update image gallery.
	 *
	 * Checks if the gallery is already in the new format (array of IDs and URLs) or the old one (array of IDs)
	 * If the gallery is empty it uses all images attached to the listing
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @uses get_post_meta()
	 * @uses get_posts()
	 * @uses get_post_meta()
	 * @uses delete_post_meta()
	 * @uses update_post_meta()
	 * @uses wp_get_attachment_image_src()
	 *
	 * @since 1.0.0
	 */
	public static function maybe_update_gallery( $listing_id ) {
		
		// Check if gallery has already been imported
		$gallery_imported = get_post_meta( $listing_id, '_gallery_imported', true );
		
		if( ! $gallery_imported ) {
		
			// Check existing gallery
			$gallery = get_post_meta( $listing_id, '_gallery', true );
			
			// Get all image attachments
			
			$attachments = get_posts(
				array(
					'post_type'      => 'attachment',
					'posts_per_page' => -1,
					'post_parent'    => $listing_id,
					'post_mime_type' => 'image',
					'orderby'        => 'menu_order',
					'fields'         => 'ids'
				)
			);
			
			/**
			 * If still no gallery is available and it
			 * hasn't been imported yet, but there are
			 * attachments, create gallery custom fields
			 * with attachment IDs.
			 */
			
			if( ! $gallery && $attachments ) {
				
				$gallery_ids = array();
			
				foreach ( $attachments as $attachment_id ) {
				
					$image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
				
					// check if the attachment exists
					if ( isset( $image_src[0] ) ) {
						// the new format expects $attachment_id as key and URL as value
						$gallery_ids[ $attachment_id ] = $image_src[0];
					}
				}
				
				if ( ! empty( $gallery_ids ) ) {
					return update_post_meta( $listing_id, '_gallery', $gallery_ids );
				}
			
			}
			
			// prevent future checks
			update_post_meta( $listing_id, '_gallery_imported', true );
		
		}
		
		return false;

	}

	/**
	 * user_can_edit_listing_id()
	 *
	 * Check if current user can edit the listing ID.
	 *
	 * @uses is_user_logged_in()
	 * @uses current_user_can()
	 * @return bool True if user is eligible, else false
	 *
	 * @since 1.0.0
	 */
	public static function user_can_edit_listing_id() {

		$can = false;

		if ( is_user_logged_in() )
			$can = current_user_can( 'edit_listing_id' );

		return apply_filters( 'wpsight_user_can_edit_listing_id', $can );

	}

}
