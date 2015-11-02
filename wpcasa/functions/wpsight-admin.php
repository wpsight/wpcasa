<?php
/**
 * WPSight admin functions
 *
 * @package WPSight \ Functions
 */

/**
 * wpsight_options()
 *
 * Merge option tabs and
 * return WPSight_Admin::options()
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_options' ) ) {

	function wpsight_options() {
		return WPSight_Admin::options();
	}

}

/**
 * wpsight_options_listings()
 *
 * Create theme options array
 * Listings options
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_options_listings' ) ) {

	function wpsight_options_listings() {
		return WPSight_Admin::options_listings();
	}

}