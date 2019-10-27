<?php
/**
 * Template: Single Listing Title
 */
global $listing; ?>

<div class="wpsight-listing-section wpsight-listing-section-title">
	
	<?php do_action( 'wpsight_listing_single_title_before', $listing->ID ); ?>

	<?php wpsight_listing_title( $listing->ID ); ?>
	
	<?php do_action( 'wpsight_listing_single_title_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-title -->