<?php
/**
 * Template: After Listings Archive
 */
global $wpsight_query; ?>

</div><!-- .wpsight-listings -->

<?php if( $show_paging ) wpsight_pagination( $wpsight_query->max_num_pages ); ?>

<?php do_action( 'wpsight_listings_after', $wpsight_query ); ?>