<meta itemprop="image" content="<?php echo esc_attr( wpsight_get_listing_thumbnail_url( get_the_id(), 'wpsight-large' ) ); ?>" />

<div class="wpsight-listing-section wpsight-listing-section-image">
	
	<?php do_action( 'wpsight_listing_archive_image_before' ); ?>

	<div class="wpsight-listing-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php wpsight_listing_thumbnail(); ?>
		</a>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_image_after' ); ?>

</div><!-- .wpsight-listing-section -->