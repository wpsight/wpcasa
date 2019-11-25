<div class="wpsight-infobox" id="wpsight-infobox-<?php echo get_the_id() ?>">

	<div class="infobox-image">
		<a href="<?php the_permalink(); ?>">
			<?php wpsight_listing_thumbnail( get_the_id(), 'medium' ); ?>
		</a>
	</div><!-- .infobox-image -->
	
	<div class="infobox-header">
		<h3 class="infobox-title"><a class="infobox-title-link" href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
	</div>

	<div class="infobox-content">
	
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

		<?php wpsight_listing_summary(); ?>
	
	</div><!-- .infobox-content -->

	<div class="infobox-footer">
		<p><a href="<?php the_permalink(); ?>" class="button"><?php _e( 'View details', 'wpsight-listings-map' ) ?></a></p>
	</div>

</div>