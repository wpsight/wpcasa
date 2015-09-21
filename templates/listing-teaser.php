<div class="wpsight-listing-teaser entry-content" itemscope itemtype="http://schema.org/Product">

	<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
	
	<div itemprop="offers" class="clearfix" itemscope itemtype="http://schema.org/Offer">

		<?php do_action( 'wpsight_listing_teaser_before' ); ?>

		<div class="wpsight-listing-left">

			<meta itemprop="image" content="<?php echo esc_attr( wpsight_listing_thumbnail_url( $post->ID, 'large' ) ); ?>" />
				
			<div class="wpsight-listing-image">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php wpsight_listing_thumbnail( $post->ID, array( 75, 75 ) ); ?>
				</a>
			</div>

		</div>
		
		<div class="wpsight-listing-right">
				
			<div class="wpsight-listing-title clearfix">

				<div class="alignleft">				
					<h3 class="entry-title">
						<a href="<?php echo get_permalink( $post->ID ); ?>" rel="bookmark"><?php echo get_the_title( $post->ID ); ?></a>
					</h3>					
				</div>
				
				<div class="alignright">					
					<div class="wpsight-listing-status">
						<?php wpsight_listing_offer( $post->ID ); ?>
					</div>					    
				</div>
				    
			</div>
			
			<div class="wpsight-listing-info clearfix">
			    <div class="alignleft">
			    	<?php wpsight_listing_summary( $post->ID ); ?>
			    </div>
			    <div class="alignright">
			    	<?php wpsight_listing_price( $post->ID ); ?>
			    </div>
			</div>
		
		</div>

		<?php do_action( 'wpsight_listing_teaser_after' ); ?>

	</div>

</div><!-- .wpsight-listing -->