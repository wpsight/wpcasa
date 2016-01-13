<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Template class
 */
class WPSight_Template {

	/**
	 * locate_template()
	 *
	 * Locate a template and return the path
	 * for inclusion or load if desired.
	 *
	 * This is the load order:
	 *
	 *  /wp-content/themes/  theme (child) / $template_name
	 *
	 *	/wp-content/themes/  theme (child) / WPSIGHT_DOMAIN (e.g. wpcasa) / $template_name
	 *
	 *  /wp-content/themes/  theme (parent) / $template_name
	 *
	 *	/wp-content/themes/  theme (parent) / WPSIGHT_DOMAIN (e.g. wpcasa) / $template_name
	 *
	 *  $template_path (custom path from addon for example) / $template_name
	 *
	 *  /wp-content/plugins/  WPSIGHT_DOMAIN (e.g. wpcasa) / templates / $template_name
	 *
	 * @param string|array $template_names Template name (incl. file extension like .php)
	 * @param string $template_path Custom template path for plugins and addons (default: '')
	 * @param bool $load Call load_template() if true or return template path if false
	 * @uses trailingslashit()
	 * @uses get_stylesheet_directory()
	 * @uses get_template_directory()
	 * @uses wpsight_get_templates_dir()
	 * @return string $located Absolute path to template file (if $load is false)
	 *
	 * @since 1.0.0
	 */
	public static function locate_template( $template_names, $args = array(), $template_path = '', $load = false, $require_once = false ) {
		global $post, $wp_query, $wpdb;

		if ( $args && is_array( $args ) )
			extract( $args );

		// No file found yet
		$located = false;

		// Try to find a template file
		foreach ( (array) $template_names as $template_name ) {

			// Continue if template is empty
			if ( empty( $template_name ) )
				continue;

			// Trim off any slashes from the template name
			$template_name = ltrim( $template_name, '/' );

			// Check child theme
			if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $template_name ) ) {
				$located = trailingslashit( get_stylesheet_directory() ) . $template_name;
				break;

				// Check extra folder in child theme
			} elseif ( file_exists( trailingslashit( get_stylesheet_directory() . '/' . WPSIGHT_DOMAIN ) . $template_name ) ) {
				$located = trailingslashit( get_stylesheet_directory() . '/' . WPSIGHT_DOMAIN ) . $template_name;
				break;
			
				// Check parent theme
			} elseif ( file_exists( trailingslashit( get_template_directory() ) . $template_name ) ) {
				$located = trailingslashit( get_template_directory() ) . $template_name;
				break;
			
				// Check extra folder parent theme
			} elseif ( file_exists( trailingslashit( get_template_directory() . '/' . WPSIGHT_DOMAIN ) . $template_name ) ) {
				$located = trailingslashit( get_template_directory() . '/' . WPSIGHT_DOMAIN ) . $template_name;
				break;

				// Check custom path templates (e.g. from addons)
			} elseif ( file_exists( trailingslashit( $template_path ) . $template_name ) ) {
				$located = trailingslashit( $template_path ) . $template_name;
				break;

				// Check plugin templates
			} elseif ( file_exists( trailingslashit( wpsight_get_templates_dir() ) . $template_name ) ) {
				$located = trailingslashit( wpsight_get_templates_dir() ) . $template_name;
				break;
			}

		}

		$located = apply_filters( 'wpsight_locate_template', $located, $template_names, $template_path, $load, $require_once );

		// Load found template if required

		if ( ( true == $load ) && ! empty( $located ) ) {

			if ( $require_once )
				require_once $located;
			else
				require $located;

		}

		// Or return template file path
		return $located;
	}

	/**
	 * get_template_part()
	 *
	 * Load specific template part.
	 *
	 * @param string $slug The slug name for the generic template
	 * @param string $name The name of the specialized template
	 * @param string $template_path Custom template path for plugins and addons (default: '')
	 * @param bool $load Call load_template() if true or return template path if false
	 * @uses self::locate_template()
	 * @return string $located Absolute path to template file (if $load is false)
	 *
	 * @since 1.0.0
	 */
	public static function get_template_part( $slug, $name = null, $args = array(), $template_path = '', $load = true, $require_once = false ) {

		// Execute code for this part
		do_action( 'wpsight_get_template_part_' . $slug, $slug, $name, $args, $template_path, $load, $require_once );

		// Setup possible parts
		$templates = array();
		if ( isset( $name ) )
			$templates[] = $slug . '-' . $name . '.php';
		$templates[] = $slug . '.php';

		// Allow template parts to be filtered
		$templates = apply_filters( 'wpsight_get_template_part', $templates, $slug, $name, $args, $template_path, $load, $require_once );

		// Return the part that is found
		return self::locate_template( $templates, $args, $template_path, $load, $require_once );
	}

	/**
	 * get_orderby()
	 *
	 * Return orderby options for listing pages
	 *
	 * @param array $args Array of arguments
	 * @uses wpsight_get_template()
	 * @return string|bool $orderby HTML output or false if empty
	 *
	 * @since 1.0.0
	 */
	public static function get_orderby( $args = array() ) {

		$defaults = array(

			'type'   => 'select', // can be 'links'
			'orderby' => true,
			'order'   => true,
			'labels'  => array(
				'orderby'     => _x( 'Order by', 'listings panel actions', 'wpcasa' ),
				'date'        => _x( 'Date', 'listings panel actions', 'wpcasa' ),
				'orderby_sep' => _x( 'or', 'listings panel actions', 'wpcasa' ),
				'price'       => _x( 'Price', 'listings panel actions', 'wpcasa' ),
				'order'       => _x( 'Order', 'listings panel actions', 'wpcasa' ),
				'desc'        => _x( 'DESC', 'listings panel actions', 'wpcasa' ),
				'order_sep'   => _x( 'or', 'listings panel actions', 'wpcasa' ),
				'asc'         => _x( 'ASC', 'listings panel actions', 'wpcasa' ),
				'title'       => _x( 'Title', 'listings panel actions', 'wpcasa' )
			)

		);

		$args = wp_parse_args( $args, $defaults );

		$args = apply_filters( 'wpsight_get_orderby_args', $args );

		// Setup $orderby markup
		$orderby = '';

		if ( $args['orderby'] || $args['order'] ) {

			ob_start();
			
			// Get orderby template
			wpsight_get_template( 'listings-panel-' . sanitize_file_name( $args['type'] ) . '.php', array( 'args' => $args ) );

			$orderby = ob_get_clean();

		}

		if ( ! empty( $orderby ) )
			return $orderby;

		return false;

	}

	/**
	 * get_panel()
	 *
	 * Return formatted listings control panel (with title and order options)
	 *
	 * @uses wpsight_get_template()
	 * @return string|bool HTML markup of listings panel or false if empty
	 *
	 * @since 1.0.0
	 */
	public static function get_panel( $args = array() ) {

		$output = false;

		// If only one listing is called, don't show panel

		if ( ! isset( $args->query_vars['p'] ) || empty( $args->query_vars['p'] ) ) {

			ob_start();

			// Get panel template
			wpsight_get_template( 'listings-panel.php' );

			$output = ob_get_clean();

		}

		return apply_filters( 'wpsight_get_panel', $output, $args );

	}

	/**
	 * get_listing_title()
	 *
	 * Return formatted single listing
	 * title with title actions.
	 *
	 * @uses get_the_ID()
	 * @uses get_the_title()
	 * @uses wpsight_listing_actions()
	 * @return string HTML markup of listing title
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_title( $post_id = '', $actions = array() ) {

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		ob_start(); ?>

	    <div class="wpsight-listing-title clearfix">

		    <h1 class="entry-title">
				<?php echo get_the_title( $post_id ); ?>
			</h1>

		    <?php wpsight_listing_actions( $post_id, $actions ); ?>

		</div><?php

		return apply_filters( 'wpsight_get_listing_title', ob_get_clean(), $post_id, $actions );

	}

	/**
	 * get_archive_title()
	 *
	 * Return formatted listings archive title
	 *
	 * @return string Listings archive title for the given query
	 *
	 * @since 1.0.0
	 */
	public static function get_archive_title() {
		global $wp_query, $wpsight_query;

		if ( isset( $wpsight_query->found_posts ) ) {
			$title = '<span class="listings-panel-found">' . $wpsight_query->found_posts . '</span>' . ' ' . _nx( 'Listing', 'Listings', intval( $wpsight_query->found_posts ), 'archive title', 'wpcasa' );			
		} elseif ( isset( $wp_query->found_posts ) ) {
			$title = '<span class="listings-panel-found">' . $wp_query->found_posts . '</span>' . ' ' . _nx( 'Listing', 'Listings', intval( $wp_query->found_posts ), 'archive title', 'wpcasa' );
		} else {
			$title = __( 'Listings', 'wpcasa' );
		}

		return apply_filters( 'wpsight_get_archive_title', $title );

	}

	/**
	 * get_pagination()
	 *
	 * Return formatted listings pagination
	 *
	 * @param int $max_num_pages max_num_pages parameter of corresponding query
	 * @param array $args paginate_links() arguments
	 * @uses get_query_var()
	 * @uses is_rtl()
	 * @uses get_pagenum_link()
	 * @uses wp_parse_args()
	 * @return string|bool HTML markup of listings pagination or false if empty
	 *
	 * @since 1.0.0
	 */
	public static function get_pagination( $max_num_pages = '', $args = array() ) {

		// Check for max_num_pages

		if ( empty( $max_num_pages ) ) {
			global $wp_query;
			$total = $wp_query->max_num_pages;
		} else {
			$total = $max_num_pages;
		}

		// need an unlikely integer
		$big = 999999999;

		// Make sure paging works

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		
		// Set prev/next arrows
		
		$arr_prev = ! is_rtl() ? '&larr; ' : '&rarr; ';
		$arr_next = ! is_rtl() ? ' &rarr;' : ' &larr;';

		// Set paginate_links() defaults

		$defaults = array(
			'base'               => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format'             => '?paged=%#%',
			'current'            => max( 1, $paged ),
			'total'              => $total,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => 4,
			'prev_next'          => true,
			'prev_text'          => $arr_prev . __( 'Previous', 'wpcasa' ),
			'next_text'          => __( 'Next', 'wpcasa' ) . $arr_next,
			'type'               => 'list',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		);

		// Parse default with args
		$args = wp_parse_args( $args, $defaults );

		// Apply filter
		$args = apply_filters( 'wpsight_get_pagination_args', $args );

		// Execute paginate_links()
		$pagination = apply_filters( 'wpsight_get_pagination', paginate_links( $args ) );

		if ( ! empty( $pagination ) )
			return '<div class="wpsight-pagination">' . $pagination . '</div><!-- .wpsight-pagination -->';

		return false;

	}

	/**
	 * get_listing_class()
	 *
	 * Get listing/post classes.
	 *
	 * @param string $class
	 * @param mixed $post_id
	 * @uses get_the_ID()
	 * @uses get_post()
	 * @uses wpsight_post_type()
	 * @uses wpsight_is_listing_sticky()
	 * @uses wpsight_is_listing_featured()
	 * @uses wpsight_is_listing_expired()
	 * @uses wpsight_is_listing_not_available()
	 * @return bool|array
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_class( $class = '', $post_id = false ) {

		if ( $post_id === false )
			$post_id = get_the_ID();

		$post = get_post( $post_id );

		if ( $post->post_type != wpsight_post_type() )
			return false;

		$classes = array();

		if ( empty( $post ) )
			return $classes;

		$classes[] = 'listing';

		if ( wpsight_is_listing_sticky( $post_id ) )
			$classes[] = 'listing-sticky';

		if ( wpsight_is_listing_featured( $post_id ) )
			$classes[] = 'listing-featured';

		if ( wpsight_is_listing_expired( $post_id ) )
			$classes[] = 'listing-expired';

		if ( wpsight_is_listing_not_available( $post_id ) )
			$classes[] = 'listing-not-available';

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		}

		return apply_filters( 'wpsight_get_listing_class', get_post_class( $classes, $post->ID ), $post_id, $class, $classes );

	}

	/**
	 * get_listing_actions()
	 *
	 * Get listing actions (save, print etc.) array.
	 *
	 * @uses get_permalink()
	 * @uses get_the_ID()
	 * @uses add_query_arg()
	 * @uses wpsight_sort_array_by_position()
	 * @return array Array of listing actions
	 *
	 * @since 1.0.0
	 */
	public static function get_listing_actions( $post_id = '' ) {

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		$actions = array(
			'print' => array(
				'label'    => __( 'Print', 'wpcasa' ),
				'name'     => false,
				'id'       => false,
				'class'    => 'actions-print action-link',
				'href'     => esc_url( add_query_arg( array(  'print' => absint( $post_id ) ), get_permalink( $post_id ) ) ),
				'target'   => false,
				'icon'     => false,
				'atts'     => false,
				'priority' => 20,
				'callback' => false
			)
		);

		// Apply filter, sort and return array
		return wpsight_sort_array_by_priority( apply_filters( 'wpsight_get_listing_actions', $actions, $post_id ) );

	}
	/**
	 * listing_actions()
	 *
	 * Return listing actions
	 *
	 * @param integer $post_id
	 * @param array $actions
	 * @uses get_the_ID()
	 * @uses post_password_required()
	 * @uses get_post_status()
	 * @uses wpsight_get_listing_actions()
	 * @uses wp_parse_args()
	 * @uses call_user_func()
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function listing_actions( $post_id = '', $actions = array() ) {

		$output = '';

		// Use global post ID if not defined

		if ( ! $post_id )
			$post_id = get_the_ID();

		if ( ! post_password_required( $post_id ) && 'publish' == get_post_status( $post_id ) ) {

			// Get listing actions
			$listing_actions = wpsight_get_listing_actions( $post_id );

			$listing_actions = wp_parse_args( $actions, $listing_actions );

			// Generate output
			$output .= '<div class="wpsight-listing-actions">';

			foreach ( $listing_actions as $action => $v ) {

				if ( ! is_array( $listing_actions[ $action ] ) )
					continue;

				$output .= '<div class="wpsight-listing-action ' . sanitize_html_class( 'wpsight-listing-action-' . $action ) . '">';

				// First check if we have a callback

				if ( isset( $v['callback'] ) && is_callable( sanitize_key( $v['callback'] ) ) ) {

					$output .= call_user_func( sanitize_key( $v['callback'] ), $v );

				} else {

					// Manage css class

					$css_class = '';

					if ( $v['class'] ) {

						$css_class = ! is_array( $v['class'] ) ? explode( ' ', $v['class'] ) : $v['class'];

						$css_class = array_map( 'sanitize_html_class', $css_class );
						$css_class = ' class="' . implode( ' ', $css_class ) . '"';

					}

					// Manage link target

					$target = '';

					if ( $v['target'] ) {

						$allowed_targets = array( '_blank', '_parent', '_top' );
						$target = in_array( $v['target'], $allowed_targets ) ? $v['target'] : '';

						if ( ! empty( $target ) )
							$target = ' target="' . $target . '"';

					}

					$output .= sprintf( '<a href="%1$s"%2$s%3$s%4$s>%5$s%6$s</a>', esc_url( $v['href'] ), esc_attr( $v['id'] ), $css_class, $target, wp_kses_post( $v['icon'] ), esc_html( $v['label'] ) );

				}

				$output .= '</div>';

			}

			$output .= '</div>';

		}

		return apply_filters( 'wpsight_listing_actions', $output );

	}

}
