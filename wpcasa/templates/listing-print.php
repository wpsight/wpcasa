<?php
/**
 * This template shows a print-friendly
 * version of a single listing page
 *
 * @package WPSight
 * @since 1.0.0
 */

$listing_id = absint( $_GET['print'] );
$listing = get_post( $listing_id );

$listing_offer = wpsight_get_listing_offer( $listing->ID, false ); ?>
<!DOCTYPE html>
<html>
	
<head>
	<title><?php echo strip_tags( $listing->_listing_title ); ?></title>
	<?php do_action( 'wpsight_head_print' ); ?>
</head>

<body class="print<?php if( is_rtl() ) echo ' rtl'; ?>">

	<div class="actions clearfix">
	
		<div class="alignleft">
			<a href="<?php echo esc_url_raw( get_permalink( $listing->ID ) ); ?>" class="button back">&laquo; <?php _ex( 'Back to Listing', 'listing print', 'wpcasa' ); ?></a>
		</div>
		
		<div class="alignright">
			<a href="#" onclick="window.print();return false" class="button printnow"><?php _ex( 'Print Now', 'listing print', 'wpcasa' ); ?></a>
		</div>
	
	</div><!-- .actions -->

	<page size="A4">
	
		<div class="wrap">
	
			<div class="listing-print-title">			
				<h1><?php echo get_the_title( $listing ); ?></h1>			
			</div><!-- .listing-print-title -->
			
			<div class="listing-print-info clearfix">	
			    <div class="alignleft">
			        <?php wpsight_listing_price( $listing->ID ); ?>
			    </div>			
			    <div class="alignright">			    	
			    	<?php wpsight_listing_id( $listing->ID ); ?> - <?php wpsight_listing_offer( $listing->ID ); ?>
			    </div>			
			</div><!-- .listing-print-info -->
			
			<div class="listing-print-image">			
				<?php wpsight_listing_thumbnail( $listing->ID, 'full' ); ?>			
			</div><!-- .listing-print-image -->
			
			<div class="listing-print-details">			
				<?php wpsight_listing_details( $listing->ID ); ?>			
			</div><!-- .listing-print-details -->
			
			<div class="listing-print-description">			
				<?php if( wpsight_is_listing_not_available() ) : ?>
					<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available">
						<?php _e( 'This property is currently not available.', 'wpcasa' ); ?>
					</div>
				<?php endif; ?>				
				<div class="wpsight-listing-description" itemprop="description">
					<?php echo apply_filters( 'wpsight_listing_description', wpsight_format_content( $listing->post_content ) ); ?>
				</div>			
			</div><!-- .listing-print-description -->
			
			<div class="listing-print-features">			
				<?php wpsight_listing_terms( 'feature', $listing->ID, ', ' ); ?>			
			</div><!-- .listing-print-features -->
			
			<div class="listing-print-agent clearfix">			
				<div class="alignleft">
					<?php wpsight_listing_agent_image( $listing->ID, array( 50, 50 ) ); ?>
			        <?php wpsight_listing_agent_name( $listing->ID ); ?>
					<?php if( wpsight_get_listing_agent_company( $listing->ID ) ) : ?>
					<span class="wpsight-listing-agent-company">(<?php wpsight_listing_agent_company( $listing->ID ); ?>)</span>
					<?php endif; ?>
					<?php if( wpsight_get_listing_agent_phone( $listing->ID ) ) : ?>
					<br /><strong><?php wpsight_listing_agent_phone( $listing->ID ); ?></strong>
					<?php endif; ?>			        
			    </div>			
			    <div class="alignright">			    	
			    	<img src="<?php echo esc_url_raw( 'http://chart.apis.google.com/chart?cht=qr&chs=100x100&chld=H|0&chl=' . urlencode( get_permalink( $listing->ID ) ) ); ?>" width="100" height="100" alt="" />
			    </div>			
			</div><!-- .listing-print-agent -->
		
		</div><!-- .wrap -->
	
	</page>

</body>
</html>