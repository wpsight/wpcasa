<?php
/**
 *	WPSight general functions
 *	
 *	@package WPSight \ Functions
 */
 
/**
 *	wpsight_details()
 *	
 *	Function that defines the array
 *	of standard listing details (beds, baths etc.)
 *	
 *	@uses	WPSight_General::details()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_details() {
    return WPSight_General::details();
}

/**
 *	wpsight_get_detail()
 *	
 *	Get specific listing detail.
 *	
 *	@param	string	$detail	Key of the detail to return
 *	@param	bool	$return	Only return specific element of detail array
 *	@uses	WPSight_General::get_detail()
 *	@return	array|string|bool
 *	
 *	@since 1.0.0
 */
function wpsight_get_detail( $detail, $return = false ) {
    return WPSight_General::get_detail( $detail, $return );
}

/**
 *	wpsight_get_detail_by_query_var()
 *	
 *	Get specific detail by it's query var key
 *	in the wpsight_details() array.
 *	
 *	@param	string	$query_var	query_var key
 *	@uses	WPSight_General::get_detail()
 *	@return	string	Key of the detail array element
 *	
 *	@since 1.0.0
 */
function wpsight_get_detail_by_query_var( $query_var ) {
    return WPSight_General::get_detail( $query_var );
}

/**
 *	wpsight_get_query_var_by_detail()
 *	
 *	Get specific detail by it's query var key
 *	in the wpsight_details() array.
 *	
 *	@param	string	$query_var	query_var key
 *	@uses	WPSight_General::get_query_var_by_detail()
 *	@return	string	Key of the detail array element
 *	
 *	@since 1.0.0
 */
function wpsight_get_query_var_by_detail( $detail ) {
    return WPSight_General::get_query_var_by_detail( $detail );
}

/**
 *	wpsight_offers()
 *	
 *	Function that defines the array
 *	of available listing offers (sale, rent etc.)
 *	
 *	@uses	WPSight_General::offers()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_offers() {
    return WPSight_General::offers();
}

/**
 *	wpsight_get_offer()
 *	
 *	Get specific offer.
 *	
 *	@param	string		$offer	Key of the offer to return
 *	@uses	WPSight_General::get_offer()
 *	@return	string|bool	Label of the offer or false if offer does not exist
 *	
 *	@since 1.0.0
 */
function wpsight_get_offer( $offer ) {
    return WPSight_General::get_offer( $offer );
}

/**
 *	wpsight_offer()
 *	
 *	Echo wpsight_get_offer()
 *	
 *	@param	string	$offer	Key of the offer to return
 *	@uses	wpsight_get_offer()
 *	
 *	@since 1.0.0
 */
function wpsight_offer( $offer ) {
	echo wpsight_get_offer( $offer );
}

/**
 *	wpsight_offer_color()
 *	
 *	Echo wpsight_get_offer_color()
 *	
 *	@param	string	$offer	Key of the offer to return
 *	@uses	wpsight_get_offer_color()
 *	
 *	@since 1.0.0
 */
function wpsight_offer_color( $offer ) {
	echo wpsight_get_offer_color( $offer );
}

/**
 *	wpsight_get_offer_color()
 *	
 *	Get specific offer color used for labels etc.
 *	
 *	@param	string		$offer	Key of the offer to return
 *	@uses	WPSight_General::get_offer_color()
 *	@return	string|bool	Color of the offer or false if offer does not exist
 *	
 *	@since 1.0.0
 */
function wpsight_get_offer_color( $offer ) {
	return WPSight_General::get_offer_color( $offer );
}

/**
 *	wpsight_rental_periods()
 *	
 *	Function that defines the array
 *	of available rental periods (monthly etc.)
 *	
 *	@uses	WPSight_General::rental_periods()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_rental_periods() {
	return WPSight_General::rental_periods();
}

/**
 *	wpsight_get_rental_period()
 *	
 *	Get specific rental period.
 *	
 *	@param	string		$period	Key of the period to return
 *	@uses	WPSight_General::get_rental_period()
 *	@return	string|bool	Label of the period or false if period does not exist
 *	
 *	@since 1.0.0
 */
function wpsight_get_rental_period( $period ) {
    return WPSight_General::get_rental_period( $period );
}

/**
 *	wpsight_rental_period()
 *	
 *	Echo wpsight_get_rental_period()
 *	
 *	@param	string		$period	Key of the period to return
 *	@uses	wpsight_get_rental_period()
 *	
 *	@since 1.0.0
 */
function wpsight_rental_period( $period ) {
    echo wpsight_get_rental_period( $period );
}

/**
 *	wpsight_measurements()
 *	
 *	Function that defines the array
 *	of available measurement units (m2,  etc.)
 *	
 *	@uses	WPSight_General::measurements()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_measurements() {
    return WPSight_General::measurements();
}

/**
 *	wpsight_get_measurement()
 *	
 *	Get specific measurement unit.
 *	
 *	@param	string		$measurement	Key of the measurement to return
 *	@uses	WPSight_General::get_measurement()
 *	@return	string|bool	Label of the measurement or false if unit does not exist
 *	
 *	@since 1.0.0
 */
function wpsight_get_measurement( $measurement ) {
    return WPSight_General::get_measurement( $measurement );
}

/**
 *	wpsight_measurement()
 *	
 *	Echo wpsight_get_measurement()
 *	
 *	@param	string		$measurement	Key of the measurement to return
 *	@uses	wpsight_get_measurement()
 *	
 *	@since 1.0.0
 */
function wpsight_measurement( $measurement ) {
    echo wpsight_get_measurement( $measurement );
}

/**
 *	wpsight_date_formats()
 *	
 *	Function that defines the array
 *	of available date formats.
 *	
 *	@uses	return WPSight_General::date_formats()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_date_formats( $date_i18n = false ) {
    return WPSight_General::date_formats( $date_i18n );
}

/**
 *	wpsight_statuses()
 *	
 *	Function that defines the array
 *	of available post statuses
 *	
 *	@uses	WPSight_General::statuses()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_statuses() {
    return WPSight_General::statuses();
}

/**
 *	wpsight_get_status()
 *	
 *	Get specific listing status
 *	
 *	@param	string	$status	Key of the corresponding status
 *	@param	string	$field	Field of the status (default: label)
 *	@uses	WPSight_General::get_status()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_get_status( $status, $field = 'label' ) {
	return WPSight_General::get_status( $status, $field );
}

/**
 *	wpsight_currencies()
 *	
 *	Function that defines the array
 *	of available currencies (USD, EUR  etc.)
 *	
 *	@uses	WPSight_General::currencies()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_currencies() {
    return WPSight_General::currencies();
}

/**
 *	wpsight_get_currency_abbr()
 *	
 *	Get 3-letter currency abbreviation.
 *	
 *	@param	string	$currency	3-letter code of specific currency
 *	@uses	WPSight_General::get_currency_abbr()
 *	@return	string	3-letter currency code
 *	
 *	@since 1.0.0
 */
function wpsight_get_currency_abbr( $currency = '' ) {
	return WPSight_General::get_currency_abbr( $currency );
}

/**
 *	wpsight_get_currency()
 *	
 *	Get currency entity.
 *	
 *	@param	string	$currency	3-letter code of specific currency
 *	@uses	WPSight_General::get_currency()
 *	@return	string	Currency entity or 3-letter code
 *	
 *	@since 1.0.0
 */
function wpsight_get_currency( $currency = '' ) {
    return WPSight_General::get_currency( $currency );
}

/**
 *	wpsight_currency()
 *	
 *	Echo wpsight_get_currency().
 *	
 *	@param	string	$currency	3-letter code of specific currency
 *	@uses	wpsight_get_currency()
 *	@return	string	Currency entity or 3-letter code
 *	
 *	@since 1.0.0
 */
function wpsight_currency( $currency = '' ) {
	echo wpsight_get_currency( $currency );
}

/**
 *	wpsight_spaces()
 *	
 *	Function that defines the array
 *	of available widget spaces.
 *	
 *	@uses	WPSight_General::spaces()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_spaces() {
    return WPSight_General::spaces();
}

/**
 *	listing_query_vars() 
 *	
 *	Return all custom query vars for listings
 *	
 *	@uses	WPSight_General::listing_query_vars()
 *	@return	array
 *	
 *	@since 1.0.0
 */
function wpsight_listing_query_vars() {
	return WPSight_General::listing_query_vars();
}
