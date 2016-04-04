<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPSight_General class
 */
class WPSight_General {

	/**
	 * Constructor
	 */
	public function __construct( ) {
		add_filter( 'wpsight_details', array( $this, 'check_standard_details' ), 20 );
		add_filter( 'wpsight_rental_periods', array( $this, 'check_rental_periods' ), 20 );
		add_filter( 'init', array( $this, 'listing_query_vars_general' ) );
		add_filter( 'init', array( $this, 'listing_query_vars_details' ) );
	}

	/**
	 * details()
	 *
	 * Function that defines the array
	 * of standard listing details (beds, baths etc.)
	 *
	 * @uses wpsight_sort_array_by_position()
	 * @return array $details Array of standard listing details
	 *
	 * @since 1.0.0
	 */
	public static function details() {

		// Set standard details

		$details = array(

			'details_1' => array(
				'id'			=> 'details_1',
				'label'			=> __( 'Beds', 'wpcasa' ),
				'unit'			=> '',
				'data'			=> array( '' => __( 'n/d', 'wpcasa' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10' ),
				'description'	=> '',
				'query_var'		=> 'bedrooms',
				'data_compare'	=> '>=',
				'data_type'		=> 'numeric',
				'dashboard'		=> true,
				'position'		=> 10
			),
			'details_2' => array(
				'id'			=> 'details_2',
				'label'			=> __( 'Baths', 'wpcasa' ),
				'unit'			=> '',
				'data'			=> array( '' => __( 'n/d', 'wpcasa' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10' ),
				'description'	=> '',
				'query_var'		=> 'bathrooms',
				'data_compare'	=> '>=',
				'data_type'		=> 'numeric',
				'dashboard'		=> true,
				'position'		=> 20
			),
			'details_3' => array(
				'id'			=> 'details_3',
				'label'			=> __( 'Plot Size', 'wpcasa' ),
				'unit'			=> 'm2',
				'description'	=> '',
				'query_var'		=> 'plot_size',
				'data_compare'	=> '>=',
				'data_type'		=> 'numeric',
				'dashboard'		=> true,
				'position'		=> 30
			),
			'details_4' => array(
				'id'			=> 'details_4',
				'label'			=> __( 'Living Area', 'wpcasa' ),
				'unit'			=> 'm2',
				'data'			=> false,
				'description'	=> '',
				'query_var'		=> 'living_area',
				'data_compare'	=> '>=',
				'data_type'		=> 'numeric',
				'dashboard'		=> true,
				'position'		=> 40
			),
			'details_5' => array(
				'id'			=> 'details_5',
				'label'			=> __( 'Terrace', 'wpcasa' ),
				'unit'			=> 'm2',
				'data'			=> false,
				'description'	=> '',
				'query_var'		=> 'terrace',
				'data_compare'	=> 'LIKE',
				'data_type'		=> 'CHAR',
				'dashboard'		=> true,
				'position'		=> 50
			),
			'details_6' => array(
				'id'			=> 'details_6',
				'label'			=> __( 'Parking', 'wpcasa' ),
				'unit'			=> '',
				'data'			=> false,
				'description'	=> '',
				'query_var'		=> 'parking',
				'data_compare'	=> 'LIKE',
				'data_type'		=> 'CHAR',
				'dashboard'		=> true,
				'position'		=> 60
			),
			'details_7' => array(
				'id'			=> 'details_7',
				'label'			=> __( 'Heating', 'wpcasa' ),
				'unit'			=> '',
				'data'			=> false,
				'description'	=> '',
				'query_var'		=> 'heating',
				'data_compare'	=> 'LIKE',
				'data_type'		=> 'CHAR',
				'dashboard'		=> true,
				'position'		=> 70
			),
			'details_8' => array(
				'id'			=> 'details_8',
				'label'			=> __( 'Built in', 'wpcasa' ),
				'unit'			=> '',
				'data'			=> false,
				'description'	=> '',
				'query_var'		=> 'built_in',
				'data_compare'	=> 'LIKE',
				'data_type'		=> 'CHAR',
				'dashboard'		=> true,
				'position'		=> 80
			)

		);

		// Apply filter to array
		$details = apply_filters( 'wpsight_details', $details );

		// Sort array by position
		$details = wpsight_sort_array_by_position( $details );

		// Return array
		return $details;

	}

	/**
	 * check_standard_details()
	 *
	 * Filter standard details and update if
	 * label and/or unit have been set on options page
	 *
	 * @uses wpsight_get_option()
	 * @return array Updated standard details
	 *
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
				$standard_details[ $detail ]['label'] = $standard_details_option['label'];
				$standard_details[ $detail ]['unit'] = $standard_details_option['unit'];
			}

		}

		return $standard_details;

	}

	/**
	 * get_detail()
	 *
	 * Get specific detail.
	 *
	 * @param string $detail Key of the detail to return
	 * @param bool $return Only return specific element of detail array
	 * @uses self::details()
	 * @return array|string|bool
	 *
	 * @since 1.0.0
	 */
	public static function get_detail( $detail, $return = false ) {

		// Get available details
		$details = self::details();

		// Return array of specific detail

		if ( $return === false )
			return $details[ $detail ];

		// Only return specific detail element

		if ( isset( $details[ $detail ][ $return ] ) )
			return $details[ $detail ][ $return ];

		return false;

	}

	/**
	 * get_detail_by_query_var()
	 *
	 * Get specific detail by it's query var key
	 * in the wpsight_details() array.
	 *
	 * @param string  $query_var query_var key
	 * @uses self::details()
	 * @return string Key of the detail array element
	 *
	 * @since 1.0.0
	 */
	public static function get_detail_by_query_var( $query_var ) {

		// Get available details
		$details = self::details();

		// Get query vars
		$query_vars = wp_list_pluck( $details, 'query_var' );

		// Check if query var in array
		$result = array_search( $query_var, $query_vars );

		// If no query var set, use array key

		if ( $result === false && isset( $query_vars[ $query_var ] ) )
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
	 * @uses self::details()
	 * @uses wp_list_pluck()
	 * @return string Key of the detail array element
	 *
	 * @since 1.0.0
	 */
	public static function get_query_var_by_detail( $detail ) {

		// Get available details
		$details = self::details();

		// Get query vars
		$detail_vars = wp_list_pluck( $details, 'query_var' );

		// Check if query var in array

		if ( isset( $detail_vars[ $detail ] ) && $detail_vars[ $detail ] !== false )
			return $detail_vars[ $detail ];

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
			'sale' => __( 'For Sale', 'wpcasa' ),
			'rent' => __( 'For Rent', 'wpcasa' )
		);

		return apply_filters( 'wpsight_offers', $offers );

	}

	/**
	 * get_offer()
	 *
	 * Get specific offer.
	 *
	 * @param string  $offer Key of the offer to return
	 * @uses self::offers()
	 * @return string|bool Label of the offer or false if offer does not exist
	 *
	 * @since 1.0.0
	 */
	public static function get_offer( $offer ) {

		// Get available offers
		$offers = self::offers();

		// Return label of specific offer

		if ( isset( $offers[ $offer ] ) )
			return $offers[ $offer ];

		return false;

	}

	/**
	 * get_offer_color()
	 *
	 * Get specific offer color used for labels etc.
	 *
	 * @param string  $offer Key of the offer to return
	 * @return string|bool Color of the offer or false if offer does not exist
	 *
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
		$color = isset( $colors[ $offer ] ) ? $colors[ $offer ] : false;

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

			'rental_period_1' => __( 'per Month', 'wpcasa' ),
			'rental_period_2' => __( 'per Week', 'wpcasa' ),
			'rental_period_3' => __( 'per Year', 'wpcasa' ),
			'rental_period_4' => __( 'per Day', 'wpcasa' )

		);

		return apply_filters( 'wpsight_rental_periods', $rental_periods );

	}

	/**
	 * check_rental_periods()
	 *
	 * Filter rental periods and update if
	 * label has been set on options page
	 *
	 * @uses wpsight_get_option()
	 * @return array Updated rental periods
	 *
	 * @since 1.0.0
	 */
	public static function check_rental_periods( $rental_periods ) {

		// Just return originals on reset

		if ( isset( $_POST['reset'] ) )
			return $rental_periods;

		// Loop through details and check against database

		foreach ( $rental_periods as $period => $value ) {
			
			$rental_periods_option = wpsight_get_option( $period );
			
			if( ! empty( $rental_periods_option ) )
				$rental_periods[ $period ] = $rental_periods_option;

		}

		return $rental_periods;

	}

	/**
	 * get_rental_period()
	 *
	 * Get specific rental period.
	 *
	 * @param string  $period Key of the period to return
	 * @uses self::rental_periods()
	 * @return string|bool Label of the period or false if period does not exist
	 *
	 * @since 1.0.0
	 */
	public static function get_rental_period( $period ) {

		// Get available periods
		$rental_periods = self::rental_periods();

		// Return label of specific period

		if ( isset( $rental_periods[ $period ] ) )
			return $rental_periods[ $period ];

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
			''		=> '',
			'm2'	=> 'm&sup2;',
			'sqft'	=> 'sq ft',
			'sqyd'	=> 'sq yd',
			'acres'	=> 'acre(s)'
		);

		return apply_filters( 'wpsight_measurements', $measurements );

	}

	/**
	 * get_measurement()
	 *
	 * Get specific measurement unit.
	 *
	 * @param string  $measurement Key of the measurement to return
	 * @uses self::measurements()
	 * @return string|bool Label of the measurement or false if unit does not exist
	 *
	 * @since 1.0.0
	 */
	public static function get_measurement( $measurement ) {

		// Get available measurements
		$measurements = self::measurements();

		// Return label of specific measurement

		if ( isset( $measurements[ $measurement ] ) )
			return $measurements[ $measurement ];

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
	 *
	 * @since 1.0.0
	 */
	public static function statuses() {

		$statuses = array(

			'draft' => array(
				'name'	=> _x( 'Draft', 'listing post status', 'wpcasa' ),
				'label'	=> _x( 'Draft', 'listing post status', 'wpcasa' )
			),
			'publish' => array(
				'name'	=> _x( 'Active', 'listing post status', 'wpcasa' ),
				'label'	=> _x( 'Active', 'listing post status', 'wpcasa' )
			),
			'pending' => array(
				'name'	=> _x( 'Pending', 'listing post status', 'wpcasa' ),
				'label'	=> _x( 'Pending approval', 'listing post status', 'wpcasa' )
			),
			'preview' => array(
				'name'						=> _x( 'Preview', 'listing post status', 'wpcasa' ),
				'label'						=> _x( 'Preview', 'listing post status', 'wpcasa' ),
				'public'					=> false,
				'exclude_from_search'		=> true,
				'show_in_admin_all_list'	=> false,
				'show_in_admin_status_list'	=> false,
				'label_count'				=> _n_noop( 'Preview <span class="count">(%s)</span>', 'Preview <span class="count">(%s)</span>', 'wpcasa' )
			),
			'pending_payment' => array(
				'name'						=> _x( 'Pending', 'listing post status', 'wpcasa' ),
				'label'						=> _x( 'Pending payment', 'listing post status', 'wpcasa' ),
				'public'					=> true,
				'exclude_from_search'		=> true,
				'show_in_admin_all_list'	=> true,
				'show_in_admin_status_list'	=> true,
				'label_count'				=> _n_noop( 'Pending Payment <span class="count">(%s)</span>', 'Pending Payment <span class="count">(%s)</span>', 'wpcasa' )
			),
			'trash' => array(
				'name'	=> _x( 'Trash', 'listing post status', 'wpcasa' ),
				'label'	=> _x( 'Trash', 'listing post status', 'wpcasa' )
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
	 * @uses self::statuses()
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function get_status( $status, $field = 'label' ) {

		// Get all available statuses
		$statuses = self::statuses();

		if ( isset( $statuses[ $status ][ $field ] ) )
			return $statuses[ $status ][ $field ];

		return false;

	}

	/**
	 * currencies()
	 *
	 * Function that defines the array
	 * of available currencies (USD, EUR  etc.)
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function currencies() {

		$currencies = array(
			'aed' => __( 'AED => United Arab Emirates Dirham', 'wpcasa' ),
			'ang' => __( 'ANG => Netherlands Antillean Guilder', 'wpcasa' ),
			'ars' => __( 'ARS => Argentine Peso', 'wpcasa' ),
			'aud' => __( 'AUD => Australian Dollar', 'wpcasa' ),
			'bdt' => __( 'BDT => Bangladeshi Taka', 'wpcasa' ),
			'bgn' => __( 'BGN => Bulgarian Lev', 'wpcasa' ),
			'bhd' => __( 'BHD => Bahraini Dinar', 'wpcasa' ),
			'bnd' => __( 'BND => Brunei Dollar', 'wpcasa' ),
			'bob' => __( 'BOB => Bolivian Boliviano', 'wpcasa' ),
			'brl' => __( 'BRL => Brazilian Real', 'wpcasa' ),
			'bwp' => __( 'BWP => Botswanan Pula', 'wpcasa' ),
			'cad' => __( 'CAD => Canadian Dollar', 'wpcasa' ),
			'chf' => __( 'CHF => Swiss Franc', 'wpcasa' ),
			'clp' => __( 'CLP => Chilean Peso', 'wpcasa' ),
			'cny' => __( 'CNY => Chinese Yuan', 'wpcasa' ),
			'cop' => __( 'COP => Colombian Peso', 'wpcasa' ),
			'crc' => __( 'CRC => Costa Rican Colon', 'wpcasa' ),
			'czk' => __( 'CZK => Czech Republic Koruna', 'wpcasa' ),
			'dkk' => __( 'DKK => Danish Krone', 'wpcasa' ),
			'dop' => __( 'DOP => Dominican Peso', 'wpcasa' ),
			'dzd' => __( 'DZD => Algerian Dinar', 'wpcasa' ),
			'eek' => __( 'EEK => Estonian Kroon', 'wpcasa' ),
			'egp' => __( 'EGP => Egyptian Pound', 'wpcasa' ),
			'eur' => __( 'EUR => Euro', 'wpcasa' ),
			'fjd' => __( 'FJD => Fijian Dollar', 'wpcasa' ),
			'gbp' => __( 'GBP => British Pound', 'wpcasa' ),
			'hkd' => __( 'HKD => Hong Kong Dollar', 'wpcasa' ),
			'hnl' => __( 'HNL => Honduran Lempira', 'wpcasa' ),
			'hrk' => __( 'HRK => Croatian Kuna', 'wpcasa' ),
			'huf' => __( 'HUF => Hungarian Forint', 'wpcasa' ),
			'idr' => __( 'IDR => Indonesian Rupiah', 'wpcasa' ),
			'ils' => __( 'ILS => Israeli New Sheqel', 'wpcasa' ),
			'inr' => __( 'INR => Indian Rupee', 'wpcasa' ),
			'jmd' => __( 'JMD => Jamaican Dollar', 'wpcasa' ),
			'jod' => __( 'JOD => Jordanian Dinar', 'wpcasa' ),
			'jpy' => __( 'JPY => Japanese Yen', 'wpcasa' ),
			'kes' => __( 'KES => Kenyan Shilling', 'wpcasa' ),
			'krw' => __( 'KRW => South Korean Won', 'wpcasa' ),
			'kwd' => __( 'KWD => Kuwaiti Dinar', 'wpcasa' ),
			'kyd' => __( 'KYD => Cayman Islands Dollar', 'wpcasa' ),
			'kzt' => __( 'KZT => Kazakhstani Tenge', 'wpcasa' ),
			'lbp' => __( 'LBP => Lebanese Pound', 'wpcasa' ),
			'lkr' => __( 'LKR => Sri Lankan Rupee', 'wpcasa' ),
			'ltl' => __( 'LTL => Lithuanian Litas', 'wpcasa' ),
			'lvl' => __( 'LVL => Latvian Lats', 'wpcasa' ),
			'mad' => __( 'MAD => Moroccan Dirham', 'wpcasa' ),
			'mdl' => __( 'MDL => Moldovan Leu', 'wpcasa' ),
			'mkd' => __( 'MKD => Macedonian Denar', 'wpcasa' ),
			'mur' => __( 'MUR => Mauritian Rupee', 'wpcasa' ),
			'mvr' => __( 'MVR => Maldivian Rufiyaa', 'wpcasa' ),
			'mxn' => __( 'MXN => Mexican Peso', 'wpcasa' ),
			'myr' => __( 'MYR => Malaysian Ringgit', 'wpcasa' ),
			'nad' => __( 'NAD => Namibian Dollar', 'wpcasa' ),
			'ngn' => __( 'NGN => Nigerian Naira', 'wpcasa' ),
			'nio' => __( 'NIO => Nicaraguan Cordoba', 'wpcasa' ),
			'nok' => __( 'NOK => Norwegian Krone', 'wpcasa' ),
			'npr' => __( 'NPR => Nepalese Rupee', 'wpcasa' ),
			'nzd' => __( 'NZD => New Zealand Dollar', 'wpcasa' ),
			'omr' => __( 'OMR => Omani Rial', 'wpcasa' ),
			'pen' => __( 'PEN => Peruvian Nuevo Sol', 'wpcasa' ),
			'pgk' => __( 'PGK => Papua New Guinean Kina', 'wpcasa' ),
			'php' => __( 'PHP => Philippine Peso', 'wpcasa' ),
			'pkr' => __( 'PKR => Pakistani Rupee', 'wpcasa' ),
			'pln' => __( 'PLN => Polish Zloty', 'wpcasa' ),
			'pyg' => __( 'PYG => Paraguayan Guarani', 'wpcasa' ),
			'qar' => __( 'QAR => Qatari Rial', 'wpcasa' ),
			'ron' => __( 'RON => Romanian Leu', 'wpcasa' ),
			'rsd' => __( 'RSD => Serbian Dinar', 'wpcasa' ),
			'rub' => __( 'RUB => Russian Ruble', 'wpcasa' ),
			'sar' => __( 'SAR => Saudi Riyal', 'wpcasa' ),
			'scr' => __( 'SCR => Seychellois Rupee', 'wpcasa' ),
			'sek' => __( 'SEK => Swedish Krona', 'wpcasa' ),
			'sgd' => __( 'SGD => Singapore Dollar', 'wpcasa' ),
			'skk' => __( 'SKK => Slovak Koruna', 'wpcasa' ),
			'sll' => __( 'SLL => Sierra Leonean Leone', 'wpcasa' ),
			'svc' => __( 'SVC => Salvadoran Colon', 'wpcasa' ),
			'thb' => __( 'THB => Thai Baht', 'wpcasa' ),
			'tnd' => __( 'TND => Tunisian Dinar', 'wpcasa' ),
			'try' => __( 'TRY => Turkish Lira', 'wpcasa' ),
			'ttd' => __( 'TTD => Trinidad and Tobago Dollar', 'wpcasa' ),
			'twd' => __( 'TWD => New Taiwan Dollar', 'wpcasa' ),
			'tzs' => __( 'TZS => Tanzanian Shilling', 'wpcasa' ),
			'uah' => __( 'UAH => Ukrainian Hryvnia', 'wpcasa' ),
			'ugx' => __( 'UGX => Ugandan Shilling', 'wpcasa' ),
			'usd' => __( 'USD => US Dollar', 'wpcasa' ),
			'uyu' => __( 'UYU => Uruguayan Peso', 'wpcasa' ),
			'uzs' => __( 'UZS => Uzbekistan Som', 'wpcasa' ),
			'vef' => __( 'VEF => Venezuelan Bolivar', 'wpcasa' ),
			'vnd' => __( 'VND => Vietnamese Dong', 'wpcasa' ),
			'xof' => __( 'XOF => CFA Franc BCEAO', 'wpcasa' ),
			'yer' => __( 'YER => Yemeni Rial', 'wpcasa' ),
			'zar' => __( 'ZAR => South African Rand', 'wpcasa' ),
			'zmk' => __( 'ZMK => Zambian Kwacha', 'wpcasa' )
		);

		return apply_filters( 'wpsight_currencies', $currencies );

	}

	/**
	 * get_currency_abbr()
	 *
	 * Get 3-letter currency abbreviation.
	 *
	 * @param string  $currency 3-letter code of specific currency
	 * @uses wpsight_get_option()
	 * @return string 3-letter currency code
	 *
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
	 * @uses wpsight_get_option()
	 * @uses wpsight_get_currency_abbr()
	 * @return string Currency entity or 3-letter code
	 *
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
	 *
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
	 * @uses wpsight_post_type()
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function spaces() {
		
		/**
		 * Spaces are empty by default but can
		 * be create using the wpsight_spaces
		 * filter hook in add-ons or themes.
		 */
		return apply_filters( 'wpsight_spaces', array() );

	}

	/**
	 * listing_query_vars()
	 *
	 * Rreturn all custom query vars for listings
	 *
	 * @uses self::listing_query_vars_general()
	 * @uses self::listing_query_vars_details()
	 * @uses self::listing_query_vars_taxonomies()
	 * @return array
	 *
	 * @since 1.0.0
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
	 * @uses get_query_var()
	 * @uses add_query_var()
	 * @return array
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
	 * @uses self::details()
	 * @uses wpsight_get_query_var_by_detail()
	 * @uses get_query_var()
	 * @uses add_query_var()
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function listing_query_vars_details() {
		global $wp;

		// Set custom vars
		$vars = array();

		// Get all listing details
		$details = self::details();

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
	 * Return taxonomy query vars for listings
	 *
	 * @uses wpsight_post_type()
	 * @uses wpsight_taxonomies()
	 * @uses get_query_var()
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function listing_query_vars_taxonomies() {

		// Set custom vars
		$vars = array();

		// Loop through taxonomies

		foreach ( wpsight_taxonomies( 'names' ) as $k )
			$vars[$k] = get_query_var( $k );

		return $vars;

	}

}
