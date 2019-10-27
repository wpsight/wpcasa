<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'WPSight_Addons' ) ) :

/**
 * WPSight_Addons Class
 */
class WPSight_Addons {

	/**
	 * Handles output of the reports page in admin.
	 */
	public function output() {

		if ( false === ( $addons = get_transient( 'wpsight_addons_html' ) ) ) {
			
			$raw_addons = wp_remote_get(
				WPSIGHT_SHOP_URL . '/downloads/category/add-ons/',
				array(
					'timeout'     => 10,
					'redirection' => 5,
					'sslverify'   => false
				)
			);

			if ( ! is_wp_error( $raw_addons ) ) {

				$raw_addons = wp_remote_retrieve_body( $raw_addons );

				// Get Products
				$dom = new DOMDocument();
				libxml_use_internal_errors(true);
				$dom->loadHTML( $raw_addons );

				$xpath  = new DOMXPath( $dom );
				$tags   = $xpath->query('//div[@class="portfolio-wrapper download-wrapper"]');
				
				foreach ( $tags as $tag ) {					
					$addons = $tag->ownerDocument->saveXML( $tag );
					break;
				}

				$addons = wp_kses_post( $addons );

				if ( $addons )
					set_transient( 'wpsight_addons_html', $addons, 60*60*24*7 ); // Cached for a week
			}
			
		} ?>

		<div class="wrap wpsight-addons">
			<h2><?php echo WPSIGHT_NAME . ' ' . __( 'Add-Ons', 'wpcasa' ); ?></h2>			
			<?php echo $addons; ?>			
		</div>
		<?php
	}
}

endif;

return new WPSight_Addons();
