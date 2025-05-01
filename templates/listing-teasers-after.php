<?php if ( ! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Template: After Listing Teasers
 */
global $wpsight_query; ?>

</div><!-- .wpsight-listing-teasers -->

<?php if( isset( $show_panel ) && true === $show_panel ) wpsight_pagination( $wpsight_query->max_num_pages ); ?>

<?php do_action( 'wpsight_listing_teasers_after', $wpsight_query ); ?>