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
class ListingDetails extends Widget_Base {

	public function get_name() {
		return 'wpsight_berlin_listing_details';
	}

	public function get_title() {
		return __( 'Listing Details', 'wpcasa-berlin' );
	}

    public function get_icon() {
		return 'eicon-price-list';
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
            'details_design',
            [
                'label' => __( 'Design', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'wpsight-listing-details' => 'Table',
                    'wpsight-listing-details_block' => 'Block',
                ],
                'default' => 'wpsight-listing-details',

            ]
        );

        $details = array_keys( wpsight_details() );
        foreach ( $details as $detail ) {
            $label = wpsight_get_detail( $detail, 'label' );
            $this->add_control(
                $detail,
                [
                    'label' => $label,
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __( 'False', 'wpcasa-berlin' ),
                    'label_on' => __( 'True', 'wpcasa-berlin' ),
                    'default' => 'yes',
                ]
            );
        }

        $this->end_controls_section();
    }

	protected function render() {
        $details_args = [];
        $settings = $this->get_active_settings();
        $listing_id = Widget_Manager::wpsight_get_elementor_global_listing_id();

        foreach ($settings as $index => $item) {
            if ($item == 'yes') {
                $details_args[] = $index;
            }
        }

        echo '<div class="wpsight-listing-section wpsight-listing-section-details">';
        if ($listing_id) {
//            wpsight_listing_details($listing_id, $details_args, $settings['details_design']);
            wpsight_get_template( 'listing-single-details.php', array('details' => $details_args, 'formatted' => $settings['details_design'], 'id' => $listing_id) );
        } else {
            wpsight_get_template_part('listing', 'no');
        }

        echo '</div>';
    }
}
