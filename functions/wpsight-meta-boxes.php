<?php
/**
 * WPSight meta box functions
 *
 * @package WPSight
 * @subpackage Functions
 */

/**
 * wpsight_meta_boxes()
 *
 * Merging arrays of all WPSight meta boxes
 *
 * @uses wpsight_meta_box_listing_*()
 * @return array Array of all listing meta boxes
 *
 * @since 1.0.0
 */

function wpsight_meta_boxes() {
	
	// Merge all meta box arrays
	
	$meta_boxes = array(
		'listing_attributes' => wpsight_meta_box_listing_attributes(),
		'listing_price' 	 => wpsight_meta_box_listing_price(),
		'listing_details' 	 => wpsight_meta_box_listing_details(),
		'listing_images' 	 => wpsight_meta_box_listing_images(),
		'listing_location' 	 => wpsight_meta_box_listing_location(),
		'listing_agent' 	 => wpsight_meta_box_listing_agent()
	);
	
	// Add custom spaces if any
	
	foreach( wpsight_meta_box_spaces() as $key => $space )
		$meta_boxes[$key] = $space;

    return apply_filters( 'wpsight_meta_boxes', $meta_boxes );

}

/**
 * wpsight_meta_box_listing_attributes()
 *
 * Create listing attributes meta box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_attributes() {
	
	// Set meta box fields
	
	$fields = array(
		'availability' => array(
			'name'  	=> __( 'Availability', 'wpsight' ),
			'id'    	=> '_listing_not_available',
			'type'  	=> 'checkbox',
			'label_cb'	=> __( 'Item not available', 'wpsight' ),
			'desc'  	=> __( 'The item is currently not available as it has been sold or rented.', 'wpsight' ),
			'dashboard' => false,
			'priority' 	=> 10
		)
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_attributes_fields', $fields ) );

    // Set meta box

    $meta_box = array(
    	'id' 		  => 'listing_attributes',
    	'title'		  => __( 'Listing Attributes', 'wpsight' ),
    	'pages'		  => array( wpsight_post_type() ),
    	'context'	  => 'side',
    	'priority'	  => 'core',
    	'fields'	  => $fields
    );
    
    return apply_filters( 'wpsight_meta_box_listing_attributes', $meta_box );

}

/**
 * wpsight_meta_box_listing_images()
 *
 * Create listing images meta box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_images() {
	
	// Set meta box fields
	
	$fields = array(
		'images' => array(
    	    'name'  	=> __( 'Images', 'wpsight' ),
    	    'id'    	=> '_gallery',
    	    'type'  	=> 'image_multiple',
    	    'repeatable'=> true,
    	    'sortable'	=> true,
    	    'desc'		=> false,
    	    'dashboard' => false
		)
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_images_fields', $fields ) );

    // Set meta box

    $meta_box = array(
    	'id' 		  => 'listing_images',
    	'title'		  => __( 'Listing Images', 'wpsight' ),
    	'pages'		  => array( wpsight_post_type() ),
    	'context'	  => 'normal',
    	'priority'	  => 'high',
    	'fields'	  => $fields
    );

    return apply_filters( 'wpsight_meta_box_listing_images', $meta_box );

}

/**
 * wpsight_meta_box_listing_price()
 *
 * Create listing price meta box
 *
 * @uses wpsight_offers()
 * @uses wpsight_rental_periods()
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_price() {
	
	// Set meta box fields
	
	$fields = array(
		'price' => array(
			'name'  	=> __( 'Price', 'wpsight' ) . ' (' . wpsight_get_currency() . ')',
			'id'    	=> '_price',
			'type'  	=> 'text',
			'desc'		=> __( 'No currency symbols or thousands separators', 'wpsight' ),
			'dashboard' => true,
			'priority' 	=> 10
		),
		'offer' => array(
			'name'  	=> __( 'Offer', 'wpsight' ),
			'id'    	=> '_price_offer',
			'type'  	=> 'radio',
			'options' 	=> wpsight_offers(),
			'default'	=> 'sale',
			'dashboard' => true,
			'priority' 	=> 20
		),
		'period' => array(
			'name'  	=> __( 'Period', 'wpsight' ),
			'id'    	=> '_price_period',
			'type'  	=> 'select',
			'options' 	=> array_merge( array( '' => __( 'None', 'wpsight' ) ), wpsight_rental_periods() ),
			'dashboard' => true,
			'priority' 	=> 30
		)
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_price_fields', $fields ) );

    // Set meta box

    $meta_box = array(    
    	'id' 		  => 'listing_price',
    	'title'		  => __( 'Listing Price', 'wpsight' ),
    	'pages'		  => array( wpsight_post_type() ),
    	'context'	  => 'normal',
    	'priority'	  => 'high',
    	'fields'	  => $fields    	
    );
    
    return apply_filters( 'wpsight_meta_box_listing_price', $meta_box );

}

/**
 * wpsight_meta_box_listing_details()
 *
 * Create listing details meta box
 *
 * @uses wpsight_user_can_edit_listing_id()
 * @uses wpsight_measurements()
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_details() {
	
	// Set meta box fields
	
	$fields = array(
		'id' => array(
			'name'  	=> __( 'Listing ID', 'wpsight' ),
			'id'    	=> '_listing_id',
			'type'  	=> 'text',
			'dashboard' => wpsight_user_can_edit_listing_id() ? true : 'disabled',
			'readonly'	=> wpsight_user_can_edit_listing_id() ? false : true,
			'priority' 	=> 10
		)
	);
	
	/**
     * Add listing details fields
     */
     
    $units = wpsight_measurements();
           	
	$prio = 20;
    
    foreach( wpsight_details() as $detail => $value ) {
    
        if( ! empty( $value['label'] ) ) {
        
        	// Optionally add measurement label to title
           	$unit  = '';
        	
        	if( ! empty( $value['unit'] ) ) {
        		$unit = $value['unit'];
        		$unit = $units[$unit];
        		$unit = ' (' . $unit . ')';
        	}
        	
        	// If there is select data, create select fields else text
        	
        	if( ! empty( $value['data'] ) ) {
        	
        		$fields[$detail] = array(
        		    'name'    	=> $value['label'] . $unit,
        		    'id' 	  	=> '_' . $detail,
        		    'type'	  	=> 'select',
        		    'options' 	=> $value['data'],
        		    'desc'	  	=> $value['description'],
					'dashboard' => true,
					'priority' 	=> $prio
        		);
        	
        	} else {
    
        		$fields[$detail] = array(
        		    'name'    	=> $value['label'] . $unit,
        		    'id' 	  	=> '_' . $detail,
        		    'type'	  	=> 'text',
        		    'desc'	  	=> $value['description'],
					'dashboard' => true,
					'priority' 	=> $prio
        		);
        	
        	} // end if
        
        } // end if
        
        $prio +=10;
    
    } // end foreach
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_details_fields', $fields ) );

    // Set meta box

    $meta_box = array(    
    	'id' 		  => 'listing_details',
    	'title'		  => __( 'Listing Details', 'wpsight' ),
    	'pages'		  => array( wpsight_post_type() ),
    	'context'	  => 'normal',
    	'priority'	  => 'high',
    	'fields'	  => $fields
    );

	return apply_filters( 'wpsight_meta_box_listing_details', $meta_box );

}

/**
 * wpsight_meta_box_listing_location()
 *
 * Create listing location meta box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_location() {
    
    // Create map fields
    
    $fields = array(
	    'address' => array(
			'name'  	=> __( 'Address', 'wpsight' ),
			'id'    	=> '_map_address',
			'type'  	=> 'text',
			'desc'		=> __( 'e.g. <code>Marbella, Spain</code> or <code>Platz der Republik 1, 10557 Berlin</code>', 'wpsight' ),
			'dashboard' => true,
			'class'		=> 'map-search',
			'priority' 	=> 10
		),
		'note' => array(
			'name'  	=> __( 'Public Note', 'wpsight' ),
			'id'    	=> '_map_note',
			'type'  	=> 'text',
			'desc'		=> __( 'e.g. <code>Location is not the exact address of the listing</code>', 'wpsight' ),
			'dashboard' => true,
			'priority' 	=> 40
		),
		'secret' => array(
			'name'  	=> __( 'Secret Note', 'wpsight' ),
			'id'    	=> '_map_secret',
			'type'  	=> 'textarea',
			'desc'		=> __( 'Will not be displayed on the website (e.g. complete address)', 'wpsight' ),
			'dashboard' => true,
			'priority' 	=> 50
		),
		'exclude' => array(
			'name'  	=> __( 'Listings Map', 'wpsight' ),
			'id'    	=> '_map_exclude',
			'type'  	=> 'checkbox',
			'label_cb'  => __( 'Exclude from general listings map', 'wpsight' ),
			'dashboard' => false,
			'priority' 	=> 60
		)
    );
    
    // Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_location_fields', $fields ) );
    
    // Create meta box

    $meta_box = array(    
    	'id' 		  => 'listing_location',
    	'title'		  => __( 'Listing Location', 'wpsight' ),
    	'pages'		  => array( wpsight_post_type() ),
    	'context'	  => 'normal',
    	'priority'	  => 'high',
    	'fields'	  => $fields    	
    );

	return apply_filters( 'wpsight_meta_box_listing_location', $meta_box );

}

/**
 * wpsight_meta_box_listing_agent()
 *
 * Create listing agent box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_agent() {
    	
    // Set meta box fields
	
	$fields = array(
		'name' => array(
			'name'  	=> __( 'Name', 'wpsight' ),
			'id'    	=> '_agent_name',
			'type'  	=> 'text',
			'desc'		=> false,
			'dashboard' => true,
			'default'	=> wp_get_current_user()->display_name,
			'priority' 	=> 10
		),
		'company' => array(
			'name'  	=> __( 'Company', 'wpsight' ),
			'id'    	=> '_agent_company',
			'type'  	=> 'text',
			'desc'		=> false,
			'dashboard' => true,
			'default'	=> get_user_meta( wp_get_current_user()->ID, 'company', true ),
			'priority' 	=> 20
		),
		'description' => array(
			'name'  	=> __( 'Description', 'wpsight' ),
			'id'    	=> '_agent_description',
			'type'  	=> 'textarea',
			'desc'		=> false,
			'dashboard' => true,
			'default'	=> get_user_meta( wp_get_current_user()->ID, 'description', true ),
			'priority' 	=> 30
		),
		'website' => array(
			'name'  	=> __( 'Website', 'wpsight' ),
			'id'    	=> '_agent_website',
			'type'  	=> 'text_url',
			'desc'		=> false,
			'dashboard' => true,
			'default'	=> wp_get_current_user()->user_url,
			'priority' 	=> 40
		),
		'twitter' => array(
			'name'  	=> __( 'Twitter', 'wpsight' ),
			'id'    	=> '_agent_twitter',
			'type'  	=> 'text',
			'desc'		=> false,
			'dashboard' => true,
			'default'	=> get_user_meta( wp_get_current_user()->ID, 'twitter', true ),
			'priority' 	=> 50
		),
		'facebook' => array(
			'name'  	=> __( 'Facebook', 'wpsight' ),
			'id'    	=> '_agent_facebook',
			'type'  	=> 'text',
			'desc'		=> false,
			'dashboard' => true,
			'default'	=> get_user_meta( wp_get_current_user()->ID, 'facebook', true ),
			'priority' 	=> 60
		),
		'logo' => array(
			'name'  	=> __( 'Logo', 'wpsight' ),
			'id'    	=> '_agent_logo_id',
			'type'  	=> 'image',
			'desc'		=> false,
			'dashboard' => true,
			'priority' 	=> 70
		)
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_agent_fields', $fields ) );

    // Set meta box

    $meta_box = array(    
    	'id' 	   => 'listing_agent',
    	'title'	   => __( 'Listing Agent', 'wpsight' ),
    	'pages'	   => array( wpsight_post_type() ),
    	'context'  => 'normal',
    	'priority' => 'high',
    	'fields'   => $fields
    );

    return apply_filters( 'wpsight_meta_box_listing_agent', $meta_box );

}

/**
 * wpsight_meta_box_spaces()
 *
 * Create listing spaces box(es)
 *
 * @uses wpsight_spaces()
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 * @see /functions/wpsight-general.php => L768
 *
 * @since 1.0.0
 */

function wpsight_meta_box_spaces() {
    
    $meta_boxes = array();
    	
    // Loop through existing spaces
    	
    foreach( wpsight_spaces() as $key => $space ) {
	    
	    // Check if multiple fields
	    
	    if( ! isset( $space['fields'] ) || empty( $space['fields'] ) ) {
		    
		    // If not, set one field
		    
		    $fields = array(
				$key => array(
    		    	'name'  => $space['label'],
    		    	'id'    => $space['key'],
    		    	'type'  => $space['type'],
    		    	'desc'	=> $space['description'],
    		    	'rows'	=> $space['rows']
    		    )
		    );
		    
	    } else {
		    
		    // If yes, set meta box fields

		    $fields = $space['fields'];
		    
		    // Set info field as description
		    
		    if( isset( $space['description'] ) && ! empty( $space['description'] ) )
		    	$fields['description'] = array(
			    	'id' 	=> $space['key'] . '_desc',
			    	'name' 	=> $space['description'],
			    	'type' 	=> 'info',
			    	'priority' => 9999
		    	);

	    }
	    
	    // Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_spaces_fields', $fields ) );
    
    	// Set meta box

    	$meta_boxes[$key] = array(
    	
    		'id' 		  => $key,
    		'title'		  => $space['title'],
    		'pages'		  => $space['post_type'],
    		'context'	  => 'normal',
    		'priority'	  => 'high',
    		'fields'	  => $fields
    		
    	);
    
    } // endforeach
    
    return apply_filters( 'wpsight_meta_box_spaces', $meta_boxes );

}
