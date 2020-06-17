<?php


class CMB2_Field_Leaflet_Map {
    /**
     * @var string tilelayer
     */

    public function __construct() {
        add_filter( 'cmb2_render_leaflet_map', [ $this, 'render_leaflet_map' ], 10, 5 );
        add_filter( 'cmb2_sanitize_leaflet_map', [ $this, 'sanitize_leaflet_map' ], 10, 4 );

        // Add map and map args field to meta box
        add_filter( 'wpsight_meta_box_listing_location_fields', array( $this, 'location_map_fields' ) );
    }

    /**
     * Render the field
     *
     * @param $field
     * @param $field_escaped_value
     * @param $object_id
     * @param $object_type
     * @param $field_type_object
     */
    public function render_leaflet_map( CMB2_Field $field, $field_escaped_value, $object_id, $object_type, CMB2_Types $field_type_object ) {

        $this->enqueue_scripts();
        $this->localize_script( $field->args( 'attributes' ) );

        $field_type_object->type = new CMB2_Type_Text( $field_type_object );


        echo '<div id="geocode-selector"></div>';
        echo "<div class='cmb2-leaflet__container'></div>";

        $this->render_input( 'lat', $field, $field_escaped_value, $field_type_object );
        $this->render_input( 'lng', $field, $field_escaped_value, $field_type_object );

        $field_type_object->_desc( true, true );

    }

    /**
     * Sanitize values
     */
    public function sanitize_leaflet_map( $override_value, $value, $object_id, $field_args ) {
        //adapted admin map ui data to wpcasa map data
        if ( ! empty( $value['lat'] ) ) update_post_meta( $object_id, '_geolocation_lat', $value['lat'] );
        if ( ! empty( $value['lng'] ) ) update_post_meta( $object_id, '_geolocation_long', $value['lng'] );

        return $value;
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'cmb2-leaflet-core' );
        wp_enqueue_script( 'cmb2-leaflet-core' );

        wp_enqueue_style( 'cmb2-leaflet-main',  WPSIGHT_PLUGIN_URL . '/includes/admin/maps/leaflet/assets/css/style.css', [  ], WPSIGHT_VERSION );
        wp_enqueue_script( 'cmb2-leaflet-geocoder_esri_main_js', 'https://unpkg.com/esri-leaflet', [], WPSIGHT_VERSION );
        wp_enqueue_script( 'cmb2-leaflet-geocoder_esri_js', 'https://unpkg.com/esri-leaflet-geocoder', [], WPSIGHT_VERSION );
        wp_enqueue_script( 'cmb2-leaflet-bootstrap-geocoder', WPSIGHT_PLUGIN_URL . '/includes/admin/maps/leaflet/assets/js/bootstrap-geocoder.js', [  ], WPSIGHT_VERSION );
        wp_enqueue_script( 'cmb2-leaflet-main', WPSIGHT_PLUGIN_URL . '/includes/admin/maps/leaflet/assets/js/main.js', [ 'cmb2-leaflet-bootstrap-geocoder'], WPSIGHT_VERSION );
    }

    /**
     * @param $args
     *
     * @return bool
     */
    protected function localize_script( $args ) {
        return wp_localize_script( 'cmb2-leaflet-main', 'CMB2LM', wp_parse_args( $args, [
            'tilelayer'           => get_post_meta( get_the_ID(), '_map_type', true ),
            'search'              => __( 'Search...', 'cmb2-leaflet-map' ),
            'not_found'           => __( 'Not found', 'cmb2-leaflet-map' ),
            'initial_coordinates' => [
                'lat' => get_post_meta( get_the_ID(), '_geolocation_lat', true ),
                'lng' => get_post_meta( get_the_ID(), '_geolocation_long', true )
            ],
            '_map_zoom'        => get_post_meta( get_the_ID(), '_map_zoom', true ) ? get_post_meta( get_the_ID(), '_map_zoom', true ) : 14,
        ] ) );
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
            'type'     => 'leaflet_map',
            'priority' => 20
        );

        // Apply filter and order fields by priority
        $fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_location_map_fields', $fields ) );

        return $fields;
    }

    /**
     * @param string     $field_name
     * @param CMB2_Field $field
     * @param            $field_escaped_value
     * @param CMB2_Types $field_type_object
     *
     * @internal param array $args
     */
    protected function render_input( $field_name = '', CMB2_Field $field, $field_escaped_value, CMB2_Types $field_type_object ) {
        $attrs = $field_type_object->concat_attrs( [
            'id'    => "{$field->args( 'id' )}_{$field_name}",
            'type'  => 'hidden',
            'name'  => "{$field->args( '_name' )}[{$field_name}]",
            'value' => isset( $field_escaped_value[ $field_name ] ) ? $field_escaped_value[ $field_name ] : '',
            'class' => "leaflet-map__{$field_name}",
            'desc'  => ''
        ], [ 'attributes' ] );

        echo sprintf( '<input%s />', $attrs );
    }
}

new CMB2_Field_Leaflet_Map();

