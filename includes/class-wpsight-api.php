<?php
/**
 * Secure WPSight API Handler.
 *
 * Hardened version of the original WPSight_API class.
 *
 * @package WPCasa
 * @since 1.0.0
 * @updated 1.4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPSight_API {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Ensure query var is registered early.
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

		// Register API request handler.
		add_action( 'parse_request', array( $this, 'api_requests' ), 0 );
	}

	/**
	 * Register custom query vars.
	 *
	 * @param array $vars List of public query vars.
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'wpsight-api';
		return $vars;
	}

	/**
	 * Securely handle API requests.
	 *
	 * @return void
	 */
	public function api_requests() {
		global $wp;

		// 1) Preferred getter.
		$raw = get_query_var( 'wpsight-api' );

		// 2) Fallback: directly from $wp->query_vars.
		if ( empty( $raw ) && isset( $wp->query_vars['wpsight-api'] ) ) {
			$raw = $wp->query_vars['wpsight-api'];
		}

		// 3) Last resort: direct $_GET.
		if ( empty( $raw ) && ! empty( $_GET['wpsight-api'] ) ) {
			$raw = sanitize_text_field( wp_unslash( $_GET['wpsight-api'] ) );
		}

		// No request found → exit early.
		if ( empty( $raw ) ) {
			return;
		}

		// Sanitize to a valid key.
		$api = sanitize_key( $raw );

		if ( empty( $api ) ) {
			return;
		}

		/**
		 * Build allow-list of allowed API endpoints.
		 *
		 * IMPORTANT: Replace or extend this list in your theme or plugin
		 * using the 'wpsight_api_allowed_endpoints' filter.
		 *
		 * Example:
		 *
		 * add_filter( 'wpsight_api_allowed_endpoints', function( $allowed ) {
		 *     $allowed['ping'] = array( 'class' => null );
		 *     return $allowed;
		 * } );
		 */
		$allowed = apply_filters(
			'wpsight_api_allowed_endpoints',
			array()
		);

		// If API is not allowed, block access.
		if ( ! isset( $allowed[ $api ] ) ) {
			wp_die(
				sprintf(
				/* translators: %s: API endpoint slug */
					esc_html__( 'Endpoint "%s" not allowed.', 'wpcasa' ),
					$api
				),
				esc_html__( 'Forbidden', 'wpcasa' ),
				array( 'response' => 403 )
			);
		}

		// Start output buffering.
		ob_start();

		// Optional: safe class instantiation if explicitly allowed.
		if ( ! empty( $allowed[ $api ]['class'] ) && class_exists( $allowed[ $api ]['class'] ) ) {
			new $allowed[ $api ]['class']();
		}

		/**
		 * Trigger API action hook.
		 *
		 * Example usage:
		 * add_action( 'wpsight_api_ping', function() {
		 *     echo 'pong';
		 * } );
		 */
		do_action( 'wpsight_api_' . $api );

		// In development mode, allow buffer output for easier testing.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			ob_end_flush();
		} else {
			ob_end_clean();
		}

		// Maintain old behaviour for backward compatibility.
		die( '1' );
	}
}

// Instantiate the class.
new WPSight_API();
