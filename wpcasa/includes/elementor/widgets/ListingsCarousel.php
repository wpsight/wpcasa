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
class ListingsCarousel extends Widget_Base {

	public function get_name() {
		return 'wpsight_berlin_listings_carousel';
	}

	public function get_title() {
		return __( 'Listings Carousel', 'wpcasa-berlin' );
	}

    public function get_icon() {
		return 'eicon-carousel';
	}

	public function get_categories() {
		return [ 'general', 'theme' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'wpcasa-berlin' ),
			]
		);

//        $this->add_control(
//            'archive_location',
//            [
//                'label' => __( 'Location', 'wpcasa-berlin' ),
//                'type' => Controls_Manager::SWITCHER,
//                'label_off' => __( 'False', 'wpcasa-berlin' ),
//                'label_on' => __( 'True', 'wpcasa-berlin' ),
//                'default' => 'yes',
//            ]
//        );

        $this->add_control(
            'listing_type',
            [
                'label' => __( 'Listing type', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'false',
            ]
        );

        $this->add_control(
            'date',
            [
                'label' => __( 'Date', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'wpcasa-berlin' ),
                'label_on' => __( 'True', 'wpcasa-berlin' ),
                'default' => 'true',
            ]
        );

        $details_array = [];
        foreach ( array_keys(wpsight_details()) as $detail ) {
            $details_array[$detail] = wpsight_get_detail( $detail, 'label' );
        }

        $this->add_control(
            'show_elements',
            [
                'label' => __( 'Listing Details', 'wpcasa-berlin' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $details_array,
                'default' => [ 'numberOfRooms', 'details_4' ],
            ]
        );
		$this->end_controls_section();



        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'elementor' ),
            ]
        );

        $this->add_control(
            'nr',
            [
                'label' => __( 'NR', 'wpcasa-berlin' ),
                'type' => Controls_Manager::TEXT,
                'default' => '10',
            ]
        );

        foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {
            $terms = get_terms( array( $key ), array( 'hide_empty' => 0 ) );

            $terms_filtered = array_column($terms, 'name', 'slug');
//            add first el
            $terms_filtered = array_merge(["" => sprintf( __( 'All %s', 'wpcasa-berlin' ), esc_attr( $taxonomy->label ) )], $terms_filtered);

            $this->add_control(
                $taxonomy->name,
                [
                    'label' => $taxonomy->label,
                    'type' => Controls_Manager::SELECT,
                    'options' => $terms_filtered,
                ]
            );
        }

        $this->end_controls_section();



	}

	protected function render() {
        $settings = $this->get_active_settings();
        $taxonomy_filters	= array();

        foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {
            $taxonomy_filters[ $key ] = $settings[$key];
        }

        $listings_args = array(
            'nr'				=> $settings['nr'],
            'meta_query'		=> array(
                array(
                    'key'		=> '_thumbnail_id',
                    'compare'	=> 'EXISTS'
                )
            ),
            'show_paging'		=> false
        );

        $listings_args = array_merge( $listings_args, $taxonomy_filters );

        $listings = wpsight_get_listings( $listings_args );

//        if ( 'yes' === $settings['archive_location'] ) {
//            $settings['show_elements'][] = 'archive_location';
//        }

        if ( 'yes' === $settings['listing_type'] ) {
            $settings['show_elements'][] = 'listing_type';
        }

        if ( 'yes' === $settings['title'] ) {
            $settings['show_elements'][] = 'title';
        }

        if ( 'yes' === $settings['date'] ) {
            $settings['show_elements'][] = 'date';
        }
//       add filter to edit listing card details
        $show_elements = $settings['show_elements'];
        add_filter( 'card_info_filter', function() use ( $show_elements ) {
            return $show_elements;
        });

        wpsight_berlin_listings_carousel( $listings );

	}

	public function render_plain_content() {}
}


