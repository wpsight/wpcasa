<?php
/**
 *	WPSight meta box functions
 *	
 *	@package WPSight \ Functions
 */

/**
 *	wpsight_meta_boxes()
 *	
 *	Merging arrays of all WPSight meta boxes
 *	
 *	@uses	WPSight_Meta_Boxes::meta_boxes()
 *	@return	array	Array of all listing meta boxes
 *	
 *	@since 1.0.0
 */
function wpsight_meta_boxes() {
	return WPSight_Meta_Boxes::meta_boxes();
}

/**
 *	wpsight_meta_box_listing_attributes()
 *	
 *	Create listing attributes meta box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_listing_attributes()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_listing_attributes() {
	return WPSight_Meta_Boxes::meta_box_listing_attributes();
}

/**
 *	wpsight_meta_box_listing_images()
 *	
 *	Create listing images meta box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_listing_images()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_listing_images() {
	return WPSight_Meta_Boxes::meta_box_listing_images();
}

/**
 *	wpsight_meta_box_listing_price()
 *	
 *	Create listing price meta box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_listing_price()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_listing_price() {
	return WPSight_Meta_Boxes::meta_box_listing_price();
}

/**
 *	wpsight_meta_box_listing_details()
 *	
 *	Create listing details meta box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_listing_details()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_listing_details() {
	return WPSight_Meta_Boxes::meta_box_listing_details();
}

/**
 *	wpsight_meta_box_listing_location()
 *	
 *	Create listing location meta box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_listing_location()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_listing_location() {
    return WPSight_Meta_Boxes::meta_box_listing_location();
}

/**
 *	wpsight_meta_box_user()
 *	
 *	Create listing agent box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_user_agent()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_user() {
	_deprecated_function( __FUNCTION__, '1.0.6', 'wpsight_meta_box_user_agent()' );
    return WPSight_Meta_Boxes::meta_box_user_agent();
}

/**
 *	wpsight_meta_box_user_agent()
 *	
 *	Create listing agent box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_user_agent()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.6
 */
function wpsight_meta_box_user_agent() {
    return WPSight_Meta_Boxes::meta_box_user_agent();
}

/**
 *	wpsight_meta_box_listing_agent()
 *	
 *	Create listing agent box
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_listing_agent()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_listing_agent() {
    return WPSight_Meta_Boxes::meta_box_listing_agent();
}

/**
 *	wpsight_meta_box_spaces()
 *	
 *	Create listing spaces box(es)
 *	
 *	@uses	WPSight_Meta_Boxes::meta_box_spaces()
 *	@return	array	$meta_box	Meta box array with fields
 *	@see	wpsight_meta_boxes()
 *	@see	/functions/wpsight-general.php => L768
 *	
 *	@since 1.0.0
 */
function wpsight_meta_box_spaces() {
    return WPSight_Meta_Boxes::meta_box_spaces();
}
