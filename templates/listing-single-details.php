<?php
/**
 * Template: Single Listing Details
 */
global $listing; ?>

<div class="wpsight-listing-section wpsight-listing-section-details">
	
	<?php do_action( 'wpsight_listing_single_details_before', $listing->ID ); ?>

	<?php wpsight_listing_details( $listing->ID ); ?>
	
	<?php do_action( 'wpsight_listing_single_details_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-details -->