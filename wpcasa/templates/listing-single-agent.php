<?php
/**
 * Template: Single Listing Agent
 */
global $listing; ?>

<div class="wpsight-listing-section wpsight-listing-section-agent" itemprop="seller" itemscope itemtype="http://schema.org/RealEstateAgent">
	
	<?php do_action( 'wpsight_listing_single_agent_before', $listing->ID ); ?>

	<div class="wpsight-listing-agent clearfix">
	
		<?php if( wpsight_get_listing_agent_image( $listing->ID ) ) : ?>
	
	    <div class="wpsight-listing-agent-image">

	        <span itemprop="image"><?php wpsight_listing_agent_image( $listing->ID ); ?></span>

	    </div><!-- .wpsight-listing-agent-image -->
	    
	    <?php endif; ?>
	    
	    <div class="wpsight-listing-agent-info">

	        <div class="wpsight-listing-agent-name" itemprop="name">

	        	<?php wpsight_listing_agent_name( $listing->ID ); ?>

	        	<?php if( wpsight_get_listing_agent_company( $listing->ID ) ) : ?>
	        	<span class="wpsight-listing-agent-company">(<?php wpsight_listing_agent_company( $listing->ID ); ?>)</span>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_listing_agent_phone( $listing->ID ) ) : ?>
	        	<span class="wpsight-listing-agent-phone"><?php wpsight_listing_agent_phone( $listing->ID ); ?></span>
	        	<?php endif; ?>

	        </div>
	        
	        <div class="wpsight-listing-agent-links">
	        
	        	<?php if( wpsight_get_listing_agent_website( $listing->ID ) ) : ?>
	        	<a href="<?php wpsight_listing_agent_website( $listing->ID ); ?>" class="agent-website" title="<?php echo esc_attr( wpsight_get_listing_agent_website( $listing->ID ) ); ?>" itemprop="url" target="_blank" rel="nofollow"><?php _e( 'Website', 'wpcasa' ); ?></a>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_listing_agent_twitter( $listing->ID ) ) : ?>
	        	<a href="<?php wpsight_listing_agent_twitter( $listing->ID, 'url' ); ?>" class="agent-twitter" title="@<?php echo esc_attr( wpsight_get_listing_agent_twitter( $listing->ID ) ); ?>" target="_blank" rel="nofollow"><?php _e( 'Twitter', 'wpcasa' ); ?></a>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_listing_agent_facebook( $listing->ID ) ) : ?>
	        	<a href="<?php wpsight_listing_agent_facebook( $listing->ID, 'url' ); ?>" class="agent-facebook" title="<?php echo esc_attr( wpsight_get_listing_agent_facebook( $listing->ID ) ); ?>" target="_blank" rel="nofollow"><?php _e( 'Facebook', 'wpcasa' ); ?></a>
	        	<?php endif; ?>

	        </div>

	        <div class="wpsight-listing-agent-description" itemprop="description">
	        	<?php wpsight_listing_agent_description( $listing->ID ); ?>
	        </div>
	        
	        <?php if( wpsight_get_listing_agent_archive( $listing->ID ) ) : ?>	        
	        <div class="wpsight-listing-agent-archive">
	        	<a href="<?php wpsight_listing_agent_archive( $listing->ID ); ?>"><?php _e( 'My Listings', 'wpcasa' ); ?></a>
	        </div>
	        <?php endif; ?>
	    
	    </div><!-- .wpsight-listing-agent-info -->
	    
	</div><!-- .wpsight-listing-agent -->
	
	<?php do_action( 'wpsight_listing_single_agent_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-agent -->