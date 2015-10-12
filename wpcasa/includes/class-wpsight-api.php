<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_API API
 *
 * This API class handles API requests.
 */
class WPSight_API {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_filter( 'query_vars', array( $this, 'add_query_vars'), 0 );
		add_action( 'parse_request', array( $this, 'api_requests'), 0 );
	}

	/**
	 * add_query_vars function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'wpsight-api';
		return $vars;
	}

	/**
	 * add_endpoint function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_endpoint() {
		add_rewrite_endpoint( 'wpsight-api', EP_ALL );
	}

	/**
	 * API request - Trigger any API requests (handy for third party plugins/gateways).
	 *
	 * @access public
	 * @return void
	 */
	public function api_requests() {
		global $wp;

		if ( ! empty( $_GET['wpsight-api'] ) )
			$wp->query_vars['wc-api'] = $_GET['wpsight-api'];

		if ( ! empty( $wp->query_vars['wc-api'] ) ) {
			// Buffer, we won't want any output here
			ob_start();

			// Get API trigger
			$api = strtolower( esc_attr( $wp->query_vars['wpsight-api'] ) );

			// Load class if exists
			if ( class_exists( $api ) )
				$api_class = new $api();

			// Trigger actions
			do_action( 'wpsight_api_' . $api );

			// Done, clear buffer and exit
			ob_end_clean();
			die('1');
		}
	}
}

new WPSight_API();
