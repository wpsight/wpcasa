<?php
/**
 * Template: Single Listing Info
 */
global $listing;

// Get listing offer key
$listing_offer = wpsight_get_listing_offer( $listing->ID, false ); ?>

<div class="wpsight-listing-section wpsight-listing-section-info">
	
	<?php do_action( 'wpsight_listing_single_info_before', $listing->ID ); ?>

	<div class="wpsight-listing-info clearfix">
	
	    <div class="alignleft">
	        <?php wpsight_listing_price( $listing->ID ); ?>
	    </div>

	    <div class="alignright">	    
	    	<div class="wpsight-listing-id">
	    		<?php wpsight_listing_id( $listing->ID ); ?>
	    	</div>	    
	        <div class="wpsight-listing-status">
		    	<span class="badge badge-<?php echo esc_attr( $listing_offer ); ?>" style="background-color:<?php echo esc_attr( wpsight_get_offer_color( $listing_offer ) ); ?>"><?php wpsight_listing_offer( $listing->ID ); ?></span>
		    </div>
	    </div>

	</div>
	
	<?php do_action( 'wpsight_listing_single_info_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-info -->