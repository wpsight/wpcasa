<?php
/**
 * Template: Single Listing Image
 */
global $listing; ?>

<?php if( has_post_thumbnail( $listing->ID ) ) : ?>

	<meta itemprop="image" content="<?php echo esc_attr( wpsight_listing_thumbnail_url( $listing->ID, 'large' ) ); ?>" />

	<div class="wpsight-listing-section wpsight-listing-section-image">
		
		<?php do_action( 'wpsight_listing_single_image_before' ); ?>
	
		<div class="wpsight-listing-image">
			<?php wpsight_listing_thumbnail( $listing->ID, 'large' ); ?>
		</div>
		
		<?php do_action( 'wpsight_listing_single_image_after' ); ?>
	
	</div><!-- .wpsight-listing-section -->

<?php endif; ?>