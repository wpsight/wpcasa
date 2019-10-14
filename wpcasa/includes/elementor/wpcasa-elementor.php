<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPCasa_Elementor_Init
{
    public function __construct()
    {
        define('WPCASA_ELEMENTOR_PLUGIN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));

        include_once WPCASA_ELEMENTOR_PLUGIN_DIR . '/widgets-manager.php';
        add_filter( 'template_include', array($this, 'wpsight_elementor_wpcasa_listing') );
        add_action( 'elementor/theme/register_locations', array($this, 'wpsight_geneva_register_elementor_locations' ));
    }

    /**
     *	wpsight_geneva_register_elementor_locations()
     *
     *	Register Elementor Locations
     *	https://developers.elementor.com/theme-locations-api/registering-locations/
     *
     *	@since	1.0.0
     */

    public function wpsight_geneva_register_elementor_locations($elementor_theme_manager) {
//        $elementor_theme_manager->register_all_core_location();

        $elementor_theme_manager->register_location( 'single' );
    }

     public function wpsight_is_built_with_elementor($post_id ) {
        return ! ! get_post_meta( $post_id, '_elementor_edit_mode', true );
    }

    public function wpsight_elementor_wpcasa_listing( $original_template ) {
        //disable default wpcasa output.
        // Both elementor and wpcasa do overload global post. Here we prevent the overloading by wpcasa
        add_filter('wpsight_listing_single_output', function() {
            return false;
        });

        if ( class_exists( '\ElementorPro\Plugin' ) ) {
            $conditions_manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();

            if ( is_singular( 'listing' ) && !empty($conditions_manager->get_documents_for_location('single')) )  {
                return plugin_dir_path(__FILE__) . 'single-listing.php';
            }
        }

        if ( $this->wpsight_is_built_with_elementor(get_the_ID()) ) { //check if elementor turned on this particular page
            return plugin_dir_path(__FILE__) . 'single-listing.php';
        }

        add_filter('wpsight_listing_single_output', function() {
            return true;
        });

        return $original_template;
    }
}

new WPCasa_Elementor_Init();

