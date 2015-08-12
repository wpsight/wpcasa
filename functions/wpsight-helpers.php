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
	return apply_filters( 'wpsight_post_type', 'listing' );
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
	
	$is = false;
	
	if( is_singular( wpsight_post_type() ) )
		$is = true;
	
	return apply_filters( 'wpsight_is_listing_single', $is );
	
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
	global $wp_query;
	
	if( $query === null )
		$query = $wp_query;
	
	$is = false;
	
	if( ! is_admin() && $query->is_author && $query->is_main_query() && isset( $_REQUEST['listings'] ) && $_REQUEST['listings'] == 1 )
		$is = true;
	
	return apply_filters( 'wpsight_is_listing_agent_archive', $is, $query );
	
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
	global $wp_query;
	
	if( $query === null )
		$query = $wp_query;
	
	$is = false;
	
	if( is_tax( get_object_taxonomies( wpsight_post_type() ) ) || wpsight_is_listing_agent_archive( $query ) )
		$is = true;
	
	return apply_filters( 'wpsight_is_listing_archive', $is, $query );
	
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
	
		// Get wpSight options
	    $options = get_option( WPSIGHT_DOMAIN );
	    
	    // When option is set, return it
	    
	    if( isset( $options[$name] ) )
			return $options[$name];
		
		// Option is not set, but default is true
	    
	    if( $default === true ) {
		    
		    // Get default options
		    $defaults = wpsight_options_defaults();
		    
		    // When default is set, return it
		    
		    if( isset( $defaults[$name] ) )
		    	return $defaults[$name];
		    
		    // If no default, return false
		    return false;
		    
	    }
	    
	    // When default is not empty, return it
	    
	    if( ! empty( $default ) )		    
			return $default;
		
		// If nothing matches, return false
		return false;

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
		
		// Get wpSight options
	    $options = get_option( WPSIGHT_DOMAIN );
	    
	    // Add new option to array
	    $options[$name] = $value;
	    
	    // Update option with new array
	    update_option( WPSIGHT_DOMAIN, $options );
	    
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
		
		// Get wpSight options
	    $options = get_option( WPSIGHT_DOMAIN );
	    
	    if( isset( $options[$name] ) ) {
	    	
	    	// Remove option from array
	    	unset( $options[$name] );
	    	
	    	// Update option with new array
			update_option( WPSIGHT_DOMAIN, $options );
	    
	    }
	    
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

	// Get wpSight options
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
 * Helper function to get taxonomy name
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_tax_name' ) ) {

	function wpsight_get_tax_name() {	
		global $post;
	
		// loop through custom taxonomies	
		
		$current_term = array();
		
		$args = array(
		  'public'   => true,
		  '_builtin' => false		  
		);
		
		foreach( get_taxonomies( $args ) as $taxonomy ) {
		    $current_term[] = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
		}
		
		// remove empty to get current taxonomy	
		
		foreach( $current_term as $key => $value ) {
		    if( $value == '' ) {
		    	unset( $current_term[$key] );
		    }
		}
		
		$current_term = array_values( $current_term );
		
		return $current_term[0]->name;
		
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
	
		if( ! $content )
			return;
			
		$content = do_shortcode( shortcode_unautop( wpautop( convert_chars( convert_smilies( wptexturize( $content ) ) ) ) ) );
		
		return apply_filters( 'wpsight_format_content', $content );
	
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
	
		$string = str_replace( '_', '-', $string );
		
		return $string;
	
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
	
		$string = str_replace( '-', '_', $string );
		
		return $string;
	
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

}

// Make function pluggable/overwritable
if ( ! function_exists( 'in_multiarray' ) ) {

	function in_multiarray( $elem, $array ) {

	    // if the $array is an array or is an object
	     if( is_array( $array ) || is_object( $array ) )
	     {
	         // if $elem is in $array object
	         if( is_object( $array ) )
	         {
	             $temp_array = get_object_vars( $array );
	             if( in_array( $elem, $temp_array ) )
	                 return TRUE;
	         }
	       
	         // if $elem is in $array return true
	         if( is_array( $array ) && in_array( $elem, $array ) )
	             return TRUE;
	           
	       
	         // if $elem isn't in $array, then check foreach element
	         foreach( $array as $array_element )
	         {
	             // if $array_element is an array or is an object call the in_multiarray function to this element
	             // if in_multiarray returns TRUE, than the element is in array, else check next element
	             if( ( is_array( $array_element ) || is_object( $array_element ) ) && in_multiarray( $elem, $array_element ) )
	             {
	                 return TRUE;
	                 exit;
	             }
	         }
	     }
	   
	     // if isn't in array return FALSE
	     return FALSE;
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

	if( ! is_array( $array ) )
		return;

	// Sort array by priority
        
    $priority = array();
    
	foreach ( $array as $key => $row ) {
		
		if( isset( $row['position'] ) ) {
			$row['priority'] = $row['position'];
			unset( $row['position'] );
		}
		
		$priority[$key] = isset( $row['priority'] ) ? absint( $row['priority'] ) : false;
	}
	
	array_multisort( $priority, $order, $array );
	
	return apply_filters( 'wpsight_sort_array_by_priority', $array, $order );

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
	
	   	$arr_keys   = array_keys( $arr ); 
	   	$arr_values = array_values( $arr );
	   	
	   	$keys 	= implode( $glue, $arr_keys );
	   	$values = implode( $glue, $arr_values );
	
	   	return( $keys . $glue . $values ); 
	
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
	 
	   	$arr  = explode( $glue, $str );
	   	$size = count( $arr ); 
	
	   	for ( $i=0; $i < $size/2; $i++ ) 
	   	    $out[$arr[$i]] = $arr[$i+($size/2)]; 
	   	
	   	return( $out ); 
	}

}

/**
 * Helper function to display
 * theme_mods CSS
 *
 * @since 1.2
 */
 
function wpsight_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false ) {

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
 * Helper function to allow
 * DECIMAL precision (hacky)
 *
 * @since 1.2
 */

add_filter( 'get_meta_sql','wpsight_cast_decimal_precision' );

function wpsight_cast_decimal_precision( $sql ) {

    $sql['where'] = str_replace( 'DECIMAL','DECIMAL(10,2)', $sql['where'] );

    return $sql;
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
	
		// Check taxonomy
		if( ! taxonomy_exists( $taxonomy ) )
			return;
	
	    $object_terms = get_the_terms( $post_id, $taxonomy );
	    
	    // If there are more than one terms, sort them
	    
		if( count( $object_terms ) > 1 ) {
	    
	    	$parents_assembled_array = array();
	    	
	    	if ( ! empty( $object_terms ) ) {
	    	    foreach ( $object_terms as $term ) {	        	
	    	        $parents_assembled_array[$term->parent][] = $term;
	    	    }
	    	}
	    	
	    	$object_terms = wpsight_sort_taxonomies_by_parents( $parents_assembled_array );
	    
	    }

		// Create terms list
	    $term_list = wpsight_get_the_term_list_links( $taxonomy, $object_terms, $term_before, $term_after, $linked );
	    
	    // Reorder if required
	    if ( $reverse )
	        $term_list = array_reverse( $term_list );
	
	    $result = implode( $sep, $term_list );
	
	    return $result;
	}

}

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_sort_taxonomies_by_parents' ) ) {

	function wpsight_sort_taxonomies_by_parents( $data, $parent_id = 0 ) {
	
	    if ( isset( $data[$parent_id] ) ) {
	
	        if ( ! empty( $data[$parent_id] ) ) {
	            foreach ( $data[$parent_id] as $key => $taxonomy_object ) {
	                if ( isset( $data[$taxonomy_object->term_id] ) ) {
	                    $data[$parent_id][$key]->childs = wpsight_sort_taxonomies_by_parents( $data, $taxonomy_object->term_id );
	                }
	            }
	            return $data[$parent_id];
	        }
	    }
	
	    return array();
	}

}

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_get_the_term_list_links' ) ) {

	function wpsight_get_the_term_list_links( $taxonomy, $data, $term_before = '', $term_after = '', $linked = 'true' ) {
		
		$result = array();
		
	    if ( ! empty( $data ) ) {
	
	        foreach ( $data as $term ) {
	        
	        	if( $linked === true ) {
	            	$result_term = '<a rel="tag" class="listing-term listing-term-' . $term->slug . '" href="' . get_term_link( $term->slug, $taxonomy ) . '">' . $term->name . '</a>';
	            } else {
	            	$result_term = '<span class="listing-term listing-term-' . $term->slug . '">' . $term->name . '</span>';
	            }
	            
            	$result[] = '<span class="listing-term-wrap listing-term-' . $term->slug . '-wrap">' . $term_before . $result_term . $term_after . '</span>';
	            
	            if ( ! empty( $term->childs ) ) {
	
	                $res = wpsight_get_the_term_list_links( $taxonomy, $term->childs, $term_before, $term_after, $linked );
	                
	                if ( ! empty( $res ) ) {
	
	                    foreach ($res as $val) {
	                        if (!is_array($val)) {
	                            $result[] = $val;
	                        }
	                    } // endforeach
	
	                } // endif
	
	            } // endif
	
	        } // endforeach
	
	    } // endif
	
	    return $result;
	}

}

class WPSight_Walker_TaxonomyDropdown extends Walker_CategoryDropdown {
 
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    
        $pad = str_repeat( '&#45;', $depth );
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
 
        if( ! isset( $args['value'] ) ) {
            $args['value'] = ( $category->taxonomy != 'category' ? 'slug' : 'id' );
        }
 
        $value = ( $args['value']=='slug' ? $category->slug : $category->term_id );
 
        $output .= "\t<option class=\"level-$depth\" value=\"".$value."\"";
        if ( $value === (string) $args['selected'] ){ 
            $output .= ' selected="selected"';
        }
        $output .= '>';
        if( ! empty( $pad ) )
        	$pad = $pad . ' ';
        $output .= $pad . $cat_name;
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. $category->count .')';
 
        $output .= "</option>\n";

	}
 
}

add_filter( 'wp_dropdown_cats', 'wpsight_wp_dropdown_cats', 20, 2 );

function wpsight_wp_dropdown_cats( $output, $r ) {
        
	$output = str_replace( "value='-1'", "value=''", $output );
	
	return $output;

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
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) ); 
		
	    return isset( $attachment[0] ) ? $attachment[0] : false;

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
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) ); 
	
		$image = wp_get_attachment_image_src( $attachment[0], $size );

		return $image[0];

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

		// Check if gallery has already been imported
		$gallery_imported = get_post_meta( $listing_id, '_gallery_imported', true );
		
		if( ! $gallery_imported ) {
		
			// Check existing gallery
			$gallery = get_post_meta( $listing_id, '_gallery' );
			
			// Get all image attachments
			
			$attachments = get_posts(
				array(
					'post_type' 	 => 'attachment',
					'posts_per_page' => -1,
					'post_parent' 	 => $listing_id,
					'post_mime_type' => 'image',
					'orderby'		 => 'menu_order'
				)
			);
			
			/**
			 * If still no gallery is available and it
			 * hasn't been imported yet, but there are
			 * attachments, create gallery custom fields
			 * with attachment IDs.
			 */
			
			if( ! $gallery && $attachments ) {
				
				// Loop through attachments
				
				foreach( $attachments as $attachment )
					// Create gallery post meta with attachment ID
					add_post_meta( $listing_id, '_gallery', $attachment->ID );
				
				// Mark gallery as imported
				add_post_meta( $listing_id, '_gallery_imported', '1' );
				
				return true;
			
			}
		
		}
		
		return false;

	}

}
