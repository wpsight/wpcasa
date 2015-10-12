<?php
/**
 * Template: Single Listing Features
 */
global $listing; ?>

<?php if( wpsight_get_listing_terms( 'feature', $listing->ID ) ) : ?>

<div class="wpsight-listing-section wpsight-listing-section-features">
	
	<?php do_action( 'wpsight_listing_single_features_before', $listing->ID ); ?>

	<div class="wpsight-listing-features">		
		<?php wpsight_listing_terms( 'feature', $listing->ID, ', ' ); ?>		
	</div>
	
	<?php do_action( 'wpsight_listing_single_features_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section -->

<?php endif; ?>