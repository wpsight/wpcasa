<?php
/**
 * wpSight general functions
 *
 * @package WPSight
 * @subpackage Functions
 */
 
/**
 * wpsight_details()
 *
 * Function that defines the array
 * of standard listing details (beds, baths etc.)
 *
 * @return array
 * @uses WPSight_General::details()
 * @since 1.0.0
 */

function wpsight_details() {
    return WPSight_General::details();
}

/**
 * wpsight_get_detail()
 *
 * Get specific detail.
 *
 * @param string $detail Key of the detail to return
 * @param bool $return Only return specific element of detail array
 * @return array|string|bool
 * @uses WPSight_General::get_detail()
 * @since 1.0.0
 */

function wpsight_get_detail( $detail, $return = false ) {
    return WPSight_General::get_detail( $detail, $return );
}

/**
 * wpsight_get_detail_by_query_var()
 *
 * Get specific detail by it's query var key
 * in the wpsight_details() array.
 *
 * @param string $query_var query_var key
 * @return string Key of the detail array element
 * @uses WPSight_General::get_detail()
 * @since 1.0.0
 */

function wpsight_get_detail_by_query_var( $query_var ) {
    return WPSight_General::get_detail( $query_var );
}

/**
 * wpsight_get_query_var_by_detail()
 *
 * Get specific detail by it's query var key
 * in the wpsight_details() array.
 *
 * @param string $query_var query_var key
 * @return string Key of the detail array element
 * @uses WPSight_General::get_query_var_by_detail()
 * @since 1.0.0
 */

function wpsight_get_query_var_by_detail( $detail ) {
    return WPSight_General::get_query_var_by_detail( $detail );
}

/**
 * wpsight_offers()
 *
 * Function that defines the array
 * of available listing offers (sale, rent etc.)
 *
 * @return array
 * @uses WPSight_General::offers()
 * @since 1.0.0
 */

function wpsight_offers() {
    return WPSight_General::offers();
}

/**
 * wpsight_get_offer()
 *
 * Get specific offer.
 *
 * @param string $offer Key of the offer to return
 * @return string|bool Label of the offer or false if offer does not exist
 * @uses WPSight_General::get_offer()
 * @since 1.0.0
 */

function wpsight_get_offer( $offer ) {
    return WPSight_General::get_offer( $offer );
}

/**
 * wpsight_offer()
 *
 * Echo wpsight_get_offer()
 *
 * @param string $offer Key of the offer to return
 * @uses wpsight_get_offer()
 * @since 1.0.0
 */

function wpsight_offer( $offer ) {
	echo wpsight_get_offer( $offer );
}

/**
 * wpsight_offer_color()
 *
 * Echo wpsight_get_offer_color()
 *
 * @param string $offer Key of the offer to return
 * @uses wpsight_get_offer_color()
 * @since 1.0.0
 */

function wpsight_offer_color( $offer ) {
	echo wpsight_get_offer_color( $offer );
}

/**
 * wpsight_get_offer_color()
 *
 * Get specific offer color used for labels etc.
 *
 * @param string $offer Key of the offer to return
 * @return string|bool Color of the offer or false if offer does not exist
 * @uses WPSight_General::get_offer_color()
 * @since 1.0.0
 */

function wpsight_get_offer_color( $offer ) {
	return WPSight_General::get_offer_color( $offer );
}

/**
 * wpsight_rental_periods()
 *
 * Function that defines the array
 * of available rental periods (monthly etc.)
 *
 * @return array
 * @uses WPSight_General::rental_periods()
 * @since 1.0.0
 */

function wpsight_rental_periods() {
	return WPSight_General::rental_periods();
}

/**
 * wpsight_get_rental_period()
 *
 * Get specific rental period.
 *
 * @param string $period Key of the period to return
 * @return string|bool Label of the period or false if period does not exist
 * @uses WPSight_General::get_rental_period()
 * @since 1.0.0
 */

function wpsight_get_rental_period( $period ) {
    return WPSight_General::get_rental_period( $period );
}

/**
 * wpsight_measurements()
 *
 * Function that defines the array
 * of available measurement units (m2,  etc.)
 *
 * @return array
 * @uses WPSight_General::measurements()
 * @since 1.0.0
 */

function wpsight_measurements() {
    return WPSight_General::measurements();
}

/**
 * wpsight_get_measurement()
 *
 * Get specific measurement unit.
 *
 * @param string $measurement Key of the measurement to return
 * @return string|bool Label of the measurement or false if unit does not exist
 * @uses return WPSight_General::get_measurement()
 * @since 1.0.0
 */

function wpsight_get_measurement( $measurement ) {
    return WPSight_General::get_measurement( $measurement );
}

/**
 * wpsight_date_formats()
 *
 * Function that defines the array
 * of available date formats.
 *
 * @return array
 * @uses return WPSight_General::date_formats()
 *
 * @since 1.0.0
 */

function wpsight_date_formats( $date_i18n = false ) {
    return WPSight_General::date_formats( $date_i18n );
}

/**
 * wpsight_statuses()
 *
 * Function that defines the array
 * of available post statuses
 *
 * @return array
 * @uses WPSight_General::statuses()
 * @since 1.0.0
 */

function wpsight_statuses() {
    return WPSight_General::statuses();
}

/**
 * wpsight_get_status()
 *
 * Get specific listing status
 *
 * @param string $status Key of the corresponding status
 * @param string $field Field of the status (default: label)
 * @return array
 * @uses WPSight_General::get_status()
 * @since 1.0.0
 */

function wpsight_get_status( $status, $field = 'label' ) {
	return WPSight_General::get_status( $status, $field );
}

/**
 * wpsight_currencies()
 *
 * Function that defines the array
 * of available currencies (USD, EUR  etc.)
 *
 * @return array
 * @since 1.0.0
 * @uses WPSight_General::currencies()
 */

function wpsight_currencies() {
    return WPSight_General::currencies();
}

/**
 * wpsight_get_currency_abbr()
 *
 * Get 3-letter currency abbreviation.
 *
 * @param string $currency 3-letter code of specific currency
 * @return string 3-letter currency code
 * @uses WPSight_General::get_currency_abbr
 * @since 1.0.0
 */

function wpsight_get_currency_abbr( $currency = '' ) {
	return WPSight_General::get_currency_abbr( $currency );
}

/**
 * wpsight_get_currency()
 *
 * Get currency entity.
 *
 * @param string $currency 3-letter code of specific currency
 * @return string Currency entity or 3-letter code
 * @uses WPSight_General::get_currency()
 * @since 1.0.0
 */

function wpsight_get_currency( $currency = '' ) {
    return WPSight_General::get_currency( $currency );
}

/**
 * wpsight_currency()
 *
 * Echo wpsight_get_currency().
 *
 * @param string $currency 3-letter code of specific currency
 * @return string Currency entity or 3-letter code
 * @uses wpsight_get_currency()
 * @since 1.0.0
 */

function wpsight_currency( $currency = '' ) {
	echo wpsight_get_currency( $currency );
}

/**
 * wpsight_spaces()
 *
 * Function that defines the array
 * of available widget spaces.
 *
 * @return array
 * @uses WPSight_General::spaces
 * @since 1.0.0
 */
 
function wpsight_spaces() {
    return WPSight_General::spaces();
}

/**
 * listing_query_vars() 
 * 
 * Return all custom query vars for listings
 *
 * @return array
 * @uses  WPSight_General::listing_query_vars()
 * @since 1.0.0
 */
 
function wpsight_listing_query_vars() {
	return WPSight_General::listing_query_vars();
}

/**
 * wpsight_user_can_edit_listing_id()
 *
 * Check if current user can edit the listing ID.
 *
 * @return bool True if user is eligible, else false
 * @uses WPSight_General::user_can_edit_listing_id
 * @since 1.0.0
 */

function wpsight_user_can_edit_listing_id() {
	return WPSight_General::user_can_edit_listing_id();
}
