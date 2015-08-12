<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php do_action( 'wpsight_listing_archive_description_before' ); ?>

	<div class="wpsight-listing-description" itemprop="description">
		<?php echo apply_filters( 'wpsight_listing_description', wpsight_format_content( get_the_content() ) ); ?>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_description_after' ); ?>

</div><!-- .wpsight-listing-section -->