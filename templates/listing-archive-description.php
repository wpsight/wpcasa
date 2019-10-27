<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php do_action( 'wpsight_listing_archive_description_before' ); ?>
	
	<?php if( wpsight_is_listing_not_available() ) : ?>
		<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available">
			<?php _e( 'This property is currently not available.', 'wpcasa' ); ?>
		</div>
	<?php endif; ?>

	<div class="wpsight-listing-description" itemprop="description">
		<?php if( has_excerpt() ) : ?>
			<?php echo apply_filters( 'wpsight_listing_description', wpsight_format_content( get_the_excerpt() ) ); ?>
		<?php else : ?>	
			<?php echo apply_filters( 'wpsight_listing_description', wpsight_format_content( get_the_content() ) ); ?>
		<?php endif; ?>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_description_after' ); ?>

</div><!-- .wpsight-listing-section -->