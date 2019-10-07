<?php

namespace WPSight_Berlin\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 *  All information about custom widgets can be found here
 * //https://developers.elementor.com/creating-a-new-widget/
 */

class ListingImageGallery extends Widget_Base {

    private function size_to_array() {
        $thumbs = [];
        foreach( get_intermediate_image_sizes() as $size ) {
            $proper_size = strip_tags($size);

            $thumbs[ $proper_size ] = $proper_size;
        }

        return $thumbs;
    }

	public function get_name() {
		return 'wpsight_berlin_listing_image_gallery';
	}

	public function get_title() {
		return __( 'Listing Gallery', 'wpcasa-berlin' );
	}
//    TODO change icon
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'pro-elements', 'theme-elements' ];
	}

	protected function _register_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'wpcasa-berlin' ),
            ]
        );

        $this->add_control(
            'thumbs_columns',
            [
                'label' => __( 'Columns:', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1'  => __( '1 column', 'wpcasa-berlin' ),
                    '2'  => __( '2 columns', 'wpcasa-berlin' ),
                    '3'  => __( '3 columns', 'wpcasa-berlin' ),
                    '4'  => __( '4 columns', 'wpcasa-berlin' ),

                ],
            ]
        );

        $this->add_control(
            'thumbs_columns_small',
            [
                'label' => __( 'Columns (on small screens):', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1'  => __( '1 column', 'wpcasa-berlin' ),
                    '2'  => __( '2 columns', 'wpcasa-berlin' ),
                ],
            ]
        );

        $this->add_control(
            'thumbs_size',
            [
                'label' => __( 'OrderBy', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'wpsight-large',
                'options' => $this->size_to_array(),
            ]
        );

        $this->add_control(
            'thumbs_caption',
            [
                'label' => __( 'Display thumbnail captions (if any)', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'thumbs_link',
            [
                'label' => __( 'Link thumbnails to image file (in lightbox)', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_size',
            [
                'label' => __( 'Lightbox Size:', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'wpsight-full',
                'options' => $this->size_to_array(),
            ]
        );

        $this->add_control(
            'lightbox_mode',
            [
                'label' => __( 'Animation:', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => __( 'Fade', 'elementor' ),
                    'slide' => __( 'Slide', 'elementor' ),
                ],
            ]
        );

        $this->add_control(
            'lightbox_caption',
            [
                'label' => __( 'Show image descriptions in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_prev_next',
            [
                'label' => __( 'Show prev/next navigation in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_loop',
            [
                'label' => __( 'Loop lightbox images', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_download',
            [
                'label' => __( 'Show image download button', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'lightbox_zoom',
            [
                'label' => __( 'Allow zoom option in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_fullscreen',
            [
                'label' => __( 'Allow fullscreen toggle in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_counter',
            [
                'label' => __( 'Show image counter in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_autoplay',
            [
                'label' => __( 'Show autoplay controls in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lightbox_thumbs_size',
            [
                'label' => __( 'Show thumbnail previews in lightbox', 'wpcasa-berlin' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'False', 'elementor' ),
                'label_on' => __( 'True', 'elementor' ),
                'default' => 'yes',
            ]
        );


    }
	protected function render() {
        $gallery_args = [];
        $settings = $this->get_active_settings();

        foreach ( $settings as $index => $item ) {
            $gallery_args[$index] = $item;
        }

        wpsight_get_template( 'listing-single-gallery.php', array( 'gallery_args' => $gallery_args ) );

	}

	public function render_plain_content() {}
}
