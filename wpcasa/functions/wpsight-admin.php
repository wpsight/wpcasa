<?php
/**
 *	WPSight admin functions
 *	
 *	@package WPSight \ Functions
 */

/**
 *	wpsight_options()
 *	
 *	Merge option tabs and
 *	return WPSight_Admin::options()
 *	
 *	@since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_options' ) ) {

	function wpsight_options() {
		return WPSight_Admin::options();
	}

}

/**
 *	wpsight_options_listings()
 *	
 *	Create theme options array
 *	Listings options
 *	
 *	@since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_options_listings' ) ) {

	function wpsight_options_listings() {
		return WPSight_Admin::options_listings();
	}

}

/**
 *	wpsight_licenses()
 *	
 *	Create licneses array
 *	
 *	@since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_licenses' ) ) {

	function wpsight_licenses() {
		return WPSight_Admin::licenses();
	}

}

/**
 *	wpsight_check_license()
 *	
 *	Check specific license
 *	
 *	@since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_check_license' ) ) {

	function wpsight_check_license( $id = '', $item = '' ) {
		return WPSight_Admin::check_license( $id, $item );
	}

}

/**
 *	wpsight_activate_license()
 *	
 *	Update specific license
 *	
 *	@since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_activate_license' ) ) {

	function wpsight_activate_license( $id = '', $item = '' ) {
		return WPSight_Admin::activate_license( $id, $item );
	}

}

/**
 *	wpsight_deactivate_license()
 *	
 *	Update specific license
 *	
 *	@since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_deactivate_license' ) ) {

	function wpsight_deactivate_license( $id = '', $item = '' ) {
		return WPSight_Admin::deactivate_license( $id, $item );
	}

}
