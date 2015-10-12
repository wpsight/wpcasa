<div class="wpsight-listing-section wpsight-listing-section-info">
	
	<?php do_action( 'wpsight_listing_archive_info_before' ); ?>

	<div class="wpsight-listing-info clearfix">
	    <div class="alignleft">
	        <?php wpsight_listing_price(); ?>
	    </div>
	    <div class="alignright">
	        <div class="wpsight-listing-status">
	        	<?php $listing_offer = wpsight_get_listing_offer( get_the_id(), false ); ?>
		    	<span class="badge badge-<?php echo esc_attr( $listing_offer ); ?>" style="background-color:<?php echo esc_attr( wpsight_get_offer_color( $listing_offer ) ); ?>"><?php wpsight_listing_offer(); ?></span>
		    </div>
	    </div>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_info_after' ); ?>

</div><!-- .wpsight-listing-section-info -->