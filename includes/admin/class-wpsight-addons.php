<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'wpSight_Addons' ) ) :

/**
 * wpSight_Addons Class
 */
class WPSight_Addons {

	/**
	 * Handles output of the reports page in admin.
	 */
	public function output() {
		
		/**

		if ( false === ( $addons = get_transient( 'wpSight_Addons_html' ) ) ) {

			$raw_addons = wp_remote_get(
				'https://wpjobmanager.com/add-ons/',
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
				$tags   = $xpath->query('//ul[@class="products"]');
				foreach ( $tags as $tag ) {
					$addons = $tag->ownerDocument->saveXML( $tag );
					break;
				}

				$addons = wp_kses_post( utf8_decode( $addons ) );

				if ( $addons ) {
					set_transient( 'wpSight_Addons_html', $addons, 60*60*24*7 ); // Cached for a week
				}
			}
		}
		
		*/

		?>
		<div class="wrap wp_job_manager wpSight_Addons_wrap">
			<h2><?php echo WPSIGHT_NAME . ' ' . __( 'Addons', 'wpsight' ); ?></h2>
			<div id="notice" class="updated below-h2"><p>A notice here</p></div>
			<?php // echo $addons; ?>
			
			<p>Add-on here</p>
			
		</div>
		<?php
	}
}

endif;

return new WPSight_Addons();
