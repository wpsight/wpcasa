<?php

namespace WPSight_Berlin\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All information about custom widgets can be found here
 * https://developers.elementor.com/creating-a-new-widget/
 */
class ListingAgent extends Widget_Base {

    private function size_to_array() {
        $thumbs = [];
        foreach( get_intermediate_image_sizes() as $size ) {
            $proper_size = strip_tags($size);

            $thumbs[ $proper_size ] = $proper_size;
        }

        return $thumbs;
    }

	public function get_name() {
		return 'wpsight_berlin_listing_agent';
	}

	public function get_title() {
		return __( 'Listing Agent', 'wpcasa-berlin' );
	}

    public function get_icon() {
		return 'eicon-person';
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
            'display_image',
            [
                'label' => __( 'Display agent image', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __( 'Image Size:', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'post-thumbnail',
                'options' => $this->size_to_array(),
            ]
        );

        $this->add_control(
            'display_company',
            [
                'label' => __( 'Display agent company', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'display_phone',
            [
                'label' => __( 'Display agent phone', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'display_social',
            [
                'label' => __( 'Display social profiles', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

//        $this->add_control(
//            'display_archive',
//            [
//                'label' => __( 'Display agent archive link', 'wpcasa-berlin' ),
//                'type' => Controls_Manager::SWITCHER,
//                'label_off' => __( 'False', 'wpcasa-berlin' ),
//                'label_on' => __( 'True', 'wpcasa-berlin' ),
//                'default' => 'yes',
//                'return_value' => 'yes',
//            ]
//        );

		$this->end_controls_section();

	}

	protected function render() {
        $agent_args = [];
        $settings = $this->get_active_settings();
        $listing_id = get_one_listing_id();

        foreach ( $settings as $index => $item ) {
            $agent_args[$index] = $item;
        }

        if ($listing_id) {
            wpsight_get_template( 'listing-single-agent.php', array( 'widget_instance' => $agent_args, 'id' => $listing_id ) );
        } else {
            wpsight_get_template_part('listing', 'no');
        }
    }

	public function render_plain_content() {}
}
