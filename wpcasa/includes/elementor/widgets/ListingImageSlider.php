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
class ListingImageSlider extends Widget_Base {

	public function get_name() {
		return 'wpsight_berlin_listing_image_slider';
	}

	public function get_title() {
		return __( 'Listing Image Slider', 'wpcasa-berlin' );
	}

    public function get_icon() {
		return 'eicon-slider-push';
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

		$this->end_controls_section();

	}

	protected function render() {
	    $listing_id = get_one_listing_id();

        if ($listing_id) {
            wpsight_berlin_image_slider($listing_id);
        } else {
            wpsight_get_template_part('listing', 'no');
        }
	}

	public function render_plain_content() {}
}
