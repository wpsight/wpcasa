<?php

namespace WPSight\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use WPSight\Elementor\Widget_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All information about custom widgets can be found here
 * https://developers.elementor.com/creating-a-new-widget/
 */
class ListingFeatures extends Widget_Base {

	public function get_name() {
		return 'wpsight_berlin_listing_features';
	}

	public function get_title() {
		return __( 'Listing Features', 'wpcasa-berlin' );
	}

    public function get_icon() {
		return 'eicon-star';
	}

	public function get_categories() {
		return [ 'theme-elements-single', 'theme' ];
	}

	protected function _register_controls() {}

	protected function render() {
        global $listing;
        $listing_id = Widget_Manager::wpsight_get_elementor_global_listing_id();
        $listing = wpsight_get_listing($listing_id);

        if ($listing_id) {
            wpsight_get_template( 'listing-single-features.php');
        } else {
            wpsight_get_template_part('listing', 'no');
        }
	}

	public function render_plain_content() {}
}
