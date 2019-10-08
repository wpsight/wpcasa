<?php
namespace WPSight\Elementor;

use ElementorPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Widget_Manager
 *
 * @since 1.0.0
 */
class Widget_Manager {

    private $modules = [];

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var Widget_Manager single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     *
     * @return Widget_Manager an instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_widgets_files() {
        include_once( 'widgets/Listings.php' );
        include_once( 'widgets/ListingsSearch.php' );
        include_once( 'widgets/ListingsCarousel.php' );

        include_once( 'widgets/ListingDetails.php' );
        include_once( 'widgets/ListingFeatures.php' );
        include_once( 'widgets/ListingAgent.php' );
//        include_once( 'widgets/ListingMap.php' );
        include_once( 'widgets/ListingImageSlider.php' );
//        include_once( 'widgets/ListingSidebar.php' );
//        include_once( 'widgets/ListingImageGallery.php' );

    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets() {
        $this->include_widgets_files();

        // Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Listings() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingsSearch() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingsCarousel() );

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingDetails() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingFeatures() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingAgent() );
//        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingMap() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingImageSlider() );
//        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingSidebar() );
//        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ListingImageGallery() );
    }

    /**
     * Get listing id
     *
     * Get listing id for proper displaying all widgets in global elementor template
     *
     * @since 1.0.0
     * @access public
     */
    public static function wpsight_get_elementor_global_listing_id($offset = 0) {
        //  check for editing mod in elementor global templates
        if ( (\Elementor\Plugin::$instance->editor->is_edit_mode() ) && ( get_post_type( get_the_ID() ) != 'listing') ) {

            $listings_args = array(
                'posts_per_page' => 1,
                'offset' => $offset
            );

            $listings = wpsight_get_listings( $listings_args );
            $listing_id = wp_list_pluck( $listings->posts, 'ID' )[0];

            if ( !$listing_id && $offset <= 10 ) {
                $listing_id =  get_one_listing_id($offset + 1);
            }

            if ( !$listing_id ) {
                return false;
            }

        } else {
            $listing_id = get_the_ID();
        }

        return $listing_id;
    }

    /**
     * Add Widget category
     *
     * Adds new widget category to group theme-specific widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function add_widget_categories( $elements_manager ) {

        $elements_manager->add_category(
            'theme',
            [
                'title' => __( 'Theme', 'wpcasa-berlin' ),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     *  Widget_Manager class constructor
     *
     * Register action hooks and filters
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct() {

        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

        // Add widget categories
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories'] );

    }
}

Widget_Manager::instance();



