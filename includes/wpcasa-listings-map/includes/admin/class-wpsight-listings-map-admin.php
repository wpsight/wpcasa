<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listings_Map_Admin class
 */
class WPSight_Listings_Map_Admin {

	/**
	 *	Constructor
	 */
	public function __construct() {
		
		if( version_compare( '1.2.0', WPSIGHT_VERSION, '<=' ) ) {
			
			// Add addon options to maps options
			add_filter( 'wpsight_options_maps', array( $this, 'options' ) );
		
		} else {
			
			// Add addon options to general plugin settings
			add_filter( 'wpsight_options', array( $this, 'options' ) );

		}
		
		// Add meta box option to exclude listing
		add_filter( 'wpsight_meta_box_listing_location_fields', array( $this, 'meta_box' ) );

	}

	/**
	 *	listings_map_options()
	 *	
	 *	Add add-on options to settings page
	 *
	 *	@param	array	$options	Registered options	
	 *	@return	array	$options
	 *	
	 *	@since 1.0.0
	 */
	public function options( $options ) {
		$options_maps['heading_listings_map_general'] = array(
			'name'		=> __( 'General Map Settings', 'wpcasa-listings-map' ),
			'desc'		=> __( '', 'wpcasa-listings-map' ),
			'id'		=> 'heading_listings_map_general',
			'position'	=> 10,
			'type'		=> 'heading',
		);
		
		$options_maps['listings_map_page'] = array(
			'name' 		=> __( 'Map Page', 'wpcasa-listings-map' ),
			'desc' 		=> __( 'Please select the page that contains the listings map shortcode.', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_page',
			'position'	=> 20,
			'type' 		=> 'pages'
		);
		
		$options_maps['listings_map_panel'] = array(
			'name'     	=> __( 'Listings Panel', 'wpcasa-listings-map' ),
			'cb_label' 	=> __( 'Show toggle link in listings panel', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Will show a link in the listings panel (next to orderby options) and the map below when clicked.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_panel',
			'position'	=> 30,
			'type'     	=> 'checkbox'
		);

        $options_maps['listings_map_display'] = array(
            'name'     	=> __( 'Listings Map Display', 'wpcasa-listings-map' ),
            'cb_label' 	=> __( 'Show map above the listings', 'wpcasa-listings-map' ),
            'desc'     	=> __( 'Show map above the listings ', 'wpcasa-listings-map' ),
            'id'       	=> 'listings_map_display',
            'type'     	=> 'checkbox',
            'position'	=> 30,
            'default'	=> 1,
        );
		
		$options_maps['listings_map_panel_link'] = array(
			'name'     	=> __( 'Link Text', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Please enter the text for the listings panel link.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_panel_link',
			'position'	=> 40,
			'type'     	=> 'text',
			'default'	=> __( 'Toggle Map', 'wpcasa-listings-map' ),
		);
		
		$options_maps['listings_map_nr'] = array(
			'name' 		=> __( 'Number', 'wpcasa-listings-map' ),
			'desc' 		=> __( 'Please enter the maximum number of listings (<code>-1</code> to show all). Keep in mind: The more listings you want to appear on the map, the more it can impact the loading speed of your website.', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_nr',
			'position'	=> 50,
			'type' 		=> 'text',
			'default'	=> 50
		);
		
		$options_maps['heading_listings_map_appearance'] = array(
			'name'		=> __( 'Map Appearance', 'wpcasa' ),
			'desc'		=> __( '', 'wpcasa' ),
			'id'		=> 'heading_listings_map_appearance',
			'position'	=> 60,
			'type'		=> 'heading'
		);
		
		$options_maps['listings_map_width'] = array(
			'name' 		=> __( 'Width', 'wpcasa-listings-map' ),
			'desc' 		=> __( 'Please enter the default width of the map (in <code>px</code> or <code>%</code>).', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_width',
			'position'	=> 70,
			'type' 		=> 'text',
			'default'	=> '100%'
		);
		
		$options_maps['listings_map_height'] = array(
			'name' 		=> __( 'Height', 'wpcasa-listings-map' ),
			'desc' 		=> __( 'Please enter the default height of the map (in <code>px</code>).', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_height',
			'position'	=> 80,
			'type' 		=> 'text',
			'default'	=> '600px'
		);
		
		$options_maps['listings_map_type'] = array(
			'name' 		=> __( 'Type', 'wpcasa-listings-map' ),
			'desc'		=> __( 'Please select the default map type.', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_type',
			'position'	=> 90,
			'type' 		=> 'select',
			'options'	=> array(
				'ROADMAP'   => __( 'Roadmap', 'wpcasa-listings-map' ),
				'SATELLITE' => __( 'Sattelite', 'wpcasa-listings-map' ),
				'HYBRID'    => __( 'Hybrid', 'wpcasa-listings-map' ),
				'TERRAIN'   => __( 'Terrain', 'wpcasa-listings-map' )
			),
			'default'	=> 'ROADMAP'
		);
		
		$options_maps['listings_map_style'] = array(
			'name' 		=> __( 'Style', 'wpcasa-listings-map' ),
			'desc' 		=> __( 'Please select the style of the listings map. Styles will only apply to ROADMAP or TERRAIN map type.', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_style',
			'position'	=> 100,
			'type' 		=> 'select',
			'options'	=> WPSight_Listings_Map_Styles::get_map_styles_choices( true )
		);
		
		$options_maps['heading_listings_map_controls'] = array(
			'name'		=> __( 'Map Controls', 'wpcasa' ),
			'desc'		=> __( '', 'wpcasa' ),
			'id'		=> 'heading_listings_map_controls',
			'position'	=> 110,
			'type'		=> 'heading'
		);
		
		$options_maps['listings_map_control_type'] = array(
			'name'     	=> __( 'Type Control', 'wpcasa-listings-map' ),
			'cb_label' 	=> __( 'Display type control', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Let the user change the map type.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_control_type',
			'position'	=> 120,
			'type'     	=> 'checkbox',
			'default'	=> '1'
		);
		
		$options_maps['listings_map_scrollwheel'] = array(
			'name'     	=> __( 'Scrollwheel', 'wpcasa-listings-map' ),
			'cb_label' 	=> __( 'Enable scroll wheel', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Let the user change the map zoom using the scrollwheel.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_scrollwheel',
			'position'	=> 130,
			'type'     	=> 'checkbox'
		);
		
		$options_maps['listings_map_streetview'] = array(
			'name'     	=> __( 'Streetview', 'wpcasa-listings-map' ),
			'cb_label' 	=> __( 'Enable streetview', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Let the user activate streetview on the map.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_streetview',
			'position'	=> 140,
			'type'     	=> 'checkbox',
			'default'	=> '1'
		);
		
		$options_maps['heading_listings_map_infobox'] = array(
			'name'		=> __( 'Infobox Settings', 'wpcasa' ),
			'desc'		=> __( '', 'wpcasa' ),
			'id'		=> 'heading_listings_map_infobox',
			'position'	=> 150,
			'type'		=> 'heading'
		);
		
		$options_maps['listings_map_infobox_event'] = array(
			'name' 		=> __( 'Event', 'wpcasa-listings-map' ),
			'desc'		=> __( 'Show infobox on hover or click?', 'wpcasa-listings-map' ),
			'id'   		=> 'listings_map_infobox_event',
			'position'	=> 160,
			'type' 		=> 'radio',
			'options'	=> array(
				'mouseover'	=> __( 'Mouseover', 'wpcasa-listings-map' ),
				'click'		=> __( 'Click', 'wpcasa-listings-map' )
			),
			'default'	=> 'mouseover'
		);
		
		$options_maps['listings_map_infobox_close'] = array(
			'name'     	=> __( 'Close Button', 'wpcasa-listings-map' ),
			'cb_label' 	=> __( 'Show', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Show a close button on infoboxes?', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_infobox_close',
			'position'	=> 170,
			'type'     	=> 'checkbox',
			'default'	=> '1'
		);

		return array_merge( $options, $options_maps );
	}
	
	/**
	 *	meta_box()
	 *	
	 *	Add exclude option to location meta box
	 *	
	 *	@param	array	$fields	Registered meta box fields
	 *	@return	array	$fields
	 *	
	 *	@since 1.0.0
	 */
	public static function meta_box( $fields ) {
		
		$fields['exclude'] = array(
			'name'      => __( 'Listings Map', 'wpcasa-listings-map' ),
			'id'        => '_map_exclude',
			'type'      => 'checkbox',
			'desc'		=> __( 'Exclude from listings map', 'wpcasa-listings-map' ),
			'priority'  => 70
		);
		
		return $fields;
	
	}

}
