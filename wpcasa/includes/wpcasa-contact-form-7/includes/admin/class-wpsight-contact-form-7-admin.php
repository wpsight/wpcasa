<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Contact_Form_7_Admin class
 */
class WPSight_Contact_Form_7_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Add add-on options to general plugin settings
		add_filter( 'wpsight_options', array( $this, 'options' ) );

	}

	/**
	 *	options()
	 *
	 *	Add add-on options tab to
	 *	general plugin settings.
	 *
	 *	@param	array	Incoming plugin options
	 *	@uses	WPCF7_ContactForm::find()
	 *	@uses	wpsight_get_option()
	 *	@return array	Updated options array
	 *
	 *	@since 1.0.0
	 */
	public function options( $options ) {

		// Prepare forms option
		$forms = array( '' => __( 'None', 'wpcasa-contact-form-7' ) );

		foreach ( WPCF7_ContactForm::find() as $key => $form ) {
			$id = $form->id();
			$forms[ $id ] = $form->title();
		}

		$options_cf7 = array(

			'contact_form_7_listing_form_id' => array(
				'name'		=> __( 'Listing Form', 'wpcasa-contact-form-7' ),
				'desc'		=> __( 'Select the form that you want to use on listing pages.', 'wpcasa-contact-form-7' ),
				'id'		=> 'contact_form_7_listing_form_id',
				'type'		=> 'select',
				'options'	=> $forms
			)

		);
		
		$form_id = wpsight_get_option( 'contact_form_7_listing_form_id' );
		
		if( $form_id ) {
		
			$options_cf7['contact_form_7_listing_form_display'] = array(
				'name'		=> __( 'Form Display', 'wpcasa-contact-form-7' ),
				'desc'	=> __( 'Select where to display the listing form or choose to manually add the form via shortcode or function.', 'wpcasa-contact-form-7' ),
				'id'		=> 'contact_form_7_listing_form_display',
				'type'		=> 'select',
				'options'	=> array(
					'wpsight_listing_single_after'				=> __( 'At the end', 'wpcasa-contact-form-7' ),
					'wpsight_listing_single_details_after'		=> __( 'After details', 'wpcasa-contact-form-7' ),
					'wpsight_listing_single_description_after'	=> __( 'After description', 'wpcasa-contact-form-7' ),
					'wpsight_listing_single_features_after'		=> __( 'After features', 'wpcasa-contact-form-7' ),
					'wpsight_listing_single_location_after'		=> __( 'After location', 'wpcasa-contact-form-7' ),
					'wpsight_listing_single_agent_after'		=> __( 'After agent', 'wpcasa-contact-form-7' ),
					''											=> __( 'Do not display', 'wpcasa-contact-form-7' )
				)
			);
			
		}
		
		$options_cf7['contact_form_7_listing_form_css'] = array(
			'name'		=> __( 'Form CSS', 'wpcasa-contact-form-7' ),
			'cb_label'	=> __( 'Please uncheck the box to disable the plugin from outputting CSS.', 'wpcasa-contact-form-7' ),
			'id'		=> 'contact_form_7_listing_form_css',
			'type'		=> 'checkbox'
		);

		$options['contact_form_7'] = array(
			__( 'Contact Form 7', 'wpcasa-contact-form-7' ),
			apply_filters( 'wpsight_options_contact_form_7', $options_cf7 )
		);

		return $options;

	}

}
