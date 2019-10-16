<?php

class WPSight_Currencies {

	public function currencies() {
		
		$currencies = array(
		
			/* ### A ### */	
			'AED' => array(
				'name'		=> __( 'AED - United Arab Emirates Dirham', 'wpcasa' ),
				'symbol'	=> 'د.إ',
				'hex'		=> '&#x62f;&#x2e;&#x625;'
			),
			'ANG' => array(
				'name'		=> __( 'ANG - Netherlands Antillean Guilder', 'wpcasa' ),
				'symbol'	=> 'ƒ',
				'hex'		=> '&#x192;'
			),
			'ARS' => array(
				'name'		=> __( 'ARS - Argentine Peso', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'AUD' => array(
				'name'		=> __( 'AUD - Australian Dollar', 'wpcasa' ),
				'symbol'	=> 'A$',
				'hex'		=> '&#x41;&#x24;'
			),
			
			/* ### B ### */	
			'BDT' => array(
				'name'		=> __( 'BDT - Bangladeshi Taka', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'BGN' => array(
				'name'		=> __( 'BGN - Bulgarian Lev', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'BHD' => array(
				'name'		=> __( 'BHD - Bahraini Dinar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'BND' => array(
				'name'		=> __( 'BND - Brunei Dollar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'BOB' => array(
				'name'		=> __( 'BOB - Bolivian Boliviano', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'BRL' => array(
				'name'		=> __( 'BRL - Brazilian Real', 'wpcasa' ),
				'symbol'	=> 'R$',
				'hex'		=> '&#x52;&#x24;'
			),
			'BSD' => array(
				'name'		=> __( 'BSD - Bahamian Dollar', 'wpcasa' ),
				'symbol'	=> 'B$',
				'hex'		=> '&#x42;&#x24;'
			),
			'BWP' => array(
				'name'		=> __( 'BWP - Botswanan Pula', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### C ### */
			'CAD' => array(
				'name'		=> __( 'CAD - Canadian Dollar', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'CHF' => array(
				'name'		=> __( 'CHF - Swiss Franc', 'wpcasa' ),
				'symbol'	=> 'CHF',
				'hex'		=> '&#x43;&#x48;&#x46;'
			),
			'CLP' => array(
				'name'		=> __( 'CLP - Chilean Peso', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'CNY' => array(
				'name'		=> __( 'CNY - Chinese Yuan Renminbi', 'wpcasa' ),
				'symbol'	=> '¥',
				'hex'		=> '&#xa5;'
			),
			'COP' => array(
				'name'		=> __( 'COP - Colombian Peso', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'CRC' => array(
				'name'		=> __( 'CRC - Costa Rican Colon', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'CZK' => array(
				'name'		=> __( 'CZK - Czech Koruna', 'wpcasa' ),
				'symbol'	=> 'Kč',
				'hex'		=> '&#x4b;&#x10d;'
			),
	
			/* ### D ### */
			'DKK' => array(
				'name'		=> __( 'DKK - Danish Krone', 'wpcasa' ),
				'symbol'	=> 'kr',
				'hex'		=> '&#x6b;&#x72;'
			),
			'DOP' => array(
				'name'		=> __( 'DOP - Dominican Peso', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'DZD' => array(
				'name'		=> __( 'DZD - Algerian Dinar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
					
			/* ### E ### */
			'EEK' => array(
				'name'		=> __( 'EEK - Estonian Kroon', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'EGP' => array(
				'name'		=> __( 'EGP - Egyptian Pound', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'EUR' => array(
				'name'		=> __( 'EUR - Euro', 'wpcasa' ),
				'symbol'	=> '€',
				'hex'		=> '&#x20ac;'
			),		
			
			/* ### F ### */
			'FJD' => array(
				'name'		=> __( 'FJD - Fijian Dollar', 'wpcasa' ),
				'symbol'	=> 'FJ$',
				'hex'		=> '&#x46;&#x4a;&#x24;'
			),
			
			/* ### G ### */
			'GBP' => array(
				'name'		=> __( 'GBP - British Pound', 'wpcasa' ),
				'symbol'	=> '£',
				'hex'		=> '&#xa3;'
			),
			'GHS' => array(
				'name'		=> __( 'GHS - Ghanaian New Cedi', 'wpcasa' ),
				'symbol'	=> 'GH₵',
				'hex'		=> '&#x47;&#x48;&#x20b5;'
			),
			'GTQ' => array(
				'name'		=> __( 'GTQ - Guatemalan Quetzal', 'wpcasa' ),
				'symbol'	=> 'Q',
				'hex'		=> '&#x51;'
			),
			
			/* ### H ### */
			'HKD' => array(
				'name'		=> __( 'HKD - Hong Kong Dollar', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'HNL' => array(
				'name'		=> __( 'HNL - Honduran Lempira', 'wpcasa' ),
				'symbol'	=> 'L',
				'hex'		=> '&#x4c;'
			),
			'HRK' => array(
				'name'		=> __( 'HRK - Croatian Kuna', 'wpcasa' ),
				'symbol'	=> 'kn',
				'hex'		=> '&#x6b;&#x6e;'
			),
			'HUF' => array(
				'name'		=> __( 'HUF - Hungarian Forint', 'wpcasa' ),
				'symbol'	=> 'Ft',
				'hex'		=> '&#x46;&#x74;'
			),
			
			/* ### I ### */
			'IDR' => array(
				'name'		=> __( 'IDR - Indonesian Rupiah', 'wpcasa' ),
				'symbol'	=> 'Rp',
				'hex'		=> '&#x52;&#x70;'
			),
			'ILS' => array(
				'name'		=> __( 'ILS - Israeli New Sheqel', 'wpcasa' ),
				'symbol'	=> '₪',
				'hex'		=> '&#x20aa;'
			),
			'INR' => array(
				'name'		=> __( 'INR - Indian Rupee', 'wpcasa' ),
				'symbol'	=> '₹',
				'hex'		=> '&#x20b9;'
			),
			'ISK' => array(
				'name'		=> __( 'ISK - Icelandic króna', 'wpcasa' ),
				'symbol'	=> 'kr',
				'hex'		=> '&#x6b;&#x72;'
			),
			
			/* ### J ### */
			'JMD' => array(
				'name'		=> __( 'JMD - Jamaican Dollar', 'wpcasa' ),
				'symbol'	=> 'J$',
				'hex'		=> '&#x4a;&#x24;'
			),
			'JOD' => array(
				'name'		=> __( 'JOD - Jordanian Dinar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'JPY' => array(
				'name'		=> __( 'JPY - Japanese Yen', 'wpcasa' ),
				'symbol'	=> '¥',
				'hex'		=> '&#xa5;'
			),
			
			/* ### K ### */
			'KES' => array(
				'name'		=> __( 'KES - Kenyan Shilling', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'KRW' => array(
				'name'		=> __( 'KRW - South Korean Won', 'wpcasa' ),
				'symbol'	=> '₩',
				'hex'		=> '&#x20a9;'
			),
			'KWD' => array(
				'name'		=> __( 'KWD - Kuwaiti Dinar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'KYD' => array(
				'name'		=> __( 'KYD - Cayman Islands Dollar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'KZT' => array(
				'name'		=> __( 'KZT - Kazakhstani Tenge', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### L ### */
			'LBP' => array(
				'name'		=> __( 'LBP - Lebanese Pound', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'LKR' => array(
				'name'		=> __( 'LKR - Sri Lankan Rupee', 'wpcasa' ),
				'symbol'	=> '₨',
				'hex'		=> '&#x20a8;'
			),
			'LTL' => array(
				'name'		=> __( 'LTL - Lithuanian Litas', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'LVL' => array(
				'name'		=> __( 'LVL - Latvian Lats', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### M ### */
			'MAD' => array(
				'name'		=> __( 'MAD - Moroccan Dirham', 'wpcasa' ),
				'symbol'	=> '.د.م',
				'hex'		=> '&#x2e;&#x62f;&#x2e;&#x645;'
			),
			'MDL' => array(
				'name'		=> __( 'MDL - Moldovan Leu', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'MKD' => array(
				'name'		=> __( 'MKD - Macedonian Denar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'MMK' => array(
				'name'		=> __( 'MMK - Myanmar Kyat', 'wpcasa' ),
				'symbol'	=> 'K',
				'hex'		=> '&#x4b;'
			),
			'MUR' => array(
				'name'		=> __( 'MUR - Mauritian Rupee', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'MVR' => array(
				'name'		=> __( 'MVR - Maldivian Rufiyaa', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'MXN' => array(
				'name'		=> __( 'MXN - Mexican Peso', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'MYR' => array(
				'name'		=> __( 'MYR - Malaysian Ringgit', 'wpcasa' ),
				'symbol'	=> 'RM',
				'hex'		=> '&#x52;&#x4d;'
			),
			
			/* ### N ### */
			'NAD' => array(
				'name'		=> __( 'NAD - Namibian Dollar', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'NGN' => array(
				'name'		=> __( 'NGN - Nigerian Naira', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'NIO' => array(
				'name'		=> __( 'NIO - Nicaraguan Cordoba', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'NOK' => array(
				'name'		=> __( 'NOK - Norwegian Krone', 'wpcasa' ),
				'symbol'	=> 'kr',
				'hex'		=> '&#x6b;&#x72;'
			),
			'NPR' => array(
				'name'		=> __( 'NPR - Nepalese Rupee', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'NZD' => array(
				'name'		=> __( 'NZD - New Zealand Dollar', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			
			/* ### O ### */
			'OMR' => array(
				'name'		=> __( 'OMR - Omani Rial', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
	
			/* ### P ### */
			'PAB' => array(
				'name'		=> __( 'PAB - Panamanian Balboa', 'wpcasa' ),
				'symbol'	=> 'B/.',
				'hex'		=> '&#x42;&#x2f;&#x2e;'
			),
			'PEN' => array(
				'name'		=> __( 'PEN - Peruvian Nuevo Sol', 'wpcasa' ),
				'symbol'	=> 'S/.',
				'hex'		=> '&#x53;&#x2f;&#x2e;'
			),
			'PGK' => array(
				'name'		=> __( 'PGK - Papua New Guinean Kina', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'PHP' => array(
				'name'		=> __( 'PHP - Philippine Peso', 'wpcasa' ),
				'symbol'	=> '₱',
				'hex'		=> '&#x20b1;'
			),
			'PKR' => array(
				'name'		=> __( 'PKR - Pakistani Rupee', 'wpcasa' ),
				'symbol'	=> '₨',
				'hex'		=> '&#x20a8;'
			),
			'PLN' => array(
				'name'		=> __( 'PLN - Polish Zloty', 'wpcasa' ),
				'symbol'	=> 'zł',
				'hex'		=> '&#x7a;&#x142;'
			),
			'PYG' => array(
				'name'		=> __( 'PYG - Paraguayan Guarani', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
	
			/* ### Q ### */
			'QAR' => array(
				'name'		=> __( 'QAR - Qatari Rial', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### R ### */
			'RON' => array(
				'name'		=> __( 'RON - Romanian New Lei', 'wpcasa' ),
				'symbol'	=> 'lei',
				'hex'		=> '&#x6c;&#x65;&#x69;'
			),
			'RSD' => array(
				'name'		=> __( 'RSD - Serbian Dinar', 'wpcasa' ),
				'symbol'	=> 'RSD',
				'hex'		=> '&#x52;&#x53;&#x44;'
			),
			'RUB' => array(
				'name'		=> __( 'RUB - Russian Ruble', 'wpcasa' ),
				'symbol'	=> 'руб',
				'hex'		=> '&#x440;&#x443;&#x431;'
			),
			
			/* ### S ### */
			'SAR' => array(
				'name'		=> __( 'SAR - Saudi Riyal', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'SCR' => array(
				'name'		=> __( 'SCR - Seychellois Rupee', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'SEK' => array(
				'name' => __( 'SEK - Swedish Krona', 'wpcasa' ),
				'symbol'=> 'kr',
				'hex'=> '&#x6b;&#x72;'
			),
			'SGD' => array(
				'name'		=> __( 'SGD - Singapore Dollar', 'wpcasa' ),
				'symbol'	=> 'S$',
				'hex'		=> '&#x53;&#x24;'
			),
			'SKK' => array(
				'name'		=> __( 'SKK - Slovak Koruna', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'SLL' => array(
				'name'		=> __( 'SLL - Sierra Leonean Leone', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'SVC' => array(
				'name'		=> __( 'SVC - Salvadoran Colon', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### T ### */
			'THB' => array(
				'name'		=> __( 'THB - Thai Baht', 'wpcasa' ),
				'symbol'	=> '฿',
				'hex'		=> '&#xe3f;'
			),
			'TND' => array(
				'name'		=> __( 'TND - Tunisian Dinar', 'wpcasa' ),
				'symbol'	=> 'DT',
				'hex'		=> '&#x44;&#x54;'
			),
			'TRY' => array(
				'name'		=> __( 'TRY - Turkish Lira', 'wpcasa' ),
				'symbol'	=> 'TL',
				'hex'		=> '&#x54;&#x4c;'
			),
			'TTD' => array(
				'name'		=> __( 'TTD - Trinidad and Tobago Dollar', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'TWD' => array(
				'name'		=> __( 'TWD - New Taiwan Dollar', 'wpcasa' ),
				'symbol'	=> 'NT$',
				'hex'		=> '&#x4e;&#x54;&#x24;'
			),
			
			'TZS' => array(
				'name'		=> __( 'TZS - Tanzanian Shilling', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### U ### */
			'UAH' => array(
				'name'		=> __( 'UAH - Ukrainian Hryvnia', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'UGX' => array(
				'name'		=> __( 'UGX - Ugandan Shilling', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'USD' => array(
				'name'		=> __( 'USD - US Dollar', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'UYU' => array(
				'name'		=> __( 'UYU - Uruguayan Peso', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'UZS' => array(
				'name'		=> __( 'UZS - Uzbekistan Som', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			
			/* ### V ### */
			'VEF' => array(
				'name'		=> __( 'VEF - Venezuelan Bolivar Fuerte', 'wpcasa' ),
				'symbol'	=> 'Bs',
				'hex'		=> '&#x42;&#x73;'
			),
			'VND' => array(
				'name'		=> __( 'VND - Vietnamese Dong', 'wpcasa' ),
				'symbol'	=> '₫',
				'hex'		=> '&#x20ab;'
			),
			
			/* ### W ### */
			
			/* ### X ### */
			'XAF' => array(
				'name'		=>  __( 'XAF - CFA Franc BEAC', 'wpcasa' ),
				'symbol'	=> 'FCFA',
				'hex'		=> '&#x46;&#x43;&#x46;&#x41;'
			),
			'XCD' => array(
				'name'		=>  __( 'XCD - East Caribbean Dollar', 'wpcasa' ),
				'symbol'	=> '$',
				'hex'		=> '&#x24;'
			),
			'XOF' => array(
				'name'		=>  __( 'XOF - CFA Franc BCEAO', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'XPF' => array(
				'name'		=>  __( 'XPF - CFP Franc', 'wpcasa' ),
				'symbol'	=> 'F',
				'hex'		=> '&#x46;'
			),
	
			/* ### Y ### */
			'YER' => array(
				'name'		=> __( 'YER - Yemeni Rial', 'wpcasa' ),
				'symbol'	=> '﷼',
				'hex'		=> '&#xfdfc;'
			),
			
			/* ### Z ### */
			'ZAR' => array(
				'name'		=> __( 'ZAR - South African Rand', 'wpcasa' ),
				'symbol'	=> 'R',
				'hex'		=> '&#x52;'
			),
			'ZMK' => array(
				'name'		=> __( 'ZMK - Zambian Kwacha', 'wpcasa' ),
				'symbol'	=> '',
				'hex'		=> ''
			),
			'ZWD' => array(
				'name'		=> __( 'ZWD - Zimbabwe Dollar', 'wpcasa' ),
				'symbol'	=> 'Z$',
				'hex'		=> '&#x5a;&#x24;'
			)
					
		);
		
		return $currencies;
		
	}
	
	//Return Currency Name
	function get_currency_name( $currency_code ) {
		if ( !empty( $this->currencies[$currency_code]['name'] ) )
			return ( string ) $this->currencies[$currency_code]['name'];
		else
			return '';
	}

	//Return Currency Symbol
	function get_currency_symbol( $currency_code ) {
		if ( !empty( $this->currencies[$currency_code]['symbol'] ) )
			return ( string ) $this->currencies[$currency_code]['symbol'];
		else
			return '';
	}

	//Return Currency Symbol in HEX
	function get_currency_symbol_hex( $currency_code ) {
		if ( !empty( $this->currencies[$currency_code]['hex'] ) )
			return ( string ) $this->currencies[$currency_code]['hex'];
		else
			return '';
	}

}