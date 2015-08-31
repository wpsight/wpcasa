<?php
/**
 * wpSight contact functions
 *
 * @package WPSight \ Functions
 */
 
/**
 * wpsight_contact_fields()
 *
 * Return array of form fields
 * used in wpSight contact form.
 *
 * @return array
 * @since 1.0.0
 */
 
function wpsight_contact_fields() {
    return WPSight_Agents::contact_fields();
}

/**
 * wpsight_contact_labels()
 *
 * Return array of form field labels
 * used in wpSight contact form.
 *
 * @return array
 * @since 1.0.0
 */

function wpsight_contact_labels() {
    return WPSight_Agents::contact_labels();
}

/**
 * wpsight_contact_email()
 *
 * Return array with body and
 * subject of email message.
 *
 * @param array $get_post Contact form $_POST data
 * @param string $location Contact form location (e.g. listing)
 * @param array $fields Validated form field data
 * @return array
 * @since 1.0.0
 */

function wpsight_contact_email( $get_post = false, $location, $fields ) {
    return WPSight_Agents::contact_email();
}

/**
 * wpsight_contact_placeholders()
 *
 * Return array of placeholders that
 * will be replaced in the email message.
 *
 * @param array $fields Validated form field data
 * @return array
 * @since 1.0.0
 */

function wpsight_contact_placeholders( $fields ) {
    return WPSight_Agents::contact_placeholders();
}

/**
 * Set HTML content type for emails
 *
 * @return string
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_set_html_content_type' ) ) {

	function wpsight_set_html_content_type() {
		return 'text/html';
	}

}
