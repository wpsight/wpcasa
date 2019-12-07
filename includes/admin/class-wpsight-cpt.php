<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 *	WPSight_Admin_CPT class
 */
class WPSight_Admin_CPT {

	/**
	 *	Constructor
	 */
	public function __construct() {

		// Manage custom columns on listings screen

		add_filter( 'manage_edit-listing_columns', array( $this, 'columns' ) );
		add_action( 'manage_listing_posts_custom_column', array( $this, 'custom_columns' ), 2 );
		add_filter( 'manage_edit-listing_sortable_columns', array( $this, 'sortable_columns' ) );
		add_filter( 'request', array( $this, 'sort_columns' ) );
		
		// Set custom update messages for listings
		
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_updated_messages' ) );
		
		// Manage bulk actions
		
		add_action( 'admin_footer-edit.php', array( $this, 'add_bulk_actions' ) );
		add_action( 'load-edit.php', array( $this, 'do_bulk_actions' ) );
		
		// Manage single actions (buttons on the right)
		
		add_action( 'admin_init', array( $this, 'approve_listing' ) );		
		add_action( 'admin_init', array( $this, 'toggle_sticky' ) );
		add_action( 'admin_init', array( $this, 'toggle_featured' ) );
		add_action( 'admin_init', array( $this, 'toggle_unavailable' ) );
		
		// Messages for single and bulk actions
		
		add_action( 'admin_notices', array( $this, 'approved_notice' ) );
		add_action( 'admin_notices', array( $this, 'marked_sticky_notice' ) );
		add_action( 'admin_notices', array( $this, 'marked_featured_notice' ) );
		add_action( 'admin_notices', array( $this, 'marked_unavailable_notice' ) );
			
		// Allow users to filter listings by offers (sale, rent etc.)
		
		add_action( 'restrict_manage_posts', array( $this, 'restrict_listing_offers' ) );
		add_filter( 'parse_query', array( $this, 'parse_query_listing_offers' ) );
		
		// Allow users to filter listings by taxomoy term
		add_action( 'restrict_manage_posts', array( $this, 'restrict_listing_taxonomy' ) );
		
		// Allow users to filter listings by author		
		add_action( 'restrict_manage_posts', array( $this, 'restrict_listing_author' ) );
		
		// Manage admin search by listing ID
		
		add_action( 'parse_request', array( $this, 'parse_request_listing_id' ) );
		add_action( 'parse_request', array( $this, 'parse_request_listing_id_media' ) );		
		add_action( 'parse_request_listing_id', array( $this, 'parse_request_listing_id_search_title' ) );
		
		// Add pending listings badge to menu
		add_filter( 'admin_head', array( $this, 'pending_listings_count' ) );
		
	}
	
	/**
	 *	columns()
	 *	
	 *	Define columns for manage_edit-listing_columns filter.
	 *	
	 *	@access	public
	 *	@param	mixed	$columns
	 *	
	 *	@since 1.0.0
	 */
	public function columns( $columns ) {
		
		// Make sure we deal with array

		if ( ! is_array( $columns ) )
			$columns = array();

		// Unset some default columns
		unset( $columns['date'], $columns['author'] );

		// Define our custom columns

		$columns["listing_offer"]	= __( 'Offer', 'wpcasa' );
		$columns["listing_id"]		= __( 'ID', 'wpcasa' );
		$columns["listing_title"]	= __( 'Listing', 'wpcasa' );
		$columns["listing_price"]	= __( 'Price', 'wpcasa' );
		$columns["listing_posted"]	= __( 'Posted', 'wpcasa' );

		if( class_exists( 'WPSight_Expire_Listings' ) )
			$columns["listing_expires"]	= __( 'Expires', 'wpcasa' );

		$columns['listing_status']	= __( 'Status', 'wpcasa' );;
		$columns['listing_actions']	= __( 'Actions', 'wpcasa' );

		return apply_filters( 'wpsight_admin_listing_columns', $columns );

	}

	/**
	 *	custom_columns()
	 *	
	 *	Define custom columns for
	 *	manage_listing_posts_custom_column action.
	 *	
	 *	@access	public
	 *	@param	mixed	$column
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_listing_offer()
	 *	@uses	wpsight_get_offer_color()
	 *	@uses	wpsight_get_offer()
	 *	@uses	wpsight_listing_id()
	 *	@uses	wpsight_listing_price()
	 *	@uses	wpsight_get_listing_thumbnail()
	 *	@uses	wpsight_is_listing_sticky()
	 *	@uses	wpsight_is_listing_featured()
	 *	@uses	wpsight_get_listing_terms()
	 *	@uses	wpsight_is_listing_not_available()
	 *	@uses	current_user_can()
	 *	@uses	wpsight_get_listing_summary()
	 *	@uses	wpsight_is_listing_pending()
	 *	@uses	wpsight_is_listing_expired()
	 *	@uses	wpsight_is_listing_sticky()
	 *	@uses	wpsight_sort_array_by_priority()
	 *	
	 *	@since 1.0.0
	 */
	public function custom_columns( $column ) {
		global $post;
		
		$datef = wpsight_get_option( 'date_format', get_option( 'date_format' ) );

		switch ( $column ) {

			case "listing_offer" :

				// Get listing offer (sale, rent etc.)
				$listing_offer = wpsight_get_listing_offer( $post->ID, false );
				
				// Display colored offer badge
				
				if ( $listing_offer )
					echo '<span style="background-color:' . esc_attr( wpsight_get_offer_color( $listing_offer ) ) . '" class="' . sanitize_html_class( $listing_offer ) . '">' . esc_attr( wpsight_get_offer( $listing_offer ) ) . '</span>';

			break;
			
			case "listing_id" :
			
				// Disply listing ID
				wpsight_listing_id( $post->ID );

			break;
			
			case "listing_price" :
			
				// Display listing price
				wpsight_listing_price( $post->ID );

			break;

			case "listing_title" :
			
				// Display listing thumbnail (with edit link if not in trash)
				
				if ( $post->post_status !== 'trash' && current_user_can( 'edit_listing', $post->ID ) ) {
					echo '<a href="' . get_edit_post_link( $post->ID ) . '">' . wpsight_get_listing_thumbnail( $post->ID ) . '</a>';
				} else {							
					echo wpsight_get_listing_thumbnail( $post->ID );							
				}
			
				echo '<div class="listing-info">';

					echo '<div class="listing-title">';
					
						// Display listing title (with edit link if not in trash)
						
						if ( $post->post_status !== 'trash' && current_user_can( 'edit_listing', $post->ID ) ) {
							echo '<a href="' . admin_url( 'post.php?post=' . $post->ID . '&action=edit') . '" class="tips" data-tip="' . __( 'Edit', 'wpcasa' ) . '">' . $post->post_title . '</a>';
						} else {							
							echo $post->post_title;							
						}
						
						if ( $post->post_status !== 'trash' && class_exists( 'WPSight_Featured_Listings' ) ) {
						
							// Display sticky
						
							if( wpsight_is_listing_sticky() )
								echo ' <span class="listing-sticky">&dash; ' . __( 'Sticky', 'wpcasa' ) . '</span>';
							
							// Display featured
							
							if( wpsight_is_listing_featured() )
								echo ' <span class="listing-featured">&dash; ' . __( 'Featured', 'wpcasa' ) . '</span>';
						
						}
						
					echo '</div>';
					
					echo '<div class="listing-taxonomies">';
					
						$type 	  = wpsight_get_listing_terms( 'listing-type' );
						$location = wpsight_get_listing_terms( 'location' );
					
						// Display listing type terms
						
						if( $type ) {
							echo '<div class="listing-type">';
								echo wpsight_get_listing_terms( 'listing-type', $post->ID, ', ', '', '', false );
							echo '</div>';
						}
						
						if( $type && $location )
							echo '&dash; ';
						
						// Display listing location terms
					
						if( $location ) {
							echo '<div class="location">';
								echo wpsight_get_listing_terms( 'location', $post->ID, ' &rsaquo; ', '', '', false );
							echo '</div>';
						}
					
					echo '</div>';
					
					// Display text if item not available
					
					if( wpsight_is_listing_not_available() )
						echo '<span class="listing-not-available">' . __( 'Item is currently not available', 'wpcasa' ) . '</span>';
					
					// Display listing title actions (edit, view)
					
					echo '<div class="actions">';
				
						$admin_actions_listing_title = array();
						
						if( current_user_can( 'edit_listing', $post->ID ) ) {
						
							if ( $post->post_status !== 'trash' ) {
							
								$admin_actions_listing_title['edit']   = array(
									'action'  => 'edit',
									'name'    => __( 'Edit', 'wpcasa' ),
									'url'     => get_edit_post_link( $post->ID )
								);
							
								$admin_actions_listing_title['view']   = array(
									'action'  => 'view',
									'name'    => __( 'View', 'wpcasa' ),
									'url'     => get_permalink( $post->ID ),
									'target'  => '_blank'
								);
							
							}
						
						}
						
						$admin_actions_listing_title = apply_filters( 'wpsight_admin_actions_listing_title', $admin_actions_listing_title, $post );
						
						foreach ( $admin_actions_listing_title as $action ) {
							printf( '<a class="button tips" href="%2$s" data-tip="%3$s" target="%5$s"><i class="icon icon-%1$s"></i> %4$s</a>', $action['action'], esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_html( $action['name'] ), isset( $action['target'] ) ? esc_html( $action['target'] ) : false );
						}
					
					echo '</div>';
					
					// Display more info when excerpt list mode
					
					if( isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'excerpt' ) {						
					
						echo '<div class="listing-summary">';						
							echo '<p>' . wpsight_get_listing_summary() . '</p>';						
						echo '</div>';
						
						echo '<div class="listing-excerpt">';						
							echo '<p>' . wp_trim_excerpt() . '</p>';						
						echo '</div>';
						
					}
				
				echo '</div>';

			break;
			
			case "listing_status" :
				
				// Display listing status (e.g. pending, active) with colored dot
				echo '<span class="listing-status status-' . sanitize_html_class( $post->post_status ) . '"><span></span> ' . wpsight_get_status( $post->post_status ) . '</span>';

			break;

			case "listing_posted" :
			
				// Display listing publish date
				echo '<span class="listing-posted">' . date_i18n( $datef, strtotime( $post->post_date ) ) . '</span>';
				
				$user_name = current_user_can( 'edit_users' ) ? '<a href="' . get_edit_user_link( $post->post_author ) . '">' . get_the_author() . '</a>' : get_the_author();
				
				// Display listing agent
				echo '<span class="listing-agent">' . ( empty( $post->post_author ) ? __( 'by a guest', 'wpcasa' ) : sprintf( __( 'by %s', 'wpcasa' ), $user_name ) ) . '</span>';

			break;

			case "listing_actions" :
			
				// Define some general classes to be used with action buttons
			
				$classes = array();
				
				if( ! wpsight_is_listing_pending( $post->ID ) )
					$classes[] = 'listing-approved';
				
				if( wpsight_is_listing_expired( $post->ID ) )
					$classes[] = 'listing-expired';
			
				if( wpsight_is_listing_not_available( $post->ID ) )
					$classes[] = 'listing-not-available';
				
				if( wpsight_is_listing_sticky( $post->ID ) )
					$classes[] = 'listing-sticky';
				
				if( wpsight_is_listing_featured( $post->ID ) )
					$classes[] = 'listing-featured';
				
				// Display action buttons
			
				echo '<div class="actions ' . join( ' ', $classes ) . '">';
				
					$admin_actions = array();
					
					if ( $post->post_status !== 'trash' ) {
						
						$admin_actions['approve']   = array(
							'action'   => 'approve',
							'name'     => wpsight_is_listing_pending( $post->ID ) ? __( 'Approve', 'wpcasa' ) : __( 'Unapprove', 'wpcasa' ),
							'url'      =>  wp_nonce_url( add_query_arg( 'approve_listing', $post->ID ), 'approve_listing' ),
							'cap'	   => 'publish_listings',
							'priority' => 10
						);
						
						$admin_actions['unavailable']   = array(
							'action'   => 'unavailable',
							'name'     => wpsight_is_listing_not_available( $post->ID ) ? __( 'Mark available', 'wpcasa' ) : __( 'Mark unavailable', 'wpcasa' ),
							'url'      =>  wp_nonce_url( add_query_arg( 'toggle_unavailable', $post->ID ), 'toggle_unavailable' ),
							'cap'	   => 'publish_listings',
							'priority' => 20
						);
					
						$admin_actions['delete'] = array(
							'action'   => 'delete',
							'name'     => __( 'Trash', 'wpcasa' ),
							'url'      => get_delete_post_link( $post->ID ),
							'cap'	   => 'delete_listing',
							'priority' => 30
						);
					
					} else {
					
						$admin_actions['untrash'] = array(
							'action'   => 'untrash',
							'name'     => __( 'Restore', 'wpcasa' ),
							'url'      => wp_nonce_url( admin_url( 'post.php?post=' . $post->ID . '&action=untrash' ), 'untrash-post_' . $post->ID ),
							'cap'	   => 'delete_listing',
							'priority' => 10
						);
					
					}
					
					$admin_actions = apply_filters( 'wpsight_admin_actions', $admin_actions, $post );
					
					// Sort array by priority
					$admin_actions = wpsight_sort_array_by_priority( $admin_actions );
						
					$i = 0;
					
					foreach ( $admin_actions as $action ) {
						
						$action['cap'] = isset( $action['cap'] ) ? $action['cap'] : 'read_listing';
						
						if( current_user_can( $action['cap'], $post->ID ) ) {
						
							printf( '<a class="button tips" href="%2$s" data-tip="%3$s" target="%5$s"><i class="icon icon-%1$s"></i> %4$s</a>', $action['action'], esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_html( $action['name'] ), isset( $action['target'] ) ? esc_html( $action['target'] ) : false );
							
							$i++;
						
						}

					}
					
					// If no other action is displayed, show view button
					
					if( 0 == $i && $post->post_status == 'publish' )
						printf( '<a class="button tips" href="%2$s" data-tip="%3$s" target="%5$s"><i class="icon icon-view"></i> %4$s</a>', 'view', esc_url( get_permalink( $post->ID ) ), esc_attr( __( 'View', 'wpcasa' ) ), esc_html( __( 'View', 'wpcasa' ) ), '_blank' );

				echo '</div>';

			break;

		}
		
	}

	/**
	 *	sortable_columns()
	 *	
	 *	Define some sortable columns for
	 *	manage_edit-listing_sortable_columns filter.
	 *	
	 *	@access	public
	 *	@param	mixed	$columns
	 *	@uses	wp_parse_args()
	 *	
	 *	@since 1.0.0
	 */
	public function sortable_columns( $columns ) {

		$custom = array(
			'listing_id'   		=> 'listing_id',
			'listing_title'   	=> 'title',
			'listing_price' 	=> 'listing_price',
			'listing_posted'  	=> 'date'
		);

		return wp_parse_args( $custom, $columns );

	}

	/**
	 *	sort_columns()
	 *	
	 *	Make sortable colums sort.
	 *	
	 *	@access	public
	 *	@param	mixed	$vars
	 *	
	 *	@since 1.0.0
	 */
	public function sort_columns( $vars ) {

		if ( isset( $vars['orderby'] ) ) {
			
			if ( 'listing_id' === $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' 	=> '_listing_id',
					'orderby' 	=> 'meta_value'
				) );
			}
			
			if ( 'listing_price' === $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' 	=> '_price',
					'orderby' 	=> 'meta_value_num'
				) );
			}

		}

		return $vars;

	}

	/**
	 *	add_bulk_actions()
	 *	
	 *	Add our custom bulk actions
	 *	to WordPress dropdown.
	 *	
	 *	@access	public
	 *	@uses	wpsight_post_type()
	 *	
	 *	@since 1.0.0
	 */
	public function add_bulk_actions() {
		global $post_type, $wp_post_types;

		if ( $post_type == wpsight_post_type() ) {
			?>
			<script type="text/javascript">
		      jQuery(document).ready(function() {

		        jQuery('<option>').val('approve_listings').text('<?php _e( 'Approve', 'wpcasa' ); ?>').appendTo("select[name='action']");
		        jQuery('<option>').val('approve_listings').text('<?php _e( 'Approve', 'wpcasa' ); ?>').appendTo("select[name='action2']");
		        
		        jQuery('<option>').val('unapprove_listings').text('<?php _e( 'Unapprove', 'wpcasa' ); ?>').appendTo("select[name='action']");
		        jQuery('<option>').val('unapprove_listings').text('<?php _e( 'Unapprove', 'wpcasa' ); ?>').appendTo("select[name='action2']");

		      });
		    </script>
		    <?php
		}
	}

	/**
	 *	do_bulk_actions()
	 *	
	 *	Execute our custom bulk actions
	 *	if selected in WordPress dropdown.
	 *	
	 *	@access	public
	 *	@uses	_get_list_table()
	 *	@uses	$wp_list_table->current_action()
	 *	@uses	check_admin_referer()
	 *	@uses	wp_update_post()
	 *	@uses	admin_url()
	 *	@uses	remove_query_arg()
	 *	@uses	add_query_arg()
	 *	@uses	wp_redirect()
	 *	@return	null
	 *	
	 *	@since 1.0.0
	 */
	public function do_bulk_actions() {

		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$list_action   = $wp_list_table->current_action();
		
		// Set array of vars to remove
				
		$remove_vars = array(
			'approved_listings',
			'expired_listings'
		);
		
		// Check current action

		switch( $list_action ) {
			
			// Bulk approve listings

			case 'approve_listings' :
				check_admin_referer( 'bulk-posts' );

				// Get post IDs from $_GET
				$post_ids = array_map( 'absint', array_filter( (array) $_GET['post'] ) );
				
				$approved = array();
				
				// Check if there are post IDs

				if ( ! empty( $post_ids ) ) {
					
					// Loop through post IDs, if any

					foreach( $post_ids as $post_id ) {
						
						// Set ID and post status
						
						$listing_data = array(
							'ID'          => $post_id,
							'post_status' => 'publish'
						);
						
						// If status was pending, publish post and collect post ID
						
						if ( wpsight_is_listing_pending( $post_id ) && wp_update_post( $listing_data ) ) {
							
							// Collect Post ID
							$approved[] = $post_id;
							
							// Set listing expiry

							if( function_exists( 'wpsight_set_listing_expiry' ) )
								wpsight_set_listing_expiry( $post_id );

						}

					} // endforeach
					
				} // endif ! empty( $post_ids )
				
				// Add approved listings to URL and redirect to list
				wp_redirect( add_query_arg( 'approved_listings', $approved, remove_query_arg( $remove_vars, admin_url( 'edit.php?post_type=listing' ) ) ) );
				
				exit;
			break;
			
			// Bulk unapprove listings

			case 'unapprove_listings' :
				check_admin_referer( 'bulk-posts' );

				// Get post IDs from $_GET
				$post_ids = array_map( 'absint', array_filter( (array) $_GET['post'] ) );
				
				$unapproved = array();
				
				// Check if there are post IDs

				if ( ! empty( $post_ids ) ) {
					
					// Loop through post IDs, if any

					foreach( $post_ids as $post_id ) {
						
						// Set ID and post status
						
						$listing_data = array(
							'ID'          => $post_id,
							'post_status' => 'pending'
						);
						
						// If status was publish, set to pending and collect post ID
						
						if ( wp_update_post( $listing_data ) )
							$unapproved[] = $post_id;

					} // endforeach
					
				} // endif ! empty( $post_ids )
				
				// Add unapproved listings to URL and redirect to list
				wp_redirect( add_query_arg( 'approved_listings', $unapproved, remove_query_arg( $remove_vars, admin_url( 'edit.php?post_type=listing' ) ) ) );
				
				exit;
			break;

		} // end switch( $list_action )

		return;

	}
	
	/**
	 *	approve_listing()
	 *	
	 *	Approve a single listing with action button.
	 *	
	 *	@access	public
	 *	@uses	wp_verify_nonce()
	 *	@uses	current_user_can()
	 *	@uses	wpsight_is_listing_pending()
	 *	@uses	wp_update_post()
	 *	@uses	wpsight_set_listing_expiry()
	 *	@uses	admin_url()
	 *	@uses	add_query_arg()
	 *	@uses	remove_query_arg()
	 *	@uses	wp_redirect()
	 *	
	 *	@since 1.0.0
	 */
	public function approve_listing() {
		
		// Get listing to approve from $_GET, check nonce and if current user can

		if ( ! empty( $_GET['approve_listing'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'approve_listing' ) && current_user_can( 'edit_post', $_GET['approve_listing'] ) ) {
			
			// Get post ID
			$post_id = absint( $_GET['approve_listing'] );
			
			// Get post type
			$post_type = wpsight_post_type();
			
			// Check if listing is currently approved or not
			$status = wpsight_is_listing_pending( $post_id ) ? 'publish' : 'pending';
			
			// Set ID and post status
			
			$listing_data = array(
				'ID'          => $post_id,
				'post_status' => $status
			);
			
			// Update post
			wp_update_post( $listing_data );
			
			// Set listing expiry
			
			if( function_exists( 'wpsight_set_listing_expiry' ) && 'publish' == $status )
				wpsight_set_listing_expiry( $post_id );
			
			// Add approved listing to URL and redirect to list
			wp_redirect( remove_query_arg( 'approve_listing', add_query_arg( 'approved_listings', $post_id, admin_url( 'edit.php?post_type=' . $post_type ) ) ) );

			exit;

		} // endif

	}

	/**
	 *	approved_notice()
	 *	
	 *	Show a notice if we did a bulk action or single approval.
	 *	
	 *	@uses	wpsight_post_type()
	 *	@uses	wpsight_is_listing_pending()
	 *	@uses	get_the_title()
	 *	
	 *	@since 1.0.0
	 */
	public function approved_notice() {
		global $post_type, $pagenow;
		
		// Check if we have listings to approve and are on the right page

		if ( $pagenow == 'edit.php' && $post_type == wpsight_post_type() && ! empty( $_REQUEST['approved_listings'] ) ) {
			
			// Get listings to approve from $_REQUEST
			$approved_listings = $_REQUEST['approved_listings'];
			
			// Check if we have multiple (array) or a single listing
			
			if ( is_array( $approved_listings ) ) {
				
				// Make sure we have positive integers
				$approved_listings = array_map( 'absint', $approved_listings );
				
				$titles_approved   = array();
				$titles_unapproved = array();
				
				// Loop through listings and collect titles
				
				foreach ( $approved_listings as $listing_id ) {
					
					if( ! wpsight_is_listing_pending( $listing_id ) ) {
						
						// Titles of approved listings
						$titles_approved[] = get_the_title( $listing_id );

					} else {
					
						// Titles of unapproved listings
						$titles_unapproved[] = get_the_title( $listing_id );

					} // ! wpsight_is_listing_pending()

				} // endforeach
					
				// Display update message with titles of approved listings
				
				if( $titles_approved )
					echo '<div class="updated"><p>' . sprintf( __( '%s have been approved', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_approved ) . '&quot;' ) . '</p></div>';
				
				// Display update message with titles of unapproved listings
				
				if( $titles_unapproved )
					echo '<div class="updated"><p>' . sprintf( __( '%s are now pending approval', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_unapproved ) . '&quot;' ) . '</p></div>';

			} else {
				
				// Display update message with title of single listing

				if( ! wpsight_is_listing_pending( $approved_listings ) ) {
					echo '<div class="updated"><p>' . sprintf( __( '%s has been approved', 'wpcasa' ), '&quot;' . get_the_title( $approved_listings ) . '&quot;' ) . '</p></div>';
				} else {
					echo '<div class="updated"><p>' . sprintf( __( '%s is now pending approval', 'wpcasa' ), '&quot;' . get_the_title( $approved_listings ) . '&quot;' ) . '</p></div>';
				}

			} // endif is_array( $approved_listings )

		} // endif

	}
	
	/**
	 *	toggle_sticky()
	 *	
	 *	Mark a single listing sticky with action button.
	 *	
	 *	@access	public
	 *	@uses	wp_verify_nonce()
	 *	@uses	current_user_can()
	 *	@uses	wpsight_is_listing_sticky()
	 *	@uses	update_post_meta()
	 *	@uses	admin_url()
	 *	@uses	add_query_arg()
	 *	@uses	remove_query_arg()
	 *	@uses	wp_redirect()
	 *	
	 *	@since 1.0.0
	 */
	public function toggle_sticky() {
		
		// Get listing to mark sticky from $_GET, check nonce and if current user can
		
		if ( ! empty( $_GET['toggle_sticky'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'toggle_sticky' ) && current_user_can( 'edit_post', $_GET['toggle_sticky'] ) ) {
			
			// Get post ID
			$post_id = absint( $_GET['toggle_sticky'] );
			
			// Get post type
			$post_type = wpsight_post_type();
			
			// Check if listing is currently sticky or not
			$mark = wpsight_is_listing_sticky( $post_id ) ? '0' : '1';

			// Update post meta
			update_post_meta( $post_id, '_listing_sticky', $mark );
			
			// Add marked listing to URL and redirect to list
			wp_redirect( remove_query_arg( 'toggle_sticky', add_query_arg( 'listings_marked_sticky', $post_id, admin_url( 'edit.php?post_type=' . $post_type ) ) ) );

			exit;

		} // endif

	}
	
	/**
	 *	marked_sticky_notice()
	 *	
	 *	Show a notice if we marked a single or multiple listings sticky.
	 *	
	 *	@uses	wpsight_post_type()
	 *	@uses	get_the_title()
	 *	@uses	wpsight_is_listing_sticky()
	 *	
	 *	@since 1.0.0
	 */
	public function marked_sticky_notice() {
		global $post_type, $pagenow;
		
		// Check if we have listings to mark sticky and are on the right page

		if ( $pagenow == 'edit.php' && $post_type == wpsight_post_type() && ! empty( $_REQUEST['listings_marked_sticky'] ) ) {
			
			// Get listings to mark from $_REQUEST
			$marked_listings = $_REQUEST['listings_marked_sticky'];

			// Check if we have multiple (array) or a single listing
			
			if ( is_array( $marked_listings ) ) {
				
				// Make sure we have positive integers
				$marked_listings = array_map( 'absint', $marked_listings );
				
				$titles_sticky 	 = array();
				$titles_unsticky = array();
				
				// Loop through listings and collect titles
				
				foreach ( $marked_listings as $listing_id ) {
					
					if( wpsight_is_listing_sticky( $listing_id ) ) {
						
						// Titles of listings marked sticky
						$titles_sticky[] = get_the_title( $listing_id );

					} else {
					
						// Titles of listings marked sticky					
						$titles_unsticky[] = get_the_title( $listing_id );

					} // wpsight_is_listing_sticky()

				} // endforeach
					
				// Display update message with titles of listings marked sticky
				
				if( $titles_sticky )
					echo '<div class="updated"><p>' . sprintf( __( '%s are now sticky', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_sticky ) . '&quot;' ) . '</p></div>';
				
				// Display update message with titles of unmarked listings
				
				if( $titles_unsticky )
					echo '<div class="updated"><p>' . sprintf( __( '%s are no longer sticky', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_unsticky ) . '&quot;' ) . '</p></div>';

			} else {
				
				// Display update message with title of single listing

				if( wpsight_is_listing_sticky( $marked_listings ) ) {
					echo '<div class="updated"><p>' . sprintf( __( '%s is now sticky', 'wpcasa' ), '&quot;' . get_the_title( $marked_listings ) . '&quot;' ) . '</p></div>';
				} else {
					echo '<div class="updated"><p>' . sprintf( __( '%s is no longer sticky', 'wpcasa' ), '&quot;' . get_the_title( $marked_listings ) . '&quot;' ) . '</p></div>';
				}

			} // endif is_array( $marked_listings )

		} // endif

	}
	
	/**
	 *	toggle_featured()
	 *	
	 *	Mark a single listing featured with action button.
	 *	
	 *	@access	public
	 *	@uses	wp_verify_nonce()
	 *	@uses	current_user_can()
	 *	@uses	wpsight_is_listing_featured()
	 *	@uses	update_post_meta()
	 *	@uses	admin_url()
	 *	@uses	add_query_arg()
	 *	@uses	remove_query_arg()
	 *	@uses	wp_redirect()
	 *	
	 *	@since 1.0.0
	 */
	public function toggle_featured() {
		
		// Get listing to mark featured from $_GET, check nonce and if current user can
		
		if ( ! empty( $_GET['toggle_featured'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'toggle_featured' ) && current_user_can( 'edit_post', $_GET['toggle_featured'] ) ) {
			
			// Get post ID
			$post_id = absint( $_GET['toggle_featured'] );
			
			// Get post type
			$post_type = wpsight_post_type();
			
			// Check if listing is currently featured or not
			$mark = wpsight_is_listing_featured( $post_id ) ? '0' : '1';
			
			// Update post meta
			update_post_meta( $post_id, '_listing_featured', $mark );
			
			// Add marked listing to URL and redirect to list
			wp_redirect( remove_query_arg( 'toggle_featured', add_query_arg( 'listings_marked_featured', $post_id, admin_url( 'edit.php?post_type=' . $post_type ) ) ) );

			exit;

		} // endif

	}
	
	/**
	 *	marked_featured_notice()
	 *	
	 *	Show a notice if we marked a single or multiple listings featured.
	 *	
	 *	@uses	wpsight_post_type()
	 *	@uses	get_the_title()
	 *	@uses	wpsight_is_listing_featured()
	 *	
	 *	@since 1.0.0
	 */
	public function marked_featured_notice() {
		global $post_type, $pagenow;
		
		// Check if we have listings to mark featured and are on the right page

		if ( $pagenow == 'edit.php' && $post_type == wpsight_post_type() && ! empty( $_REQUEST['listings_marked_featured'] ) ) {
			
			// Get listings to mark from $_REQUEST
			$marked_listings = $_REQUEST['listings_marked_featured'];

			// Check if we have multiple (array) or a single listing
			
			if ( is_array( $marked_listings ) ) {
				
				// Make sure we have positive integers
				$marked_listings = array_map( 'absint', $marked_listings );
				
				$titles_featured   = array();
				$titles_unfeatured = array();
				
				// Loop through listings and collect titles
				
				foreach ( $marked_listings as $listing_id ) {
					
					if( wpsight_is_listing_featured( $listing_id ) ) {
						
						// Titles of listings marked sticky
						$titles_featured[] = get_the_title( $listing_id );

					} else {
					
						// Titles of listings marked sticky					
						$titles_unfeatured[] = get_the_title( $listing_id );

					} // wpsight_is_listing_featured()

				} // endforeach
					
				// Display update message with titles of listings marked featured
				
				if( $titles_featured )
					echo '<div class="updated"><p>' . sprintf( __( '%s are now featured', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_featured ) . '&quot;' ) . '</p></div>';
				
				// Display update message with titles of unmarked listings
				
				if( $titles_unfeatured )
					echo '<div class="updated"><p>' . sprintf( __( '%s are no longer featured', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_unfeatured ) . '&quot;' ) . '</p></div>';

			} else {
				
				// Display update message with title of single listing

				if( wpsight_is_listing_featured( $marked_listings ) ) {
					echo '<div class="updated"><p>' . sprintf( __( '%s is now featured', 'wpcasa' ), '&quot;' . get_the_title( $marked_listings ) . '&quot;' ) . '</p></div>';
				} else {
					echo '<div class="updated"><p>' . sprintf( __( '%s is no longer featured', 'wpcasa' ), '&quot;' . get_the_title( $marked_listings ) . '&quot;' ) . '</p></div>';
				}

			} // endif is_array( $marked_listings )

		} // endif

	}
	
	/**
	 *	toggle_unavailable()
	 *	
	 *	Mark a single listing unavailable with action button.
	 *	
	 *	@access	public
	 *	@uses	wp_verify_nonce()
	 *	@uses	current_user_can()
	 *	@uses	wpsight_is_listing_not_available()
	 *	@uses	update_post_meta()
	 *	@uses	admin_url()
	 *	@uses	add_query_arg()
	 *	@uses	remove_query_arg()
	 *	@uses	wp_redirect()
	 *	
	 *	@since 1.0.0
	 */
	public function toggle_unavailable() {
		
		// Get listing to mark unavailable from $_GET, check nonce and if current user can
		
		if ( ! empty( $_GET['toggle_unavailable'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'toggle_unavailable' ) && current_user_can( 'edit_post', $_GET['toggle_unavailable'] ) ) {
			
			// Get post ID
			$post_id = absint( $_GET['toggle_unavailable'] );
			
			// Get post type
			$post_type = wpsight_post_type();
			
			// Check if listing is currently unavailable or not
			$mark = wpsight_is_listing_not_available( $post_id ) ? '0' : '1';
			
			// Update post meta
			update_post_meta( $post_id, '_listing_not_available', $mark );
			
			// Add marked listing to URL and redirect to list
			wp_redirect( remove_query_arg( 'toggle_unavailable', add_query_arg( 'listings_marked_unavailable', $post_id, admin_url( 'edit.php?post_type=' . $post_type ) ) ) );

			exit;

		} // endif

	}
	
	/**
	 *	marked_unavailable_notice()
	 *	
	 *	Show a notice if we marked a single or multiple listings not available.
	 *	
	 *	@uses	wpsight_post_type()
	 *	@uses	get_the_title()
	 *	@uses	wpsight_is_listing_not_available()
	 *	
	 *	@since 1.0.0
	 */
	public function marked_unavailable_notice() {
		global $post_type, $pagenow;
		
		// Check if we have listings to mark featured and are on the right page

		if ( $pagenow == 'edit.php' && $post_type == wpsight_post_type() && ! empty( $_REQUEST['listings_marked_unavailable'] ) ) {
			
			// Get listings to mark from $_REQUEST
			$marked_listings = $_REQUEST['listings_marked_unavailable'];

			// Check if we have multiple (array) or a single listing
			
			if ( is_array( $marked_listings ) ) {
				
				// Make sure we have positive integers
				$marked_listings = array_map( 'absint', $marked_listings );
				
				$titles_not_available   = array();
				$titles_available = array();
				
				// Loop through listings and collect titles
				
				foreach ( $marked_listings as $listing_id ) {
					
					if( wpsight_is_listing_not_available( $listing_id ) ) {
						
						// Titles of listings marked not available
						$titles_not_available[] = get_the_title( $listing_id );

					} else {
					
						// Titles of listings marked available					
						$titles_available[] = get_the_title( $listing_id );

					} // wpsight_is_listing_not_available()

				} // endforeach
					
				// Display update message with titles of listings marked not available
				
				if( $titles_not_available )
					echo '<div class="updated"><p>' . sprintf( __( '%s are no longer available', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_not_available ) . '&quot;' ) . '</p></div>';
				
				// Display update message with titles of listings marked available
				
				if( $titles_available )
					echo '<div class="updated"><p>' . sprintf( __( '%s are now available', 'wpcasa' ), '&quot;' . implode( '&quot;, &quot;', $titles_available ) . '&quot;' ) . '</p></div>';

			} else {
				
				// Display update message with title of single listing

				if( wpsight_is_listing_not_available( $marked_listings ) ) {
					echo '<div class="updated"><p>' . sprintf( __( '%s is no longer available', 'wpcasa' ), '&quot;' . get_the_title( $marked_listings ) . '&quot;' ) . '</p></div>';
				} else {
					echo '<div class="updated"><p>' . sprintf( __( '%s is now available', 'wpcasa' ), '&quot;' . get_the_title( $marked_listings ) . '&quot;' ) . '</p></div>';
				}

			} // endif is_array( $marked_listings )

		} // endif

	}

	/**
	 *	post_updated_messages()
	 *	
	 *	@access	public
	 *	@param	mixed	$messages
	 *
	 *	@since 1.0.0
	 */
	public function post_updated_messages( $messages ) {
		global $post, $post_ID, $wp_post_types;
		
		$post_type = wpsight_post_type();

		$messages[ $post_type ] = array(
			0 => '',
			1 => sprintf( __( '%s updated. <a href="%s">View</a>', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name, esc_url( get_permalink( $post_ID ) ) ),
			2 => __( 'Custom field updated.', 'wpcasa' ),
			3 => __( 'Custom field deleted.', 'wpcasa' ),
			4 => sprintf( __( '%s updated.', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name ),
			5 => isset( $_GET['revision'] ) ? sprintf( __( '%s restored to revision from %s', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( '%s published. <a href="%s">View</a>', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name, esc_url( get_permalink( $post_ID ) ) ),
			7 => sprintf( __( '%s saved.', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name ),
			8 => sprintf( __( '%s submitted. <a target="_blank" href="%s">Preview</a>', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			9 => sprintf( __( '%s scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview</a>', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name,
			  date_i18n( __( 'M j, Y @ G:i', 'wpcasa' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
			10 => sprintf( __( '%s draft updated. <a target="_blank" href="%s">Preview</a>', 'wpcasa' ), $wp_post_types[ $post_type ]->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		);

		return $messages;
	}
	
	/**
	 *	bulk_updated_messages()
	 *	
	 *	@access	public
	 *	@param	mixed	$messages
	 *
	 *	@since 1.0.0
	 */
	public function bulk_updated_messages( $messages ) {
		global $post, $post_ID, $wp_post_types;
		
		$bulk_counts = array(
			'updated'   => isset( $_REQUEST['updated'] )   ? absint( $_REQUEST['updated'] )   : 0,
			'locked'    => isset( $_REQUEST['locked'] )    ? absint( $_REQUEST['locked'] )    : 0,
			'deleted'   => isset( $_REQUEST['deleted'] )   ? absint( $_REQUEST['deleted'] )   : 0,
			'trashed'   => isset( $_REQUEST['trashed'] )   ? absint( $_REQUEST['trashed'] )   : 0,
			'untrashed' => isset( $_REQUEST['untrashed'] ) ? absint( $_REQUEST['untrashed'] ) : 0
		);
		
		$post_type = wpsight_post_type();
		
		$messages[ $post_type ] = array(
			'updated'   => _n( '%s listing updated.', '%s listings updated.', $bulk_counts['updated'], 'wpcasa' ),
			'locked'    => _n( '%s listing not updated, somebody is editing it.', '%s listings not updated, somebody is editing them.', $bulk_counts['locked'], 'wpcasa' ),
			'deleted'   => _n( '%s listing permanently deleted.', '%s listings permanently deleted.', $bulk_counts['deleted'], 'wpcasa' ),
			'trashed'   => _n( '%s listing moved to the Trash.', '%s listings moved to the Trash.', $bulk_counts['trashed'], 'wpcasa' ),
			'untrashed' => _n( '%s listing restored from the Trash.', '%s listings restored from the Trash.', $bulk_counts['untrashed'], 'wpcasa' ),
		);

		return $messages;
	}
	
	/**
	 *	pending_listings_count()
	 *	
	 *	Add pending listings to badge in menu label.
	 *	
	 *	@access	public
	 *	@uses	wpsight_post_type()
	 *	
	 *	@since 1.0.0
	 */
	public function pending_listings_count() {
		global $menu;
		
		if( ! current_user_can( 'publish_listings' ) )
			return;

		$plural = __( 'Listings', 'wpcasa' );
		$count  = wp_count_posts( wpsight_post_type(), 'readable' );

		if ( ! empty( $menu ) && is_array( $menu ) ) {
			foreach ( $menu as $key => $menu_item ) {
				if ( strpos( $menu_item[0], $plural ) === 0 ) {
					if ( $order_count = $count->pending + $count->pending_payment ) {
						$menu[ $key ][0] .= " <span class='awaiting-mod update-plugins count-$order_count'><span class='pending-count'>" . number_format_i18n( $order_count ) . "</span></span>" ;
					}
					break;
				}
			}
		}
	}
	
	/**
	 *	parse_query_listing_offers()
	 *	
	 *	Parse the query and limit listings
	 *	to desired listing offer.
	 *	
	 *	@access	public
	 *	@param	object	$query	WP_Query object
	 *	@uses	wpsight_post_type()
	 *	
	 *	@since 1.0.0
	 */
	public function parse_query_listing_offers( $query ) {	
	    global $pagenow, $typenow;
	    
	    if( $typenow != wpsight_post_type() )
			return;
	
	    if ( $pagenow == 'edit.php' ) {
	    
	    	if( isset( $_GET['wpsight-offer'] ) && $_GET['wpsight-offer'] != '' && $_GET['wpsight-offer'] != 'not-available' ) {
	    		$query->query_vars['meta_key'] = '_price_offer';
	    		$query->query_vars['meta_value'] = sanitize_text_field( $_GET['wpsight-offer'] );
	        }
	        
	        if( isset( $_GET['wpsight-offer'] ) && $_GET['wpsight-offer'] == 'not-available' ) {
				$query->query_vars['meta_key'] = '_listing_not_available';
	    		$query->query_vars['meta_value'] = '1';
			}
			
		}			
			
	}
	
	/**
	 *	restrict_listing_offers()
	 *	
	 *	Add filter by offer (sale, rent etc.) to let
	 *	users browse by wpsight_offers().
	 *	
	 *	@access	public
	 *	@uses	wpsight_post_type()
	 *	
	 *	@since 1.0.0
	 */
	function restrict_listing_offers() {	
	    global $wpdb, $typenow;
	    
	     if( $typenow != wpsight_post_type() )
			return;
	    
	    $offers = wpsight_offers(); ?>
	
		<select name="wpsight-offer">
			<option value=""><?php _e( 'Offers', 'wpcasa' ); ?></option><?php
			
			$current = isset( $_GET['wpsight-offer'] ) ? $_GET['wpsight-offer'] : false;
	
			foreach ( $offers as $offer => $label )
				echo '<option value="' . $offer . '"' . selected( $offer, $current, false ) . '>' . $label . '</option>'; ?>
	
			<option value="not-available"<?php selected( 'not-available', $current ); ?>><?php _e( 'Not Available', 'wpcasa' ); ?></option>
		</select><?php
	
	}
	
	/**
	 *	restrict_listing_taxonomy()
	 *	
	 *	Add filter by taxonomy to let
	 *	users browse by specific taxonomies.
	 *	
	 *	@access	public
	 *	@uses	wpsight_post_type()
	 *	@uses	wpsight_taxonomies()
	 *	@uses	get_terms()
	 *	@uses	wp_dropdown_categories()
	 *	
	 *	@since 1.0.0
	 */
	function restrict_listing_taxonomy() {	
	    global $typenow;
		
		if( $typenow != wpsight_post_type() )
			return;
	    
	    $filters = wpsight_taxonomies();
	    
		foreach( $filters as $tax_obj ) {
			
			$terms = get_terms( $tax_obj->name );
				               
		    if( ! empty( $terms ) ) {
		    		              
		    	wp_dropdown_categories(
		    		array(
						'show_option_all'   => $tax_obj->label,
						'option_none_value' => '',
						'taxonomy'          => $tax_obj->name,
						'name'              => $tax_obj->name,
						'selected'          => get_query_var( $tax_obj->query_var ),
						'hierarchical'      => $tax_obj->hierarchical,
						'show_count'        => false,
						'hide_empty'        => true,
						'orderby'           => 'NAME',
						'value_field'       => 'slug',
		    		)
		    	);            
		    }
		}
	}
	
	/**
	 *	restrict_listing_author()
	 *	
	 *	Add filter by author to let
	 *	users browse by specific agents.
	 *	
	 *	@access	public
	 *	@uses	wpsight_post_type()
	 *	@uses	wp_dropdown_users()
	 *	
	 *	@since 1.0.0
	 */
	public function restrict_listing_author() {	
		global $typenow;
		
		if( $typenow != wpsight_post_type() )
			return;
	
	    $args = array( 
	    	'name' => 'author', 
	    	'show_option_all' => __( 'Agents', 'wpcasa' ), 
	    	'selected', get_query_var( 'user' ) 
	    );
	
	    wp_dropdown_users( $args );

	}
	
	/**
	 *	parse_request_listing_id()
	 *	
	 *	Let users search listings by listing ID
	 *	on admin listings page.
	 *	
	 *	@access	public
	 *	@param	object	$query	WP_Query object
	 *	@uses	$wpdb->prepare()
	 *	@uses	$wpdb->get_col()
	 *	
	 *	@since 1.0.0
	 */
	public function parse_request_listing_id( $query ) {
	    global $wpdb, $pagenow;

		if( 'edit.php' != $pagenow )
		    return;
		
		// If it's not a search return
		if( empty( $query->query_vars['s'] ) )
		    return;
		
		// Search custom fields for listing ID
		
		$post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
		SELECT DISTINCT post_id FROM {$wpdb->postmeta}
		WHERE meta_value LIKE '%s'
		AND ( meta_key = '_listing_id' OR meta_key = '_property_id' )
		", $query->query_vars['s'] ) );
		
		if( ! empty( $post_ids_meta ) ) {
			unset( $query->query_vars['s'] );
			$query->query_vars['post__in'] = $post_ids_meta;
			do_action( 'parse_request_listing_id' );
		}
	    
	}
	
	/**
	 *	parse_request_listing_id_media()
	 *	
	 *	Let users search listings by listing ID
	 *	on admin media library page.
	 *	
	 *	@access	public
	 *	@param	object	$query	WP_Query object
	 *	@uses	$wpdb->prepare()
	 *	@uses	$wpdb->get_col()
	 *	
	 *	@since 1.0.0
	 */
	public function parse_request_listing_id_media( $query ) {
	    global $wpdb, $pagenow;

		if( 'upload.php' != $pagenow )
		    return;
		
		// If it's not a search return
		if( empty( $query->query_vars['s'] ) )
		    return;
		
		// Search custom fields for listing ID
		
		$post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
		SELECT DISTINCT post_id FROM {$wpdb->postmeta}
		WHERE meta_value LIKE '%s'
		AND ( meta_key = '_listing_id' OR meta_key = '_property_id' )
		", $query->query_vars['s'] ) );
		
		if( ! empty( $post_ids_meta ) ) {
			unset( $query->query_vars['s'] );    	
			$query->query_vars['post_parent'] = $post_ids_meta[0];
			do_action( 'parse_request_listing_id' );
		}
	    
	}
	
	// Set search results title accordingly
	
	function parse_request_listing_id_search_title() {
		add_filter( 'get_search_query', array( $this, 'parse_request_listing_id_search_query' ) );
	}
	
	function parse_request_listing_id_search_query() {	
		return $_GET['s'];	
	}
    
}
