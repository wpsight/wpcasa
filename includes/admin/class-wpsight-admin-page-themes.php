<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'WPSight_Themes' ) ) :

/**
 * WPSight_Themes Class
 */
class WPSight_Themes {

	/**
	 * Handles output of the reports page in admin.
	 */
	public function output() {

		if ( false === ( $themes = get_transient( 'wpsight_themes_html' ) ) ) {

			$raw_themes = wp_remote_get(
				WPSIGHT_SHOP_URL . '/downloads/category/themes/',
				array(
					'timeout'     => 10,
					'redirection' => 5,
					'sslverify'   => false
				)
			);

			if ( ! is_wp_error( $raw_themes ) ) {

				$raw_themes = wp_remote_retrieve_body( $raw_themes );

				// Get Products
				$dom = new DOMDocument();
				libxml_use_internal_errors(true);
				$dom->loadHTML( $raw_themes );

				$xpath  = new DOMXPath( $dom );
				$tags   = $xpath->query('//div[@class="portfolio-wrapper download-wrapper"]');
				
				foreach ( $tags as $tag ) {
					$themes = $tag->ownerDocument->saveXML( $tag );
					break;
				}

				$themes = wp_kses_post( $themes );

				if ( $themes )
					set_transient( 'wpsight_themes_html', $themes, 60*60*24*7 ); // Cached for a week
			}

		} ?>

		<div class="wrap wpsight-themes">
			<h2><?php echo WPSIGHT_NAME . ' ' . __( 'Themes', 'wpcasa' ); ?></h2>			
			<?php echo $themes; ?>
		</div>
		<?php
	}
}

endif;

return new WPSight_Themes();
