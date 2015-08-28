<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * General class
 *
 * General-purpose methods
 */
class WPSight_General {

	public function __construct( ) {
		add_filter( 'wpsight_standard_details', array( $this, 'check_standard_details' ), 20 );
		add_filter( 'init', array( $this, 'listing_query_vars_general' ) );
		add_filter( 'init', array( $this, 'listing_query_vars_details' ) );
	}

	/**
	 * details()
	 *
	 * Function that defines the array
	 * of standard listing details (beds, baths etc.)
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function details() {

		// Set standard details

		$details = array(

			'details_1' => array(
				'id'           => 'details_1',
				'label'        => __( 'Bedrooms', 'wpsight' ),
				'unit'         => '',
				'data'         => array( '' => __( 'n/d', 'wpsight' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10' ),
				'description'  => '',
				'query_var'    => 'bedrooms',
				'data_compare' => '>=',
				'data_type'    => 'numeric',
				'position'     => 10
			),
			'details_2' => array(
				'id'           => 'details_2',
				'label'        => __( 'Bathrooms', 'wpsight' ),
				'unit'         => '',
				'data'         => array( '' => __( 'n/d', 'wpsight' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10' ),
				'description'  => '',
				'query_var'    => 'bathrooms',
				'data_compare' => '>=',
				'data_type'    => 'numeric',
				'position'     => 20
			),
			'details_3' => array(
				'id'           => 'details_3',
				'label'        => __( 'Plot Size', 'wpsight' ),
				'unit'         => 'm2',
				'description'  => '',
				'query_var'    => 'plot_size',
				'data_compare' => '>=',
				'data_type'    => 'numeric',
				'position'     => 30
			),
			'details_4' => array(
				'id'           => 'details_4',
				'label'        => __( 'Living Area', 'wpsight' ),
				'unit'         => 'm2',
				'data'         => false,
				'description'  => '',
				'query_var'    => 'living_area',
				'data_compare' => '>=',
				'data_type'    => 'numeric',
				'position'     => 40
			),
			'details_5' => array(
				'id'           => 'details_5',
				'label'        => __( 'Terrace', 'wpsight' ),
				'unit'         => 'm2',
				'data'         => false,
				'description'  => '',
				'query_var'    => 'terrace',
				'data_compare' => 'LIKE',
				'data_type'    => 'CHAR',
				'position'     => 50
			),
			'details_6' => array(
				'id'           => 'details_6',
				'label'        => __( 'Parking', 'wpsight' ),
				'unit'         => '',
				'data'         => false,
				'description'  => '',
				'query_var'    => 'parking',
				'data_compare' => 'LIKE',
				'data_type'    => 'CHAR',
				'position'     => 60
			),
			'details_7' => array(
				'id'           => 'details_7',
				'label'        => __( 'Heating', 'wpsight' ),
				'unit'         => '',
				'data'         => false,
				'description'  => '',
				'query_var'    => 'heating',
				'data_compare' => 'LIKE',
				'data_type'    => 'CHAR',
				'position'     => 70
			),
			'details_8' => array(
				'id'           => 'details_8',
				'label'        => __( 'Built in', 'wpsight' ),
				'unit'         => '',
				'data'         => false,
				'description'  => '',
				'query_var'    => 'built_in',
				'data_compare' => 'LIKE',
				'data_type'    => 'CHAR',
				'position'     => 80
			)

		);

		// Apply filter to array
		$details = apply_filters( 'wpsight_wpcasa_standard_details', $details );

		// Sort array by position
		$details = wpsight_sort_array_by_position( $details );

		// Return array
		return $details;

	}

	/**
	 * Filter standard details and update if
	 * label and/or unit have been set on options page
	 *
	 * @return array Updated standard details
	 * @since 1.0.0
	 */

	public static function check_standard_details( $standard_details ) {

		// Just return originals on reset

		if ( isset( $_POST['reset'] ) )
			return $standard_details;

		// Loop through details and check against database

		foreach ( $standard_details as $detail => $value ) {

			$standard_details_option = wpsight_get_option( $detail );

			if ( ! empty( $standard_details_option ) ) {
				$standard_details[$detail]['label'] = $standard_details_option['label'];
				$standard_details[$detail]['unit'] = $standard_details_option['unit'];
			}

		}

		return $standard_details;

	}

	/**
	 * get_detail()
	 *
	 * Get specific detail.
	 *
	 * @param string  $detail Key of the detail to return
	 * @param bool    $return Only return specific element of detail array
	 * @return array|string|bool
	 * @since 1.0.0
	 */

	public static function get_detail( $detail, $return = false ) {

		// Get available details
		$details = wpsight_details();

		// Return array of specific detail

		if ( $return === false )
			return $details[$detail];

		// Only return specific detail element

		if ( isset( $details[$detail][$return] ) )
			return $details[$detail][$return];

		return false;

	}

	/**
	 * get_detail_by_query_var()
	 *
	 * Get specific detail by it's query var key
	 * in the wpsight_details() array.
	 *
	 * @param string  $query_var query_var key
	 * @return string Key of the detail array element
	 * @since 1.0.0
	 */

	public static function get_detail_by_query_var( $query_var ) {

		// Get available details
		$details = wpsight_details();

		// Get query vars
		$query_vars = wp_list_pluck( $details, 'query_var' );

		// Check if query var in array
		$result = array_search( $query_var, $query_vars );

		// If no query var set, use array key

		if ( $result === false && isset( $query_vars[$query_var] ) )
			return $query_var;

		return $result;

	}

	/**
	 * get_query_var_by_detail()
	 *
	 * Get specific detail by it's query var key
	 * in the wpsight_details() array.
	 *
	 * @param string  $query_var query_var key
	 * @return string Key of the detail array element
	 * @since 1.0.0
	 */

	public static function get_query_var_by_detail( $detail ) {

		// Get available details
		$details = wpsight_details();

		// Get query vars
		$detail_vars = wp_list_pluck( $details, 'query_var' );

		// Check if query var in array

		if ( isset( $detail_vars[$detail] ) && $detail_vars[$detail] !== false )
			return $detail_vars[$detail];

		return $detail;

	}

	/**
	 * offers()
	 *
	 * Function that defines the array
	 * of available listing offers (sale, rent etc.)
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function offers() {

		$offers = array(
			'sale' => __( 'For Sale', 'wpsight' ),
			'rent' => __( 'For Rent', 'wpsight' )
		);

		return apply_filters( 'wpsight_offers', $offers );

	}

	/**
	 * get_offer()
	 *
	 * Get specific offer.
	 *
	 * @param string  $offer Key of the offer to return
	 * @return string|bool Label of the offer or false if offer does not exist
	 * @since 1.0.0
	 */

	public static function get_offer( $offer ) {

		// Get available offers
		$offers = wpsight_offers();

		// Return label of specific offer

		if ( isset( $offers[$offer] ) )
			return $offers[$offer];

		return false;

	}

	/**
	 * get_offer_color()
	 *
	 * Get specific offer color used for labels etc.
	 *
	 * @param string  $offer Key of the offer to return
	 * @return string|bool Color of the offer or false if offer does not exist
	 * @since 1.0.0
	 */

	public static function get_offer_color( $offer ) {

		// Set offer colors

		$colors = array(
			'sale' => '#27ae60',
			'rent' => '#2980b9'
		);

		// Apply filter
		$colors = apply_filters( 'wpsight_offer_colors', $colors );

		// Set color of specific offer
		$color = isset( $colors[$offer] ) ? $colors[$offer] : false;

		return apply_filters( 'wpsight_get_offer_color', $color );

	}

	/**
	 * rental_periods()
	 *
	 * Function that defines the array
	 * of available rental periods (monthly etc.)
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function rental_periods() {

		$rental_periods = array(

			'rental_period_1' => __( 'per Month', 'wpsight' ),
			'rental_period_2' => __( 'per Week', 'wpsight' ),
			'rental_period_3' => __( 'per Year', 'wpsight' ),
			'rental_period_4' => __( 'per Day', 'wpsight' )

		);

		return apply_filters( 'wpsight_rental_periods', $rental_periods );

	}

	/**
	 * get_rental_period()
	 *
	 * Get specific rental period.
	 *
	 * @param string  $period Key of the period to return
	 * @return string|bool Label of the period or false if period does not exist
	 * @since 1.0.0
	 */

	public static function get_rental_period( $period ) {

		// Get available periods
		$rental_periods = wpsight_rental_periods();

		// Return label of specific period

		if ( isset( $rental_periods[$period] ) )
			return $rental_periods[$period];

		return false;

	}

	/**
	 * measurements()
	 *
	 * Function that defines the array
	 * of available measurement units (m2,  etc.)
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function measurements() {

		$measurements = array(

			''      => '',
			'm2'    => 'm&sup2;',
			'sqft'  => 'sq ft',
			'sqyd'  => 'sq yd',
			'acres' => 'acre(s)'

		);

		return apply_filters( 'wpsight_measurements', $measurements );

	}

	/**
	 * get_measurement()
	 *
	 * Get specific measurement unit.
	 *
	 * @param string  $measurement Key of the measurement to return
	 * @return string|bool Label of the measurement or false if unit does not exist
	 * @since 1.0.0
	 */

	public static function get_measurement( $measurement ) {

		// Get available measurements
		$measurements = wpsight_measurements();

		// Return label of specific measurement

		if ( isset( $measurements[$measurement] ) )
			return $measurements[$measurement];

		return false;

	}

	/**
	 * date_formats()
	 *
	 * Function that defines the array
	 * of available date formats.
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function date_formats( $date_i18n = false ) {

		$formats = apply_filters( 'date_formats', array( __( 'F j, Y' ), __( 'j. F Y' ), 'Y-m-d', 'y-m-d', 'm/d/Y', 'm/d/y', 'd/m/Y', 'd/m/y', 'd.m.Y', 'd.m.y' ) );

		$date_formats = array();

		foreach ( $formats as $format )
			$date_formats[ $format ] = $date_i18n === true ? date_i18n( $format ) : $format;

		return array_unique( apply_filters( 'wpsight_date_formats', $date_formats ) );

	}

	/**
	 * statuses()
	 *
	 * Function that defines the array
	 * of available post statuses
	 *
	 * @return array
	 * @since 1.0.0
	 */

	public static function statuses() {

		$statuses = array(

			'draft' => array(
				'name'  => _x( 'Draft', 'listing post status', 'wpsight' ),
				'label' => _x( 'Draft', 'listing post status', 'wpsight' )
			),
			'publish' => array(
				'name'  => _x( 'Active', 'listing post status', 'wpsight' ),
				'label' => _x( 'Active', 'listing post status', 'wpsight' )
			),
			'pending' => array(
				'name'  => _x( 'Pending', 'listing post status', 'wpsight' ),
				'label' => _x( 'Pending approval', 'listing post status', 'wpsight' )
			),
			'preview'  => array(
				'name'                      => _x( 'Preview', 'listing post status', 'wpsight' ),
				'label'                     => _x( 'Preview', 'listing post status', 'wpsight' ),
				'public'                    => false,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => false,
				'label_count'               => _n_noop( 'Preview <span class="count">(%s)</span>', 'Preview <span class="count">(%s)</span>', 'wpsight' )
			),
			'pending_payment' => array(
				'name'                      => _x( 'Pending', 'listing post status', 'wpsight' ),
				'label'                     => _x( 'Pending payment', 'listing post status', 'wpsight' ),
				'public'                    => true,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Pending Payment <span class="count">(%s)</span>', 'Pending Payment <span class="count">(%s)</span>', 'wpsight' )
			),
			'trash' => array(
				'name'  => _x( 'Trash', 'listing post status', 'wpsight' ),
				'label' => _x( 'Trash', 'listing post status', 'wpsight' )
			)

		);

		return apply_filters( 'wpsight_statuses', $statuses );

	}

	/**
	 * get_status()
	 *
	 * Get specific listing status
	 *
	 * @param string  $status Key of the corresponding status
	 * @param string  $field  Field of the status (default: label)
	 * @return array
	 * @since 1.0.0
	 */

	public static function get_status( $status, $field = 'label' ) {

		// Get all available statuses
		$statuses = wpsight_statuses();

		if ( isset( $statuses[$status][$field] ) )
			return $statuses[$status][$field];

		return false;

	}

	/**
	 * currencies()
	 *
	 * Function that defines the array
	 * of available currencies (USD, EUR  etc.)
	 *
	 * @return array
	 * @since 1.0.0
	 */

	public static function currencies() {

		$currencies = array(
			'aed' => __( 'AED => United Arab Emirates Dirham', 'wpsight'  ),
			'ang' => __( 'ANG => Netherlands Antillean Guilder', 'wpsight'  ),
			'ars' => __( 'ARS => Argentine Peso', 'wpsight'  ),
			'aud' => __( 'AUD => Australian Dollar', 'wpsight'  ),
			'bdt' => __( 'BDT => Bangladeshi Taka', 'wpsight'  ),
			'bgn' => __( 'BGN => Bulgarian Lev', 'wpsight'  ),
			'bhd' => __( 'BHD => Bahraini Dinar', 'wpsight'  ),
			'bnd' => __( 'BND => Brunei Dollar', 'wpsight'  ),
			'bob' => __( 'BOB => Bolivian Boliviano', 'wpsight'  ),
			'brl' => __( 'BRL => Brazilian Real', 'wpsight'  ),
			'bwp' => __( 'BWP => Botswanan Pula', 'wpsight'  ),
			'cad' => __( 'CAD => Canadian Dollar', 'wpsight'  ),
			'chf' => __( 'CHF => Swiss Franc', 'wpsight'  ),
			'clp' => __( 'CLP => Chilean Peso', 'wpsight'  ),
			'cny' => __( 'CNY => Chinese Yuan', 'wpsight'  ),
			'cop' => __( 'COP => Colombian Peso', 'wpsight'  ),
			'crc' => __( 'CRC => Costa Rican Colon', 'wpsight'  ),
			'czk' => __( 'CZK => Czech Republic Koruna', 'wpsight'  ),
			'dkk' => __( 'DKK => Danish Krone', 'wpsight'  ),
			'dop' => __( 'DOP => Dominican Peso', 'wpsight'  ),
			'dzd' => __( 'DZD => Algerian Dinar', 'wpsight'  ),
			'eek' => __( 'EEK => Estonian Kroon', 'wpsight'  ),
			'egp' => __( 'EGP => Egyptian Pound', 'wpsight'  ),
			'eur' => __( 'EUR => Euro', 'wpsight'  ),
			'fjd' => __( 'FJD => Fijian Dollar', 'wpsight'  ),
			'gbp' => __( 'GBP => British Pound', 'wpsight'  ),
			'hkd' => __( 'HKD => Hong Kong Dollar', 'wpsight'  ),
			'hnl' => __( 'HNL => Honduran Lempira', 'wpsight'  ),
			'hrk' => __( 'HRK => Croatian Kuna', 'wpsight'  ),
			'huf' => __( 'HUF => Hungarian Forint', 'wpsight'  ),
			'idr' => __( 'IDR => Indonesian Rupiah', 'wpsight'  ),
			'ils' => __( 'ILS => Israeli New Sheqel', 'wpsight'  ),
			'inr' => __( 'INR => Indian Rupee', 'wpsight'  ),
			'jmd' => __( 'JMD => Jamaican Dollar', 'wpsight'  ),
			'jod' => __( 'JOD => Jordanian Dinar', 'wpsight'  ),
			'jpy' => __( 'JPY => Japanese Yen', 'wpsight'  ),
			'kes' => __( 'KES => Kenyan Shilling', 'wpsight'  ),
			'krw' => __( 'KRW => South Korean Won', 'wpsight'  ),
			'kwd' => __( 'KWD => Kuwaiti Dinar', 'wpsight'  ),
			'kyd' => __( 'KYD => Cayman Islands Dollar', 'wpsight'  ),
			'kzt' => __( 'KZT => Kazakhstani Tenge', 'wpsight'  ),
			'lbp' => __( 'LBP => Lebanese Pound', 'wpsight'  ),
			'lkr' => __( 'LKR => Sri Lankan Rupee', 'wpsight'  ),
			'ltl' => __( 'LTL => Lithuanian Litas', 'wpsight'  ),
			'lvl' => __( 'LVL => Latvian Lats', 'wpsight'  ),
			'mad' => __( 'MAD => Moroccan Dirham', 'wpsight'  ),
			'mdl' => __( 'MDL => Moldovan Leu', 'wpsight'  ),
			'mkd' => __( 'MKD => Macedonian Denar', 'wpsight'  ),
			'mur' => __( 'MUR => Mauritian Rupee', 'wpsight'  ),
			'mvr' => __( 'MVR => Maldivian Rufiyaa', 'wpsight'  ),
			'mxn' => __( 'MXN => Mexican Peso', 'wpsight'  ),
			'myr' => __( 'MYR => Malaysian Ringgit', 'wpsight'  ),
			'nad' => __( 'NAD => Namibian Dollar', 'wpsight'  ),
			'ngn' => __( 'NGN => Nigerian Naira', 'wpsight'  ),
			'nio' => __( 'NIO => Nicaraguan Cordoba', 'wpsight'  ),
			'nok' => __( 'NOK => Norwegian Krone', 'wpsight'  ),
			'npr' => __( 'NPR => Nepalese Rupee', 'wpsight'  ),
			'nzd' => __( 'NZD => New Zealand Dollar', 'wpsight'  ),
			'omr' => __( 'OMR => Omani Rial', 'wpsight'  ),
			'pen' => __( 'PEN => Peruvian Nuevo Sol', 'wpsight'  ),
			'pgk' => __( 'PGK => Papua New Guinean Kina', 'wpsight'  ),
			'php' => __( 'PHP => Philippine Peso', 'wpsight'  ),
			'pkr' => __( 'PKR => Pakistani Rupee', 'wpsight'  ),
			'pln' => __( 'PLN => Polish Zloty', 'wpsight'  ),
			'pyg' => __( 'PYG => Paraguayan Guarani', 'wpsight'  ),
			'qar' => __( 'QAR => Qatari Rial', 'wpsight'  ),
			'ron' => __( 'RON => Romanian Leu', 'wpsight'  ),
			'rsd' => __( 'RSD => Serbian Dinar', 'wpsight'  ),
			'rub' => __( 'RUB => Russian Ruble', 'wpsight'  ),
			'sar' => __( 'SAR => Saudi Riyal', 'wpsight'  ),
			'scr' => __( 'SCR => Seychellois Rupee', 'wpsight'  ),
			'sek' => __( 'SEK => Swedish Krona', 'wpsight'  ),
			'sgd' => __( 'SGD => Singapore Dollar', 'wpsight'  ),
			'skk' => __( 'SKK => Slovak Koruna', 'wpsight'  ),
			'sll' => __( 'SLL => Sierra Leonean Leone', 'wpsight'  ),
			'svc' => __( 'SVC => Salvadoran Colon', 'wpsight'  ),
			'thb' => __( 'THB => Thai Baht', 'wpsight'  ),
			'tnd' => __( 'TND => Tunisian Dinar', 'wpsight'  ),
			'try' => __( 'TRY => Turkish Lira', 'wpsight'  ),
			'ttd' => __( 'TTD => Trinidad and Tobago Dollar', 'wpsight'  ),
			'twd' => __( 'TWD => New Taiwan Dollar', 'wpsight'  ),
			'tzs' => __( 'TZS => Tanzanian Shilling', 'wpsight'  ),
			'uah' => __( 'UAH => Ukrainian Hryvnia', 'wpsight'  ),
			'ugx' => __( 'UGX => Ugandan Shilling', 'wpsight'  ),
			'usd' => __( 'USD => US Dollar', 'wpsight'  ),
			'uyu' => __( 'UYU => Uruguayan Peso', 'wpsight'  ),
			'uzs' => __( 'UZS => Uzbekistan Som', 'wpsight'  ),
			'vef' => __( 'VEF => Venezuelan Bolivar', 'wpsight'  ),
			'vnd' => __( 'VND => Vietnamese Dong', 'wpsight'  ),
			'xof' => __( 'XOF => CFA Franc BCEAO', 'wpsight'  ),
			'yer' => __( 'YER => Yemeni Rial', 'wpsight'  ),
			'zar' => __( 'ZAR => South African Rand', 'wpsight'  ),
			'zmk' => __( 'ZMK => Zambian Kwacha', 'wpsight'  )
		);

		return apply_filters( 'wpsight_currencies', $currencies );

	}

	/**
	 * get_currency_abbr()
	 *
	 * Get 3-letter currency abbreviation.
	 *
	 * @param string  $currency 3-letter code of specific currency
	 * @return string 3-letter currency code
	 * @since 1.0.0
	 */

	public static function get_currency_abbr( $currency = '' ) {

		if ( empty( $currency ) )
			$currency = wpsight_get_option( 'currency', 'eur' );

		// Check if there is a custom currency

		if ( $currency != 'other' ) {

			return strtoupper( $currency );

		} else {

			return wpsight_get_option( 'currency_other' );

		}

	}

	/**
	 * get_currency()
	 *
	 * Get currency entity.
	 *
	 * @param string  $currency 3-letter code of specific currency
	 * @return string Currency entity or 3-letter code
	 * @since 1.0.0
	 */

	public static function get_currency( $currency = '' ) {

		// Get currency from theme options

		if ( empty( $currency ) )
			$currency = wpsight_get_option( 'currency', 'eur' );

		// Check if there is a custom currency

		if ( $currency != 'other' ) {

			// Get currency abbreviation

			$currency = wpsight_get_currency_abbr( $currency );

			// Create HTML entities

			if ( $currency == 'EUR' ) {
				$currency_ent = '&euro;';
			}
			elseif ( $currency == 'USD' ) {
				$currency_ent = '&#36;';
			}
			elseif ( $currency == 'CAD' ) {
				$currency_ent = 'C&#36;';
			}
			elseif ( $currency == 'GBP' ) {
				$currency_ent = '&pound;';
			}
			elseif ( $currency == 'AUD' ) {
				$currency_ent = 'AU&#36;';
			}
			elseif ( $currency == 'JPY' ) {
				$currency_ent = '&yen;';
			}
			elseif ( $currency == 'CHF' ) {
				$currency_ent = ' SFr. ';
			}
			elseif ( $currency == 'ILS' ) {
				$currency_ent = '&#8362;';
			}
			elseif ( $currency == 'THB' ) {
				$currency_ent = '&#3647;';
			}

		} else {

			$currency_ent = wpsight_get_option( 'currency_other_ent' );

		}

		// If no entity, set three letter code

		if ( empty( $currency_ent ) )
			$currency_ent = ' ' . $currency . ' ';

		return apply_filters( 'wpsight_get_currency', $currency_ent );

	}

	/**
	 * currency()
	 *
	 * Echo wpsight_get_currency().
	 *
	 * @param string  $currency 3-letter code of specific currency
	 * @return string Currency entity or 3-letter code
	 * @uses wpsight_get_currency()
	 * @since 1.0.0
	 */

	public static function currency( $currency = '' ) {
		echo wpsight_get_currency( $currency );
	}

	/**
	 * spaces()
	 *
	 * Function that defines the array
	 * of available widget spaces.
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function spaces() {

		$spaces = array(
			'space' => array(
				'title'       => __( 'Space', 'wpsight' ),
				'label'       => __( 'Widget Space', 'wpsight' ),
				'key'         => '_space',
				'description' => __( 'Add some custom content to this page. Then drag the Single Space widget to the Listing Content or Listing Sidebar widget area.', 'wpsight' ),
				'type'        => 'textarea',
				'rows'        => 5,
				'post_type'   => array( wpsight_post_type() )
			)
		);

		return apply_filters( 'wpsight_spaces', $spaces );

	}

	/**
	 * @return all custom query vars for listings
	 */

	public static function listing_query_vars() {

		$vars = array_merge(
			(array) self::listing_query_vars_general(),
			(array) self::listing_query_vars_details(),
			(array) self::listing_query_vars_taxonomies()
		);

		return apply_filters( 'wpsight_listing_query_vars', $vars );

	}

	/**
	 * listing_query_vars_general()
	 *
	 * Add general query vars for listings
	 *
	 * @since 1.0.0
	 */

	public static function listing_query_vars_general() {
		global $wp;

		// Set custom vars

		$vars = array(
			'keyword' => get_query_var( 'keyword' ),
			'status'  => get_query_var( 'status' ),
			'offer'   => get_query_var( 'offer' ),
			'min'     => get_query_var( 'min' ),
			'max'     => get_query_var( 'max' ),
			'nr'      => get_query_var( 'nr' )
		);

		// Add query vars

		foreach ( array_keys( $vars ) as $var )
			$wp->add_query_var( $var );

		return $vars;

	}

	/**
	 * listing_query_vars_details()
	 *
	 * Add detail query vars for listings
	 *
	 * @since 1.0.0
	 */

	public static function listing_query_vars_details() {
		global $wp;

		// Set custom vars
		$vars = array();

		// Get all listing details
		$details = wpsight_details();

		// Loop through details

		foreach ( $details as $k => $v ) {

			$k = wpsight_get_query_var_by_detail( $k );

			$vars[$k] = get_query_var( $k );

		}

		foreach ( array_keys( $vars ) as $var )
			$wp->add_query_var( $var );

		return $vars;

	}

	/**
	 * listing_query_vars_taxonomies()
	 *
	 * @return taxonomy query vars for listings
	 *
	 * @since 1.0.0
	 */

	public static function listing_query_vars_taxonomies() {

		// Set custom vars
		$vars = array();

		// Loop through taxonomies

		foreach ( get_object_taxonomies( wpsight_post_type() ) as $k )
			$vars[$k] = get_query_var( $k );

		return $vars;

	}

	/**
	 * user_can_edit_listing_id()
	 *
	 * Check if current user can edit the listing ID.
	 *
	 * @return bool True if user is eligible, else false
	 * @since 1.0.0
	 */

	public static function user_can_edit_listing_id() {

		$can = false;

		if ( is_user_logged_in() )
			$can = current_user_can( 'edit_listing_id' );

		return apply_filters( 'wpsight_user_can_edit_listing_id', $can );

	}


}
