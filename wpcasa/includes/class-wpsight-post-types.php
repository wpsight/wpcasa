<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * wpSight_Post_Type_Listing class
 */
class WPSight_Post_Type_Listing {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Register listing post type
		add_action( 'init', array( $this, 'register_post_type_listing' ), 0 );
		
		// Register custom post statuses
		add_action( 'init', array( $this, 'register_post_statuses' ), 0 );
        
        // Manage template for listing archive
        
        add_action( 'loop_start', array( $this, 'template_listing_archive' ) );
        add_action( 'loop_end', array( $this, 'template_listing_archive' ) );
        
        // Manage template for single listing
        
        add_action( 'loop_start', array( $this, 'template_listing_single' ) );
        add_action( 'loop_end', array( $this, 'template_listing_single' ) );
        
        // Optionally create location data from front end
        add_filter( 'wp', array( $this, 'listing_geolocation' ) );
        
        // Optionally add default meta data to listing
        add_action( 'wp_insert_post', array( $this, 'maybe_add_default_meta' ), 10, 2 );
        
        // Delete old listing previews to clean up
		add_action( 'wpsight_delete_listing_previews', array( $this, 'delete_listing_previews' ) );

		// Handle custom statuses in publish box

		foreach ( array( 'post', 'post-new' ) as $hook )
			add_action( "admin_footer-{$hook}.php", array( $this,'custom_statuses_submitdiv' ) );
		
		// Handle single listing print view
		
		add_filter( 'query_vars', array( $this, 'wpsight_print_query_vars' ) );		
		add_action( 'template_redirect', array( $this, 'wpsight_print_redirect' ) );
		
		add_action( 'wpsight_head_print', array( $this, 'wpsight_head_print_css' ) );
		add_filter( 'wpsight_head_print', array( $this, 'wpsight_head_print_robots' ) );

	}
	
	/**
	 * register_post_type_listing()
	 *
	 * This functions adds the custom post type listing
	 * and all the corresponding taxonomies.
 	 *
 	 * @access public
 	 * @uses apply_filters()
 	 * @uses register_taxonomy()
 	 * @uses wpsight_post_type()
 	 * @uses register_post_type()
 	 * @uses wpsight_post_type()
 	 *
 	 * @since 1.0.0
	 */
	public function register_post_type_listing() {
	
		// Custom admin capability
		$admin_capability = 'edit_listings';
	
		// Set labels and localize them
	
		$locations_name		= apply_filters( 'wpsight_taxonomy_locations_name', __( 'Locations', 'wpsight' ) );
		$locations_singular	= apply_filters( 'wpsight_taxonomy_locations_singular', __( 'Location', 'wpsight' ) );
	
		$locations_labels = array(
			'name' 				 		 => $locations_name,
			'singular_name' 	 		 => $locations_singular,
	        'menu_name' 		 		 => _x( 'Locations', 'taxonomy locations', 'wpsight' ),
	        'all_items' 		 		 => _x( 'All Locations', 'taxonomy locations', 'wpsight' ),
	        'edit_item' 		 		 => _x( 'Edit Location', 'taxonomy locations', 'wpsight' ),
	        'view_item' 		 		 => _x( 'View Location', 'taxonomy locations', 'wpsight' ),
	        'update_item' 		 		 => _x( 'Update Location', 'taxonomy locations', 'wpsight' ),
	        'add_new_item' 		 		 => _x( 'Add New Location', 'taxonomy locations', 'wpsight' ),
	        'new_item_name' 	 		 => _x( 'New Location Name', 'taxonomy locations', 'wpsight' ),
	        'parent_item'  		 		 => _x( 'Parent Location', 'taxonomy locations', 'wpsight' ),
	        'parent_item_colon'  		 => _x( 'Parent Location:', 'taxonomy locations', 'wpsight' ),
	        'search_items' 		 		 => _x( 'Search locations', 'taxonomy locations', 'wpsight' ),
	        'popular_items' 	 		 => _x( 'Popular Locations', 'taxonomy locations', 'wpsight' ),
	        'separate_items_with_commas' => _x( 'Separate locations with commas', 'taxonomy locations', 'wpsight' ),
	        'add_or_remove_items' 		 => _x( 'Add or remove locations', 'taxonomy locations', 'wpsight' ),
	        'choose_from_most_used' 	 => _x( 'Choose from the most used locations', 'taxonomy locations', 'wpsight' ),
	        'not_found' 		 		 => _x( 'No location found', 'taxonomy locations', 'wpsight' )
		);
		
		// Set args and rewrite rules
	
		$locations_args = array(
			'labels' 		=> $locations_labels,
			'hierarchical' 	=> true,
			'capabilities'  => array(
				'manage_terms' => 'manage_listing_terms',
	            'edit_terms'   => 'edit_listing_terms',
	            'delete_terms' => 'delete_listing_terms',
				'assign_terms' => 'assign_listing_terms'
			),
			'rewrite' 		=> array(
				'slug' 		   => apply_filters( 'wpsight_rewrite_loctions_slug', 'location' ),
				'with_front'   => false,
				'hierarchical' => true
			)
		);
		
		$locations_args = apply_filters( 'wpsight_taxonomy_locations_args', $locations_args );
		
		// Register taxonomy		
		register_taxonomy( 'location', array( wpsight_post_type() ), $locations_args );
	
		// Set labels and localize them
		
		$types_name		= apply_filters( 'wpsight_taxonomy_types_name', __( 'Listing Types', 'wpsight' ) );
		$types_singular	= apply_filters( 'wpsight_taxonomy_types_singular', __( 'Listing Type', 'wpsight' ) );
		
		$types_labels = array(
			'name' 						 => $types_name,
			'singular_name' 			 => $types_singular,
			'menu_name' 		 		 => _x( 'Listing Types', 'taxonomy types', 'wpsight' ),
	        'all_items' 		 		 => _x( 'All Listing Types', 'taxonomy types', 'wpsight' ),
	        'edit_item' 		 		 => _x( 'Edit Listing Type', 'taxonomy types', 'wpsight' ),
	        'view_item' 		 		 => _x( 'View Listing Type', 'taxonomy types', 'wpsight' ),
	        'update_item' 		 		 => _x( 'Update Listing Type', 'taxonomy types', 'wpsight' ),
	        'add_new_item' 		 		 => _x( 'Add New Listing Type', 'taxonomy types', 'wpsight' ),
	        'new_item_name' 	 		 => _x( 'New Listing Type Name', 'taxonomy types', 'wpsight' ),
	        'parent_item'  		 		 => _x( 'Parent Listing Type', 'taxonomy types', 'wpsight' ),
	        'parent_item_colon'  		 => _x( 'Parent Listing Type:', 'taxonomy types', 'wpsight' ),
	        'search_items' 		 		 => _x( 'Search listing types', 'taxonomy types', 'wpsight' ),
	        'popular_items' 	 		 => _x( 'Popular Listing Types', 'taxonomy types', 'wpsight' ),
	        'separate_items_with_commas' => _x( 'Separate listing types with commas', 'taxonomy types', 'wpsight' ),
	        'add_or_remove_items' 		 => _x( 'Add or remove listing types', 'taxonomy types', 'wpsight' ),
	        'choose_from_most_used' 	 => _x( 'Choose from the most used listing types', 'taxonomy types', 'wpsight' ),
	        'not_found' 		 		 => _x( 'No listing type found', 'taxonomy types', 'wpsight' )
		);
		
		// Set args and rewrite rules
	
		$types_args = array(
			'labels' 	   => $types_labels,
			'hierarchical' => false,
			'capabilities'  => array(
				'manage_terms' => 'manage_listing_terms',
	            'edit_terms'   => 'edit_listing_terms',
	            'delete_terms' => 'delete_listing_terms',
				'assign_terms' => 'assign_listing_terms'
			),
			'rewrite' 	   => array( 
				'slug' 		 => apply_filters( 'wpsight_rewrite_types_slug', 'type' ),
				'with_front' => false
			)
		);
		
		$types_args = apply_filters( 'wpsight_taxonomy_types_args', $types_args );
		
		// Register taxonomy		
		register_taxonomy( 'listing-type', array( wpsight_post_type() ), $types_args );
	
		// Set labels and localize them
		
		$features_name	   = apply_filters( 'wpsight_taxonomy_features_name', __( 'Features', 'wpsight' ) );
		$features_singular = apply_filters( 'wpsight_taxonomy_features_singular', __( 'Feature', 'wpsight' ) );
		
		$features_labels = array(
			'name' 						 => $features_name,
			'singular_name' 			 => $features_singular,
			'menu_name' 		 		 => _x( 'Features', 'taxonomy features', 'wpsight' ),
	        'all_items' 		 		 => _x( 'All Features', 'taxonomy features', 'wpsight' ),
	        'edit_item' 		 		 => _x( 'Edit Feature', 'taxonomy features', 'wpsight' ),
	        'view_item' 		 		 => _x( 'View Feature', 'taxonomy features', 'wpsight' ),
	        'update_item' 		 		 => _x( 'Update Feature', 'taxonomy features', 'wpsight' ),
	        'add_new_item' 		 		 => _x( 'Add New Feature', 'taxonomy features', 'wpsight' ),
	        'new_item_name' 	 		 => _x( 'New Feature Name', 'taxonomy features', 'wpsight' ),
	        'parent_item'  		 		 => _x( 'Parent Feature', 'taxonomy features', 'wpsight' ),
	        'parent_item_colon'  		 => _x( 'Parent Feature:', 'taxonomy features', 'wpsight' ),
	        'search_items' 		 		 => _x( 'Search features', 'taxonomy features', 'wpsight' ),
	        'popular_items' 	 		 => _x( 'Popular Features', 'taxonomy features', 'wpsight' ),
	        'separate_items_with_commas' => _x( 'Separate features with commas', 'taxonomy features', 'wpsight' ),
	        'add_or_remove_items' 		 => _x( 'Add or remove features', 'taxonomy features', 'wpsight' ),
	        'choose_from_most_used' 	 => _x( 'Choose from the most used features', 'taxonomy features', 'wpsight' ),
	        'not_found' 		 		 => _x( 'No feature found', 'taxonomy features', 'wpsight' )
		);
		
		// Set args and rewrite rules
	
		$features_args = array(
			'labels' 	   => $features_labels,
			'hierarchical' => false,
			'capabilities'  => array(
				'manage_terms' => 'manage_listing_terms',
	            'edit_terms'   => 'edit_listing_terms',
	            'delete_terms' => 'delete_listing_terms',
				'assign_terms' => 'assign_listing_terms'
			),
			'rewrite' 	   => array(
				'slug' 		 => apply_filters( 'wpsight_rewrite_features_slug', 'feature' ),
				'with_front' => false
			)
		);
		
		$features_args = apply_filters( 'wpsight_taxonomy_features_args', $features_args );
		
		// Register taxonomy		
		register_taxonomy( 'feature', array( wpsight_post_type() ), $features_args );
	
		// Set labels and localize them
		
		$categories_name	 = apply_filters( 'wpsight_taxonomy_categories_name', __( 'Categories', 'wpsight' ) );
		$categories_singular = apply_filters( 'wpsight_taxonomy_categories_singular', __( 'Category', 'wpsight' ) );	
		
		$categories_labels = array(
			'name' 			=> $categories_name,
			'singular_name' => $categories_singular
		);
		
		// Set args and rewrite rules
	
		$categories_args = array(
			'labels' 	   => $categories_labels,
			'hierarchical' => true,
			'capabilities'  => array(
				'manage_terms' => 'manage_listing_terms',
	            'edit_terms'   => 'edit_listing_terms',
	            'delete_terms' => 'delete_listing_terms',
				'assign_terms' => 'assign_listing_terms'
			),
			'rewrite' 	   => array( 
				'slug' 		   => apply_filters( 'wpsight_rewrite_categories_slug', 'listing-category' ), 
				'with_front'   => false,
				'hierarchical' => true
			)
		);
		
		$categories_args = apply_filters( 'wpsight_taxonomy_categories_args', $categories_args );
		
		// Register taxonomy		
		register_taxonomy( 'listing-category', array( wpsight_post_type() ), $categories_args );
		
		// Set post type labels

		$labels = array( 
		    'name' 				 => _x( 'Listings', 'listing', 'wpsight' ),
		    'singular_name' 	 => _x( 'Listing', 'listing', 'wpsight' ),
		    'add_new' 			 => _x( 'Add New', 'listing', 'wpsight' ),
		    'add_new_item' 		 => _x( 'Add New Listing', 'listing', 'wpsight' ),
		    'edit_item' 		 => _x( 'Edit Listing', 'listing', 'wpsight' ),
		    'new_item' 			 => _x( 'New Listing', 'listing', 'wpsight' ),
		    'view_item' 		 => _x( 'View Listing', 'listing', 'wpsight' ),
		    'search_items' 		 => _x( 'Search Listings', 'listing', 'wpsight' ),
		    'not_found' 		 => _x( 'No listings found', 'listing', 'wpsight' ),
		    'not_found_in_trash' => _x( 'No listings found in Trash', 'listing', 'wpsight' ),
		    'menu_name' 		 => _x( 'Listings', 'listing', 'wpsight' ),
		);
		
		$labels = apply_filters( 'wpsight_post_type_labels_listing', $labels );
		
		// Set post type arguments
		
		$args = array(
			'label'               => _x( 'Listings', 'listing', 'wpsight' ),
			'description'         => _x( 'Searchable listings with detailed information about the corresponding item.', 'listing', 'wpsight' ),
		    'labels' 			  => $labels,
		    'hierarchical' 		  => false,        
		    'supports' 			  => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'revisions', 'excerpt' ),
		    'public' 			  => true,
		    'show_ui' 			  => true,
		    'show_in_menu' 		  => true,
		    'show_in_nav_menus'   => true,
			'menu_position' 	  => 50,
		    'menu_icon'			  => 'dashicons-location',
		    'publicly_queryable'  => true,
		    'exclude_from_search' => false,
		    'has_archive' 		  => true,
		    'query_var' 		  => true,
		    'can_export' 		  => true,
		    'rewrite' 			  => array( 'slug' => apply_filters( 'wpsight_rewrite_listings_slug', 'listing' ), 'with_front' => false ),
		    'capability_type' 	  => array( 'listing', 'listings' ),
		    'map_meta_cap'		  => true
		);
		
		$args = apply_filters( 'wpsight_post_type_args_listing', $args );
		
		// Register post type		
		register_post_type( 'listing', $args );
    	
	}
	
	/**
	 * register_post_statuses()
	 *
	 * Register custom post statuses.
 	 *
 	 * @access public
 	 * @uses wpsight_statuses()
 	 * @uses register_post_status()
 	 *
 	 * @since 1.0.0
	 */
	public function register_post_statuses() {
	    global $wp_post_statuses;
	    
	    foreach( wpsight_statuses() as $status => $args ) {
		    
		    if( ! in_array( $status, array_keys( $wp_post_statuses ) ) )		    
	    		register_post_status( $status, $args );
	    	
	    }
	    
    }
	
	/**
	 * template_listing_archive()
	 *
	 * Replace default output on listing archive
	 * pages (taxonomies, search or agents) with
	 * our templated output.
 	 *
 	 * @access public
 	 * @param object $query WP_Query of the corresponding loop
 	 *
 	 * @uses $query->is_main_query()
 	 * @uses wpsight_is_listing_archive()
 	 * @uses current_filter()
 	 * @uses wpsight_listings()
 	 *
 	 * @since 1.0.0
	 */
	public function template_listing_archive( $query ) {
		
		// Make sure this is a main query
		
		if ( ! $query->is_main_query() )
			return;
			
		// Only on listing archives
		
		if( wpsight_is_listing_archive() ) {
			
			// Remove original output
		
			if ( 'loop_start' === current_filter() ) {
			    ob_start();
			} else {
				ob_end_clean();
			}
			
			// Create custom loop output
			
			// Action before listing archive template is called
			do_action( 'wpsight_template_listing_archive_before', $query );
			
			// Get listings for this query	
			wpsight_listings( $query );
			
			// Action after listing archive template is called
			do_action( 'wpsight_template_listing_archive_after', $query );
		
		}
		
	}
	
	/**
	 * template_listing_single()
	 *
	 * Replace default output on single listing
	 * pages with our templated output.
 	 *
 	 * @access public
 	 * @param object $query WP_Query of the corresponding loop
 	 *
 	 * @uses $query->is_main_query()
 	 * @uses wpsight_is_listing_archive()
 	 * @uses current_filter()
 	 * @uses wpsight_listing()
 	 *
 	 * @since 1.0.0
	 */
	public function template_listing_single( $query ) {
		
		// Make sure this is a main query
		
		if ( ! $query->is_main_query() )
			return;
		
		// If we have a single-listing.php template and widgets are active, display them
		
		if( wpsight_locate_template( 'single-listing.php' ) && ( is_active_sidebar( 'listing' ) || is_active_sidebar( 'listing-top' ) || is_active_sidebar( 'listing-bottom' ) ) )
			return;
		
		// Only on single listing pages
		
		if( is_singular( wpsight_post_type() ) ) {
			
			// Remove original output
		
			if ( 'loop_start' === current_filter() ) {
			    ob_start();
			} else {
				ob_end_clean();
			}
			
			// Create custom loop output
			
			// Action before listing archive template is called
			do_action( 'wpsight_template_listing_single_before', $query );
			
			// Get single listing
			wpsight_listing( $query->post->ID );
			
			// Action after listing archive template is called
			do_action( 'wpsight_template_listing_single_after', $query );
		
		}
		
	}
	
	/**
	 * listing_geolocation()
	 *
	 * If a listing is called on the front end,
	 * check if we need to create geolocation data
	 * to ensure backwards compatibiliy with older
	 * wpCasa versions.
 	 *
 	 * @access public
 	 * @uses wpsight_is_listing_single()
 	 * @uses get_post_meta()
 	 * @uses wpSight_Geocode::has_location_data()
 	 * @uses wpSight_Geocode::generate_location_data()
 	 * 
 	 * @since 1.0.0
	 */
	public function listing_geolocation() {
		
		if( ! wpsight_is_listing_single() || is_admin() )
			return;
		
		// Update map location information		
		if ( ! wpSight_Geocode::has_location_data( get_the_id() ) && ( $location = get_post_meta( get_the_id(), '_map_address', true ) ) ) {
			wpSight_Geocode::generate_location_data( get_the_id(), $location );
		}
		
	}
	
	/**
	 * maybe_add_default_meta()
	 *
	 * Optionally create default post meta data
	 * when a post is created or saved.
	 *
	 * @access public
	 * @param int $post_id Post ID of the corresponding entry
	 * @param object WP_Post object
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/wp_insert_post
	 *
	 * @uses wpsight_post_type()
	 * @uses add_post_meta()
	 *
	 * @since 1.0.0
	 */
	public function maybe_add_default_meta( $post_id, $post = '' ) {
		if ( empty( $post ) || wpsight_post_type() == $post->post_type ) {
			add_post_meta( $post_id, '_listing_not_available', 0, true );
			add_post_meta( $post_id, '_listing_sticky', 0, true );
			add_post_meta( $post_id, '_listing_featured', 0, true );
		}
	}
	
	/**
	 * delete_listing_previews()
	 *
	 * Delete old listings with preview status when actived.
	 *
	 * @access public
	 * @uses wpsight_delete_listing_previews()
	 * @see /functions/wpsight-listings.php
	 *
	 * @since 1.0.0
	 */
	public function delete_listing_previews() {
		
		// Delete old listing previews if desired

		if ( apply_filters( 'wpsight_delete_listing_previews', true ) )
			wpsight_delete_listing_previews();
		
	}
    
    /**
	 * custom_statuses_submitdiv()
	 *
	 * Add custom listing statuses to
	 * publish box on listing edit page.
	 *
	 * @access public
	 * @uses wpsight_post_type()
	 *
	 * @since 1.0.0
	 */
    public function custom_statuses_submitdiv() {
		global $post, $post_type;

		// Only on listing edit pages

		if ( wpsight_post_type() !== $post_type )
			return;
		
		// Get custom statuses
		$statuses = wpsight_statuses();

		// Get all non-builtin post status and add them as <option>

		$options = $display = '';

		foreach ( $statuses as $status => $args ) {

			$selected = selected( $post->post_status, $status, false );
			$name = $args['label'];

			// If one of our custom post status is selected, remember it
			$selected AND $display = $name;

			// Build the options
			$options .= "<option{$selected} value='{$status}'>{$name}</option>";

		}
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function($) {

				postStatus = $('#post_status');
				status = '<?php echo $post->post_status; ?>';
				
				save_expired = '<?php _ex( 'Save as Expired', 'listing post status', 'wpsight' ); ?>';
				save_preview = '<?php _ex( 'Save as Preview', 'listing post status', 'wpsight' ); ?>';
				save_pending = '<?php _ex( 'Save as Pending', 'listing post status', 'wpsight' ); ?>';

				<?php if ( ! empty( $display ) ) : ?>
					$( '#post-status-display' ).html( '<?php echo $display; ?>' );
				<?php endif; ?>
				
				if ( status == 'pending_payment' )
					$('#save-post').show().val( save_pending );
				
				if ( status == 'expired' )
					$('#save-post').show().val( save_expired );
				
				if ( status == 'preview' )
					$('#save-post').show().val( save_preview );

				var select = $( '#post-status-select' ).find( 'select' );
				$( select ).html( "<?php echo $options; ?>" );
				
				$('#post-status-select').find('.save-post-status').click( function( event ) {
					
					if( $('option:selected', postStatus).val() == 'pending_payment' )
						$('#save-post').show().val( save_pending );

					if ( $('option:selected', postStatus).val() == 'expired' )
						$('#save-post').show().val( save_expired );
					
					if( $('option:selected', postStatus).val() == 'preview' )
						$('#save-post').show().val( save_preview );

				});

			} );
		</script>
		<?php
	}
	
	/**
	 * wpsight_print_query_vars()
	 *
	 * Add print to query vars.
 	 *
 	 * @since 1.0.0
	 */
	public function wpsight_print_query_vars( $vars ) {
	
	    $new_vars = array( 'print' );
	    $vars = array_merge( $new_vars, $vars );
	    
	    return $vars;
	}
	
	/**
	 * wpsight_print_redirect()
	 *
	 * Redirect to print view template when
	 * query var 'print' is set.
	 *
	 * @uses wpsight_get_template()
 	 *
 	 * @since 1.0.0
	 */	
	function wpsight_print_redirect() {
	    
	    global $wp, $wp_query;
	    
	    if( isset( $wp->query_vars['print'] ) && absint( $wp->query_vars['print'] ) ) {
	        wpsight_get_template( 'listing-print.php' );
	        exit();
	    }

	}
	
	/**
	 * wpsight_head_print_css()
	 *
	 * Add print styles to print header
	 * using wpsight_head_print action hook.
	 *
	 * @since 1.0.0
	 */
	function wpsight_head_print_css() { ?>
	<link href="<?php echo WPSIGHT_PLUGIN_URL; ?>/assets/css/wpsight-print.css" rel="stylesheet" type="text/css">
	<?php
	}
	
	/**
	 * wpsight_head_print_robots()
	 *
	 * Disallow indexing of print pages
	 * using robots:noindex.
	 *
	 * @since 1.0.0
	 */	
	function wpsight_head_print_robots() {	?>
	<meta name="robots" content="noindex" />
	<?php
	}

}
