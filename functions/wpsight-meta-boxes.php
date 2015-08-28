<?php
/**
 * WPSight meta box functions
 *
 * @package WPSight
 * @subpackage Functions
 */

/**
 * wpsight_meta_boxes()
 *
 * Merging arrays of all WPSight meta boxes
 *
 * @uses wpsight_meta_box_listing_*()
 * @return array Array of all listing meta boxes
 *
 * @since 1.0.0
 */

function wpsight_meta_boxes() {
	return WPSight_Admin_Meta_Boxes::meta_boxes();
}

/**
 * wpsight_meta_box_listing_attributes()
 *
 * Create listing attributes meta box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_attributes() {
	return WPSight_Admin_Meta_Boxes::meta_box_listing_attributes();
}

/**
 * wpsight_meta_box_listing_images()
 *
 * Create listing images meta box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_images() {
	return WPSight_Admin_Meta_Boxes::meta_box_listing_images();
}

/**
 * wpsight_meta_box_listing_price()
 *
 * Create listing price meta box
 *
 * @uses wpsight_offers()
 * @uses wpsight_rental_periods()
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_price() {
	return WPSight_Admin_Meta_Boxes::meta_box_listing_price();
}

/**
 * wpsight_meta_box_listing_details()
 *
 * Create listing details meta box
 *
 * @uses wpsight_user_can_edit_listing_id()
 * @uses wpsight_measurements()
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_details() {
	return WPSight_Admin_Meta_Boxes::meta_box_listing_details();
}

/**
 * wpsight_meta_box_listing_location()
 *
 * Create listing location meta box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_location() {
    return WPSight_Admin_Meta_Boxes::meta_box_listing_location();
}

/**
 * wpsight_meta_box_listing_agent()
 *
 * Create listing agent box
 *
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 *
 * @since 1.0.0
 */

function wpsight_meta_box_listing_agent() {
    return WPSight_Admin_Meta_Boxes::meta_box_listing_agent();
}

/**
 * wpsight_meta_box_spaces()
 *
 * Create listing spaces box(es)
 *
 * @uses wpsight_spaces()
 * @uses wpsight_sort_array_by_priority()
 * @uses wpsight_post_type()
 *
 * @return array $meta_box Meta box array with fields
 * @see wpsight_meta_boxes()
 * @see /functions/wpsight-general.php => L768
 *
 * @since 1.0.0
 */

function wpsight_meta_box_spaces() {
    return WPSight_Admin_Meta_Boxes::meta_box_spaces();
}
