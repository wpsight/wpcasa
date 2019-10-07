<?php

namespace WPSight_Berlin\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All information about custom widgets can be found here
 * https://developers.wpcasa-berlin.com/creating-a-new-widget/
 */
class ListingMap extends Widget_Base {

    private function get_map_zoom() {
        $zoom = [];

        for ( $i = 1; $i <= 20; $i++ ) {
            $zoom[$i] = $i;
        }

        return $zoom;
    }

	public function get_name() {
		return 'wpsight_berlin_listing_map';
	}

	public function get_title() {
		return __( 'Listing Map', 'wpcasa-berlin' );
	}

    public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return [ 'theme-elements-single', 'theme' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'wpcasa-berlin' ),
			]
		);

        $this->add_control(
            'map_height',
            [
                'label' => __( 'Map Height (px):', 'wpcasa-berlin' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '555', 'wpcasa-berlin' ),
            ]
        );

        $this->add_control(
            'map_type',
            [
                'label' => __( 'Map Type:', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'ROADMAP',
                'options' => [
                    'ROADMAP' => __( 'Roadmap', 'wpcasa-berlin' ),
                    'SATELLITE' => __( 'Satellite', 'wpcasa-berlin' ),
                    'HYBRID' => __( 'Hybrid', 'wpcasa-berlin' ),
                    'TERRAIN' => __( 'Terrain', 'wpcasa-berlin' ),
                ],
            ]
        );

        $this->add_control(
            'map_zoom',
            [
                'label' => __( 'Map Zoom:', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => '14',
                'options' => $this->get_map_zoom(),
            ]
        );

        $this->add_control(
            'map_no_streetview',
            [
                'label' => __( 'Disable streetview', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'map_control_type',
            [
                'label' => __( 'Enable type control', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'map_control_nav',
            [
                'label' => __( 'Enable nav control', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'map_scrollwheel',
            [
                'label' => __( 'Enable scrollwheel', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'no',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'display_note',
            [
                'label' => __( 'Display public note', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

		$this->end_controls_section();

	}

	protected function render() {
        $map_args = [];
        $settings = $this->get_active_settings();

        foreach ( $settings as $index => $item ) {
            $map_args[$index] = $item;
        }
        wpsight_get_template( 'listing-single-location.php', array( 'gallery_args' => $map_args ) );
    }

	public function render_plain_content() {}
}
