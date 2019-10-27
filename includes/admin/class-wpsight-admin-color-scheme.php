<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Color_Scheme class
 */
class WPSight_Admin_Color_Scheme {
	
	/**
	 *	Constructor
	 */
	public function __construct() {
		
		add_action( 'admin_init',			array( $this, 'set_color_scheme' ),	10 );

		add_action( 'admin_head',			array( $this, 'get_color_scheme' ) );
		add_action( 'admin_head',			array( $this, 'get_color_scheme_css' ) );
		
	}
	
	/**
	 *	set_color_scheme()
	 *	
	 *	Add custom admin color scheme
	 *	
	 *	@uses	wp_admin_css_color()
	 *	
	 *	@since 1.1.0
	 */
	public static function set_color_scheme() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		
		wp_admin_css_color(
			'wpcasa',
			 __( 'WPCasa' ),
			WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-admin-color-scheme' . $suffix . '.css',
			array( '#222','#333','#0F8E75','#12AE8F' )
		);
		
	}
		
	/**
	 *	get_color_scheme()
	 *
	 *	@uses	get_user_option()
	 *	@uses	get_current_user_id()
	 *
	 *	@since 1.1.0
	 */
	public function get_color_scheme() {
		
		global $_wp_admin_css_colors;
		$current_color = get_user_option( 'admin_color', get_current_user_id() );
			
		return $_wp_admin_css_colors[$current_color];
				
	}
	
	/**
	 *	get_color_scheme_css()
	 *
	 *	@uses	get_color_scheme()
	 *
	 *	@since 1.1.0
	 */
	public function get_color_scheme_css() {
		
		$scheme = $this->get_color_scheme();
		
		$color_1	= $scheme->colors[0];
		$color_2	= $scheme->colors[1];
		$color_3	= $scheme->colors[2];
		$color_4	= $scheme->colors[3];
		
		echo '<style type="text/css" id="wpsight-settings-css">
		
		.wpsight-settings-page .wpsight-settings-wrap .wpsight-admin-ui-heading .wpsight-admin-ui-heading-title .wpsight-admin-ui-icon {
			background: ' . $color_4 . ';
		}		
		
		.wpsight-settings-field-radio input[type="radio"]:checked + .label-radio:before {
			background: ' . $color_4 . ';
		}
		
		.wpsight-settings-field-radio input[type="radio"]:checked + .label-radio {
			background: ' . $color_4 . ';
			border-color: ' . $color_4 . ';
		}
		
		.wpsight-settings-field-radio input[type="radio"] + .label-radio:after {
			border-color: ' . $color_4 . ';
		}
		
		.wpsight-settings-field-multicheck input[type="checkbox"]:checked + .label-checkbox {
			background: ' . $color_4 . ';
			border-color: ' . $color_4 . ';
		}
		
		.wpsight-settings-field-multicheck input[type="checkbox"]:checked + .label-checkbox:before {
			background: ' . $color_4 . ';
		}
				
		.wpsight-settings-field-range .range-slider__range::-webkit-slider-thumb {
			background: ' . $color_4 . ';
		}
		
		.wpsight-settings-field-range .range-slider__range::-webkit-slider-thumb:hover {
			background: ' . $color_3 . ';
		}
		
		.wpsight-settings-field-range .range-slider__range:focus::-webkit-slider-thumb,
		.wpsight-settings-field-range .range-slider__range:active::-webkit-slider-thumb {
			background: ' . $color_3 . ';
		}
		
		.wpsight-settings-field-range .range-slider__range::-moz-range-thumb {
			background: ' . $color_4 . ';
		}
		
		.wpsight-settings-field-range .range-slider__range::-moz-range-thumb:hover {
			background: ' . $color_3 . ';
		}
		
		.wpsight-settings-field-range .range-slider__range:active::-moz-range-thumb {
			background: ' . $color_3 . ';
		}
		
		.wpsight-settings-field-checkbox .switch input[type="checkbox"]:checked + .label-checkbox,
		.wpsight-settings-field-checkbox .switch input[type="checkbox"]:checked + .label-checkbox:before {
			border-color: ' . $color_4 . ';
		}

		.wpsight-settings-field-checkbox .switch input[type="checkbox"]:checked + .label-checkbox {
			background-color: ' . $color_4 . ';
		}
		
		.cmb2-element .ui-datepicker .ui-datepicker-header,
		.cmb2-element .ui-datepicker .ui-widget-header,
		.cmb2-element.ui-datepicker .ui-datepicker-header,
		.cmb2-element.ui-datepicker .ui-widget-header,
		.cmb2-element .ui-datepicker td .ui-state-active,
		.cmb2-element .ui-datepicker td .ui-state-hover,
		.cmb2-element.ui-datepicker td .ui-state-active,
		.cmb2-element.ui-datepicker td .ui-state-hover {
			background: ' . $color_4 . ';
		}
		
		</style>';
	  
	}
	
}