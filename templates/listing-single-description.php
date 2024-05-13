<?php if ( ! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Template: Single Listing Description
 */
global $listing; ?>

<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php do_action( 'wpsight_listing_single_description_before', $listing->ID ); ?>
	
	<?php if( wpsight_is_listing_not_available() ) : ?>
		<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available">
			<?php $not_available_text = apply_filters( 'wpsight_listing_text_not_available', __( 'This property is currently not available.', 'wpcasa' ) ); ?>
			<?php echo wp_kses( $not_available_text, wpsight_allowed_html_tags() ); ?>
		</div>
	<?php endif; ?>

	<div class="wpsight-listing-description" itemprop="description">
		<?php $listing_content = apply_filters( 'wpsight_listing_description', wpsight_format_content( $listing->post_content ) ); ?>
		<?php echo wp_kses( $listing_content, wpsight_allowed_html_tags() ); ?>
	</div>
	
	<?php do_action( 'wpsight_listing_single_description_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section -->