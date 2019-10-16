<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Gutenberg class
 */
class WPSight_Gutenberg {
	

	/**
	 * Constructor
	 */
	public function __construct() {

		add_filter( 'wpsight_post_type_args_listing',	array( $this, 'args' ), 11 );
		add_filter( 'wpsight_taxonomy_locations_args',	array( $this, 'args' ), 11 );
		add_filter( 'wpsight_taxonomy_types_args',		array( $this, 'args' ), 11 );
		add_filter( 'wpsight_taxonomy_features_args',	array( $this, 'args' ), 11 );
		add_filter( 'wpsight_taxonomy_categories_args',	array( $this, 'args' ), 11 );

	}
	
	public function args( $args ) {
		
		$args['show_in_rest'] = true;
		return $args;
		
	}
	
}