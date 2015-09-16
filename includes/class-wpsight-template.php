<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Search class
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
	 *  /wp-content/themes/  theme (parent) / $template_name
	 *
	 *  $template_path (custom path from addon for example) / $template_name
	 *
	 *  /wp-content/plugins/  WPSIGHT_DOMAIN (e.g. wpcasa) / templates / $template_name
	 *
	 * @param string|array $template_names Template name (incl. file extension like .php)
	 * @param string  $template_path  Custom template path for plugins and addons (default: '')
	 * @param bool    $load           Call load_template() if true or return template path if false
	 *
	 * @uses trailingslashit()
	 * @uses get_stylesheet_directory()
	 * @uses get_template_directory()
	 * @uses wpsight_get_templates_dir()
	 *
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

			// Check child theme first
			if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $template_name ) ) {
				$located = trailingslashit( get_stylesheet_directory() ) . $template_name;
				break;

				// Check parent theme next
			} elseif ( file_exists( trailingslashit( get_template_directory() ) . $template_name ) ) {
				$located = trailingslashit( get_template_directory() ) . $template_name;
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
	 * Load specific template part.
	 *
	 * @param string  $slug          The slug name for the generic template
	 * @param string  $name          The name of the specialized template
	 * @param string  $template_path Custom template path for plugins and addons (default: '')
	 * @param bool    $load          Call load_template() if true or return template path if false
	 *
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
		return wpsight_locate_template( $templates, $args, $template_path, $load, $require_once );
	}

	/**
	 * get_orderby()
	 *
	 * Return orderby options for listing pages
	 *
	 * @param array   $args Array of arguments
	 * @return string|bool $orderby HTML output or false if empty
	 * @since 1.0.0
	 */

	public static function get_orderby( $args = array() ) {

		$defaults = array(

			'type'   => 'select', // links, bootstrap
			'orderby' => true,
			'order'   => true,
			'labels'  => array(
				'orderby'     => _x( 'Order by', 'listings panel actions', 'wpsight' ),
				'date'        => _x( 'Date', 'listings panel actions', 'wpsight' ),
				'orderby_sep' => _x( 'or', 'listings panel actions', 'wpsight' ),
				'price'       => _x( 'Price', 'listings panel actions', 'wpsight' ),
				'order'       => _x( 'Order', 'listings panel actions', 'wpsight' ),
				'desc'        => _x( 'DESC', 'listings panel actions', 'wpsight' ),
				'order_sep'   => _x( 'or', 'listings panel actions', 'wpsight' ),
				'asc'         => _x( 'ASC', 'listings panel actions', 'wpsight' ),
				'title'       => _x( 'Title', 'listings panel actions', 'wpsight' )
			)

		);

		$args = wp_parse_args( $args, $defaults );

		$args = apply_filters( 'wpsight_get_orderby_args', $args );

		// Setup $orderby markup
		$orderby = '';

		if ( $args['orderby'] || $args['order'] ) {

			ob_start();

			// Create orderby type links

			if ( $args['type'] == 'links' ) { ?>

				<div class="listings-sort">

					<?php if ( $args['orderby'] ) { ?>

					<div class="listings-sort-orderby">

						<span class="listings-sort-orderby-label"><?php echo $args['labels']['orderby']; ?></span>

						<?php
					// Check if order var set
					if ( get_query_var( 'order' ) )
						$vars_order = array( 'order' => get_query_var( 'order' ) );
?>

						<span class="listings-sort-orderby-date">
							<a href="<?php echo add_query_arg( array_merge( array( 'orderby' => 'date' ), (array) $vars_order ) ); ?>"><?php echo $args['labels']['date']; ?></a>
						</span>

						<span class="listings-sort-orderby-separator"><?php echo $args['labels']['orderby_sep']; ?></span>

						<span class="listings-sort-orderby-price">
							<a href="<?php echo add_query_arg( array_merge( array( 'orderby' => 'price' ), (array) $vars_order ) ); ?>"><?php echo $args['labels']['price']; ?></a>
						</span>

					</div><!-- .listings-sort-orderby -->

					<?php } ?>

					<?php if ( $args['order'] ) { ?>

					<div class="listings-sort-order">

						<span class="listings-sort-order-label"><?php echo $args['labels']['order']; ?></span>

						<?php
					// Check if orderby var set
					$vars_orderby = get_query_var( 'orderby' ) == 'price' ? array( 'orderby' => 'price' ) : false;
?>

						<span class="listings-sort-order-desc">
							<a href="<?php echo add_query_arg( array_merge( (array) $vars_orderby, array( 'order' => 'DESC' ) ) ); ?>"><?php echo $args['labels']['desc']; ?></a>
						</span>

						<span class="listings-sort-order-separator"><?php echo $args['labels']['order_sep']; ?></span>

						<span class="listings-sort-order-asc">
							<a href="<?php echo add_query_arg( array_merge( (array) $vars_orderby, array( 'order' => 'ASC' ) ) ); ?>"><?php echo $args['labels']['asc']; ?></a>
						</span>

					</div><!-- .listings-sort-order -->

					<?php } ?>

				</div><!-- .listings-sort --><?php

			} elseif ( $args['type'] == 'select' ) { ?>

				<?php
				$_GET['orderby'] = isset( $_GET['orderby'] ) ? $_GET['orderby'] : false;
				$_GET['order']   = isset( $_GET['order'] ) ? $_GET['order'] : false;
?>

				<div class="listings-sort">

					<select name="listings-sort">

						<option value=""><?php echo $args['labels']['orderby']; ?></option>

						<option<?php if ( $_GET['orderby'] == 'date' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'date', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['date']; ?> (<?php echo $args['labels']['asc']; ?>)</option>

						<option<?php if ( $_GET['orderby'] == 'date' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'date', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['date']; ?> (<?php echo $args['labels']['desc']; ?>)</option>

						<option<?php if ( $_GET['orderby'] == 'price' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'price', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['price']; ?> (<?php echo $args['labels']['asc']; ?>)</option>

						<option<?php if ( $_GET['orderby'] == 'price' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'price', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['price']; ?> (<?php echo $args['labels']['desc']; ?>)</option>

						<option<?php if ( $_GET['orderby'] == 'title' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'title', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['title']; ?> (<?php echo $args['labels']['asc']; ?>)</option>

						<option<?php if ( $_GET['orderby'] == 'title' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'title', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['title']; ?> (<?php echo $args['labels']['desc']; ?>)</option>

					</select>

				</div><!-- .listings-sort --><?php

			} elseif ( $args['type'] == 'bootstrap' ) { ?>

				<div class="listings-sort">

					<div class="btn-group">

					  	<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><?php echo $args['labels']['orderby']; ?> <span class="caret"></span></button>

					  	<ul class="dropdown-menu pull-right">
					    	<li><a href="<?php echo add_query_arg( array( 'orderby' => 'date', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['date']; ?> (<?php echo $args['labels']['asc']; ?>)</a></li>
					    	<li><a href="<?php echo add_query_arg( array( 'orderby' => 'date', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['date']; ?> (<?php echo $args['labels']['desc']; ?>)</a></li>
					    	<li class="divider"></li>
					    	<li><a href="<?php echo add_query_arg( array( 'orderby' => 'price', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['price']; ?> (<?php echo $args['labels']['asc']; ?>)</a></li>
					    	<li><a href="<?php echo add_query_arg( array( 'orderby' => 'price', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['price']; ?> (<?php echo $args['labels']['desc']; ?>)</a></li>
					    	<li class="divider"></li>
					    	<li><a href="<?php echo add_query_arg( array( 'orderby' => 'title', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['title']; ?> (<?php echo $args['labels']['asc']; ?>)</a></li>
					    	<li><a href="<?php echo add_query_arg( array( 'orderby' => 'title', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['title']; ?> (<?php echo $args['labels']['desc']; ?>)</a></li>
						</ul>

					</div>

				</div><!-- .listings-sort --><?php

			} // endif $args['type']

			$orderby = ob_get_clean();

		} // endif $args['orderby'] || $args['order']

		if ( ! empty( $orderby ) )
			return $orderby;

		return false;

	}

	/**
	 * get_panel()
	 *
	 * Return formatted listings control panel (with title and order options)
	 *
	 * @return string|bool HTML markup of listings panel or false if empty
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

			$title = '<span class="listings-panel-found">' . $wpsight_query->found_posts . '</span>' . ' ' . _n( 'Listing', 'Listings', $wpsight_query->found_posts, 'wpsight' );

		} elseif ( isset( $wp_query->found_posts ) ) {

			$title = '<span class="listings-panel-found">' . $wp_query->found_posts . '</span>' . ' ' . _n( 'Listing', 'Listings', $wp_query->found_posts, 'wpsight' );

		} else {

			$title = __( 'Listings', 'wpsight' );

		}

		return apply_filters( 'wpsight_get_archive_title', $title );

	}

	/**
	 * get_pagination()
	 *
	 * Return formatted listings pagination
	 *
	 * @param int     $max_num_pages max_num_pages parameter of corresponding query
	 * @param array   $args          paginate_links() arguments
	 * @return string|bool HTML markup of listings pagination or false if empty
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
			'prev_text'          => $arr_prev . __( 'Previous', 'wpsight' ),
			'next_text'          => __( 'Next', 'wpsight' ) . $arr_next,
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
	 * @param string  $class
	 * @param mixed   $post_id
	 * @return bool|array
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

		return apply_filters( 'get_listing_class', get_post_class( $classes, $post->ID ), $post_id, $class, $classes );

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
	 *
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
	 *  Return link to listing actions
	 *
	 *  @param   string  $post_id
	 *  @param   array  $actions
	 *
	 *  @return  string
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

		return $output;

	}

}
