<?php
/**
 * The template for single listings.
 *
 * @package WPCasa
 */
get_header();  ?>

<?php if( ! wpsight_is_listing_expired() || wpsight_user_can_edit_listing( get_the_id() ) ) :
    //if elementor pro tempalate set, show it
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
        the_post(); // WARNING fix by added the_post here. Related to how wpcasa work
        the_content();
    endif;
else:
    get_template_part( 'template-parts/content-listing', 'single-expired' );
endif; ?>


<?php  get_footer(); ?>
