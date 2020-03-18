<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_Geocode
 *
 * Obtains Geolocation data for posted listings from Google.
 * This code was mainly taken from the plugin WP Job Manager
 * created by Mike Jolley (https://wpjobmanager.com) and 
 * has been adapted to be used in wpCasa.
 *
 * @author Mike Jolley
 */
//TODO: delete till wpcasa 1.5
class WPSight_Geocode {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Update listing data submitted through front end form
		add_action( 'wpsight_update_listing_data', array( $this, 'update_location_data' ), 20, 2 );

		// Update location data submitted through post meta
		add_action( 'wpsight_listing_location_edited', array( $this, 'change_location_data' ), 20, 2 );

	}

	/**
	 * update_location_data()
	 *
	 * Update location data submitted through
	 * front end listing submission form.
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @param array $values Form field values
	 * @uses self::get_location_data()
	 * @uses self::save_location_data()
	 *
	 * @since 1.0.0
	 */
	public function update_location_data( $listing_id, $values ) {

		if ( apply_filters( 'wpsight_geolocation_enabled', true ) && isset( $values['listing_location']['map_address'] ) ) {
			$address_data = self::get_location_data( $values['listing_location']['map_address'] );
			self::save_location_data( $listing_id, $address_data );
		}

	}

	/**
	 * change_location_data()
	 *
	 * Change listing location when post meta
	 * in listing editor changes.
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @param string $new_location Changed locaction
	 * @uses self::get_location_data()
	 * @uses self::clear_location_data()
	 * @uses self::save_location_data()
	 *
	 * @since 1.0.0
	 */
	public function change_location_data( $listing_id, $new_location ) {

		if ( apply_filters( 'wpsight_geolocation_enabled', true ) ) {
			$address_data = self::get_location_data( $new_location );
			self::clear_location_data( $listing_id );
			self::save_location_data( $listing_id, $address_data );			
		}

	}

	/**
	 * has_location_data()
	 *
	 * Checks if a listing has location data
	 * (post meta 'geolocated') or not.
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @uses get_post_meta()
	 * @return bool True if custom field has value, false if not
	 *
	 * @since 1.0.0
	 */
	public static function has_location_data( $listing_id ) {
		return get_post_meta( $listing_id, '_geolocated', true ) == 1;
	}

	/**
	 * generate_location_data()
	 *
	 * Called manually to generate location data and save to a post
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @param string $location The location in question
	 * @uses self::get_location_data()
	 * @uses self::save_location_data()
	 *
	 * @since 1.0.0
	 */
	public static function generate_location_data( $listing_id, $location ) {
		$address_data = self::get_location_data( $location );
		self::save_location_data( $listing_id, $address_data );
	}

	/**
	 * clear_location_data()
	 *
	 * Clear listing location data by
	 * deleting corresponding post meta.
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @uses delete_post_meta()
	 *
	 * @since 1.0.0
	 */
	public static function clear_location_data( $listing_id ) {

		delete_post_meta( $listing_id, '_geolocated' );
		delete_post_meta( $listing_id, '_geolocation_city' );
		delete_post_meta( $listing_id, '_geolocation_country_long' );
		delete_post_meta( $listing_id, '_geolocation_country_short' );
		delete_post_meta( $listing_id, '_geolocation_formatted_address' );
		delete_post_meta( $listing_id, '_geolocation_lat' );
		delete_post_meta( $listing_id, '_geolocation_long' );
		delete_post_meta( $listing_id, '_geolocation_state_long' );
		delete_post_meta( $listing_id, '_geolocation_state_short' );
		delete_post_meta( $listing_id, '_geolocation_street' );
		delete_post_meta( $listing_id, '_geolocation_zipcode' );
		delete_post_meta( $listing_id, '_geolocation_postcode' );

	}

	/**
	 * save_location_data()
	 *
	 * Save returned location data to post meta.
	 *
	 * @param integer $listing_id Post ID of the corresponding listing
	 * @param array $address_data Array of location data
	 * @uses update_post_meta()
	 *
	 * @since 1.0.0
	 */
	public static function save_location_data( $listing_id, $address_data ) {

		if ( ! is_wp_error( $address_data ) && $address_data ) {
			
			// Loop through location data fields

			foreach ( $address_data as $key => $value ) {				
				// Update post meta if it has a value				
				if ( $value )
					update_post_meta( $listing_id, '_geolocation_' . $key, $value );
			}

			// Set value 1 for 'geolocated' (true)
			update_post_meta( $listing_id, '_geolocated', 1 );

		}

	}

	/**
	 * get_location_data()
	 *
	 * Get Location Data from Google. Based on code by Eyal Fitoussi.
	 *
	 * @param string $raw_address
	 * @uses get_transient()
	 * @uses wp_remote_get()
	 * @uses wp_remote_retrieve_body()
	 * @uses json_decode()
	 * @return array location data
	 *
	 * @since 1.0.0
	 */
	public static function get_location_data( $raw_address ) {

		$invalid_chars = array( " " => "+", "," => "", "?" => "", "&" => "", "=" => "" , "#" => "" );
		$raw_address   = trim( strtolower( str_replace( array_keys( $invalid_chars ), array_values( $invalid_chars ), $raw_address ) ) );

		// Stop if no valid address

		if ( empty( $raw_address ) )
			return false;

		$transient_name              = 'geocode_' . md5( $raw_address );
		$geocoded_address            = get_transient( $transient_name );
		$jm_geocode_over_query_limit = get_transient( 'jm_geocode_over_query_limit' );
		
		$api_key = wpsight_get_option( 'google_maps_api_key' );

		// Query limit reached - don't geocode for a while

		if ( ! $api_key && $jm_geocode_over_query_limit && false === $geocoded_address )
			return false;

		try {
			if ( false === $geocoded_address || empty( $geocoded_address->results[0] ) ) {
				
				// Set geocoding API URL				
				$api_url = sprintf( 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&region=%s', $raw_address, apply_filters( 'wpsight_geolocation_region_cctld', '', $raw_address ) );
				
				// Optionally add API key
				$api_url_key = $api_key ? add_query_arg( array( 'key' => $api_key ), $api_url ) : $api_url;
				
				// Apply filter
				$api_url_key = apply_filters( 'wpsight_google_maps_geocoding_endpoint', $api_url_key, $api_url, $raw_address, $api_key );

				// Get result for raw address

				$result = wp_remote_get(
					apply_filters( 'wpsight_geolocation_endpoint', $api_url_key, $raw_address ),
					apply_filters( 'wpsight_geolocation_args', array(
						'timeout'     => 5,
					    'redirection' => 1,
					    'httpversion' => '1.1',
					    'user-agent'  => 'WordPress/' . WPSIGHT_NAME . '-' . WPSIGHT_VERSION . '; ' . get_bloginfo( 'url' ),
					    'sslverify'   => false
				    ) )
				);

				$result           = wp_remote_retrieve_body( $result );
				$geocoded_address = json_decode( $result );

				if ( $geocoded_address->status ) {

					switch ( $geocoded_address->status ) {

						case 'ZERO_RESULTS' :
							throw new Exception( __( "No results found", 'wpcasa' ) );
						break;

						case 'OVER_QUERY_LIMIT' :
							set_transient( 'jm_geocode_over_query_limit', 1, HOUR_IN_SECONDS );
							throw new Exception( __( "Query limit reached", 'wpcasa' ) );
						break;

						case 'OK' :
							if ( ! empty( $geocoded_address->results[0] ) ) {
								set_transient( $transient_name, $geocoded_address, 24 * HOUR_IN_SECONDS * 365 );
							} else {
								throw new Exception( __( "Geocoding error", 'wpcasa' ) );
							}
						break;

						default :
							throw new Exception( __( "Geocoding error", 'wpcasa' ) );
						break;

					}

				} else {

					throw new Exception( __( "Geocoding error", 'wpcasa' ) );

				}

			}
		
		} // end try()
		
		catch ( Exception $e ) {

			return new WP_Error( 'error', $e->getMessage() );

		} // end catch()

		$address                      = array();
		$address['lat']               = sanitize_text_field( $geocoded_address->results[0]->geometry->location->lat );
		$address['long']              = sanitize_text_field( $geocoded_address->results[0]->geometry->location->lng );
		$address['formatted_address'] = sanitize_text_field( $geocoded_address->results[0]->formatted_address );

		if ( ! empty( $geocoded_address->results[0]->address_components ) ) {

			$address_data             = $geocoded_address->results[0]->address_components;
			$street_number            = false;
			$address['street']        = false;
			$address['city']          = false;
			$address['state_short']   = false;
			$address['state_long']    = false;
			$address['zipcode']       = false;
			$address['country_short'] = false;
			$address['country_long']  = false;

			foreach ( $address_data as $data ) {

				switch ( $data->types[0] ) {

					case 'street_number' :
						$address['street']        = sanitize_text_field( $data->long_name );
					break;

					case 'route' :
						$route = sanitize_text_field( $data->long_name );

						if ( ! empty( $address['street'] ) )
							$address['street'] = $address['street'] . ' ' . $route;
						else
							$address['street'] = $route;
					break;

					case 'locality' :
						$address['city']          = sanitize_text_field( $data->long_name );
					break;

					case 'administrative_area_level_1' :
						$address['state_short']   = sanitize_text_field( $data->short_name );
						$address['state_long']    = sanitize_text_field( $data->long_name );
					break;

					case 'postal_code' :
						$address['postcode']      = sanitize_text_field( $data->long_name );
						$address['zipcode']       = sanitize_text_field( $data->long_name );
					break;

					case 'country' :
						$address['country_short'] = sanitize_text_field( $data->short_name );
						$address['country_long']  = sanitize_text_field( $data->long_name );
					break;

				} // end switch()

			} // end foreach()

		}

		return $address;

	} // end get_location_data()

}

// Call wpSight_Geocode class
//new WPSight_Geocode();
