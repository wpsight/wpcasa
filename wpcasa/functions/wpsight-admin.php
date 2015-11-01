<?php
/**
 * WPSight admin functions
 *
 * @package WPSight \ Functions
 */

/**
 * Merge option tabs and
 * return WPSight_Admin::options()
 *
 * @since 1.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_options' ) ) {

	function wpsight_options() {
		return WPSight_Admin::options();
	}

}

/**
 * Create theme options array
 * Listings options
 *
 * @since 0.8
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_options_listings' ) ) {

	function wpsight_options_listings() {
		return WPSight_Admin::options_listings();
	}

}