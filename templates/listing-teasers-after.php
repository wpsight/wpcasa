<?php
/**
 * Template: After Listing Teasers
 */
global $wpsight_query; ?>

</div><!-- .wpsight-listing-teasers -->

<?php if( $show_paging ) wpsight_pagination( $wpsight_query->max_num_pages ); ?>

<?php do_action( 'wpsight_listing_teasers_after', $wpsight_query ); ?>