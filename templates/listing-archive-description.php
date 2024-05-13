<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php do_action( 'wpsight_listing_archive_description_before' ); ?>
	
	<?php if( wpsight_is_listing_not_available() ) : ?>
		<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available">
            <?php echo esc_html( apply_filters( 'wpsight_listing_text_not_available', __( 'This property is currently not available.', 'wpcasa' ) ) ); ?>
		</div>
	<?php endif; ?>

	<div class="wpsight-listing-description" itemprop="description">
		<?php if( has_excerpt() ) : ?>
			<?php echo wp_kses_post( apply_filters( 'wpsight_listing_description', wpsight_format_content( get_the_excerpt() ) ) );	?>
		<?php else : ?>	
			<?php echo wp_kses_post( apply_filters( 'wpsight_listing_description', wpsight_format_content( get_the_content() ) ) );	?>
		<?php endif; ?>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_description_after' ); ?>

</div><!-- .wpsight-listing-section -->