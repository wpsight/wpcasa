<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Admin_Map_UI_Admin class
 */
class WPSight_Admin_Map_UI_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add custom meta box field types
		add_filter( 'cmb2_render_map', array( $this, 'render_map' ), 10, 5 );
		add_filter( 'cmb2_sanitize_map', array( $this, 'sanitize_map' ), 10, 4 );

		// Add map and map args field to meta box
		add_filter( 'wpsight_meta_box_listing_location_fields', array( $this, 'location_map_fields' ) );
	}

    /**
     *	sanitize_map()
     *
     *	Sanitize individual map values
     *
     *	@param	array 	$override_value
     *	@param  array 	$value
     *	@param  string	$object_id
     *	@param  array	$field_args
     *	@return array
     *
     *	@since 1.0.0
     */
    public static function sanitize_map( $override_value, $value, $object_id, $field_args ) {
        if ( is_array( $value )) {
            $value = array_map( 'sanitize_text_field', $value );
        }

        //adapted admin map ui data to wpcasa map data
        if ( ! empty( $value['lat'] ) ) update_post_meta( $object_id, '_geolocation_lat', $value['lat'] );
        if ( ! empty( $value['long'] ) ) update_post_meta( $object_id, '_geolocation_long', $value['long'] );

        return $value;
    }

	/**
	 *	location_map_fields()
	 *
	 *	Register the location meta fields
	 *
	 *	@param	array  $fields
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@return array
	 *
	 *	@since 1.0.0
	 */
	public static function location_map_fields( $fields ) {

		// Set meta box fields

		$fields['location'] = array(
			'id'       => '_map_geolocation',
			'name'     => __( 'Location', 'wpcasa-admin-map-ui' ),
			'desc'     => false,
			'type'     => 'map',
			'priority' => 20
		);

		$fields['map_type'] = array(
			'id'   => '_map_type',
			'name' => __( 'Map Type', 'wpcasa-admin-map-ui' ),
			'desc' => false,
			'type' => 'radio',
			'options' => array(
				'ROADMAP'   => __( 'Roadmap', 'wpcasa-admin-map-ui' ),
				'SATELLITE' => __( 'Satellite', 'wpcasa-admin-map-ui' ),
				'HYBRID'    => __( 'Hybrid', 'wpcasa-admin-map-ui' ),
				'TERRAIN'   => __( 'Terrain', 'wpcasa-admin-map-ui' )
			),
			'default'  => 'ROADMAP',
			'class'    => 'map-type',
			'priority' => 22
		);

		$fields['map_zoom'] = array(
			'id'       => '_map_zoom',
			'name'     => __( 'Map Zoom', 'wpcasa-admin-map-ui' ),
			'desc'     => false,
			'type'     => 'select',
			'options'  => array_combine( range( 1, 20 ), range( 1, 20 ) ),
			'default'  => '14',
			'class'    => 'map-zoom',
			'priority' => 24
		);

		$fields['map_streetview'] = array(
			'id'  		=> '_map_no_streetview',
			'name'		=> __( 'Streetview', 'wpcasa-admin-map-ui' ),
			'type'		=> 'checkbox',
			'desc'		=> __( 'Disable Streetview for this listing', 'wpcasa-admin-map-ui' ),
			'class'     => 'map-streetview',
			'priority'  => 26
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_location_map_fields', $fields ) );

		return $fields;

	}

	/**
	 *	get_location_data()
	 *
	 *	Returns location data associated for a given listing
	 *
	 *	@param	int	$listing_id
	 *	@uses	get_the_ID()
	 *	@uses	get_post_meta()
	 *	@return array
	 *
	 *	@since 1.0.0
	 */
//	TODO: delete all commented lines till wpcasa 1.4
	public static function get_location_data( $listing_id = null ) {

		if ( empty( $listing_id ) ) {
			$listing_id = get_the_ID();
		}
		// Check if map location already exists
//		$geolocation = get_post_meta( $listing_id, '_map_geolocation', true );
//
//		if ( ! empty( $geolocation['lat'] ) ) {
//			return $geolocation;
//		}

//		$geolocated = get_post_meta( $listing_id, '_geolocated', true );

//		if ( ! empty( $geolocated ) ) {
			return array(
				'lat'  => get_post_meta( $listing_id, '_geolocation_lat', true ),
				'long' => get_post_meta( $listing_id, '_geolocation_long', true )
			);
//		}

//		$map_location = get_post_meta( $listing_id, '_map_location', true );

//		if ( ! empty( $map_location ) ) {
//			return array_combine( array( 'lat', 'long' ), (array) explode( ',', $map_location ) );
//		}
	}

	/**
	 *	enqueue_scripts()
	 *
	 *	Enqueues JS dependencies and passes map options to script
	 *
	 *	@uses	wp_enqueue_script()
	 *	@uses	get_the_ID()
	 *	@uses	self::get_location_data()
	 *	@uses	wp_localize_script()
	 *
	 *	@since 1.0.0
	 */
	public static function enqueue_scripts() {

		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		// Enqueue scripts

		$api_key = wpsight_get_option( 'google_maps_api_key' );
		$api_url = $api_key ? add_query_arg( array( 'libraries' => 'places', 'key' => $api_key ), '//maps.googleapis.com/maps/api/js' ) : add_query_arg( array( 'libraries' => 'places' ), '//maps.googleapis.com/maps/api/js' );

		wp_enqueue_script( 'cmb-google-maps', apply_filters( 'wpsight_admin_map_ui_google_maps_endpoint', $api_url, $api_key ), null, WPSIGHT_VERSION );
		wp_enqueue_script( 'cmb-google-maps-script', WPSIGHT_ADMIN_MAP_UI_PLUGIN_URL . '/assets/js/map.js', array( 'jquery', 'cmb-google-maps', 'cmb2-scripts' ) );

		// Get map listing options

		$listing_id  = get_the_ID();

		$map_options = array(
		    '_map_type' 			=> get_post_meta( $listing_id, '_map_type', true ) ? get_post_meta( $listing_id, '_map_type', true ) : 'ROADMAP',
		    '_map_zoom' 			=> get_post_meta( $listing_id, '_map_zoom', true ) ? get_post_meta( $listing_id, '_map_zoom', true ) : 14,
		    '_map_no_streetview' 	=> get_post_meta( $listing_id, '_map_no_streetview', true ) ? get_post_meta( $listing_id, '_map_no_streetview', true ) : 'false'
		);

		$geolocation = self::get_location_data( $listing_id );

		wp_localize_script( 'cmb-google-maps-script', 'CMBGmaps',
			apply_filters( 'wpsight_admin_map_ui_map_args', wp_parse_args( $map_options, array(
				'_map_no_streetview' => 'false',
				'_map_type'          => 'ROADMAP',
				'_map_zoom'          => 14,
				'control_nav'        => 'true',
				'control_type'       => 'true',
				'latitude'           => isset( $geolocation['lat'] ) ? $geolocation['lat'] : '36.510071',
				'longitude'          => isset( $geolocation['long'] ) ? $geolocation['long'] : '-4.882447400000046',
				'markerTitle'        => __( 'Drag to set the exact location', 'wpcasa-admin-map-ui' ),
				'scrollwheel'        => 'false'
			)
		) ) );

	}

	/**
	 * 	render_map()
	 *
	 * 	Displays the map UI in the meta boxes of the listing
	 *
	 * 	@param	array	$field
	 * 	@param  array	$value
	 * 	@param  int		$object_id
	 * 	@param  string	$object_type
	 * 	@param  array	$field_type
	 * 	@uses	self::enqueue_scripts()
	 * 	@uses	wp_parse_args()
	 * 	@uses	$field_type->input()
	 * 	@uses	$field_type->_name()
	 *
	 * 	@since 1.0.0
	 */
	public static function render_map( $field, $value, $object_id, $object_type, $field_type ) {

		self::enqueue_scripts();

		// Ensure all args used are set
		$value = wp_parse_args( $value, array( 'lat' => null, 'long' => null, 'elevation' => null ) ); ?>

		<div class="map" style="width: 100%; height: 400px; border: 1px solid #eee; margin-top: 8px;"></div>

		<?php echo $field_type->input( array(
				'name'  => $field_type->_name( '[lat]' ),
				'value' => $value['lat'],
				'type'  => 'hidden',
				'class' => '_map_geolocation_lat',
				'id'    => '_map_geolocation_lat'
			) ); ?>

        <?php echo $field_type->input( array(
				'name'  => $field_type->_name( '[long]' ),
				'value' => $value['long'],
				'type'  => 'hidden',
				'class' => '_map_geolocation_long',
				'id'    => '_map_geolocation_long'
			) ); ?>

        <?php echo $field_type->input( array(
				'name'  => $field_type->_name( '[elevation]' ),
				'value' => $value['elevation'],
				'type'  => 'hidden',
				'class' => '_map_geolocation_elevation',
				'id'    => '_map_geolocation_elevation'
			) ); ?>

		<?php
	}
}
