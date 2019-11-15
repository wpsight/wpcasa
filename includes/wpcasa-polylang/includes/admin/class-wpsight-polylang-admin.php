<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Polylang_Admin class
 */
class WPSight_Polylang_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Sync some meta values
		add_action( 'updated_post_meta', array( $this, 'updated_post_meta' ), 10, 4 );

		// Add new field to maintain default description
		// add_filter( 'wpsight_meta_box_user_fields', array( $this, 'user_fields' ) );

		// Set agent description for each language
		add_action( 'wpsight_profile_agent_update_save_options', array( $this, 'agent_description_options' ), 10, 2 );

		// Correctly save the agent descriptions
		add_filter( 'wpsight_profile_agent_update_post_meta', array( $this, 'agent_description_post_meta' ), 10, 4 );

		// Set agent description default in listing editor
		add_filter( 'wpsight_meta_box_listing_agent_fields', array( $this, 'listing_agent_description' ) );

		// Remove agent desription from copied post meta
		add_filter( 'pll_copy_post_metas', array( $this, 'copy_post_metas' ) );

		// Get translated listing images
		add_filter( 'wpsight_meta_box_listing_images_fields', array( $this, 'listing_images' ) );

	}

	/**
	 *	updated_post_meta()
	 *
	 *	Sync some meta values between
	 *	listing translations. Important
	 *	for changes through action buttons.
	 *
	 *	@access	public
	 *	@param	integer	$meta_id
	 *	@param	integer	$object_id
	 *	@param	string	$meta_key
	 *	@param	string	$_meta_value
	 *	@uses	wpsight_post_type()
	 *	@uses	$polylang->model->get_translations()
	 *	@uses	update_post_meta()
	 *
	 *	@since 1.0.0
	 */
	public function updated_post_meta( $meta_id, $object_id, $meta_key, $_meta_value ) {
		global $polylang;

		// Set meta keys to be updated

		$update_meta = array(
			'_listing_sticky',
			'_listing_featured',
			'_listing_expires',
			'_listing_not_available'
		);

		// Check if one of them is updated

		if( in_array( $meta_key, $update_meta ) ) {

			// Get all translations of current listing
			$post_ids = $polylang->model->get_translations( wpsight_post_type(), $object_id );

			// Update all translations
			foreach( $post_ids as $post_id )
				update_post_meta( $post_id, $meta_key, $_meta_value );

		}

	}

	/**
	 *	user_fields()
	 *
	 *	Let's save the $_POST['description']
	 *	value that seems to be removed
	 *	by Polylang.
	 *
	 *	@access	public
	 *	@param	array	$fields
	 *
	 *	@since 1.0.0
	 */
	public function user_fields( $fields ) {

		$fields['agent_description'] = array(
			'name'	=> false,
			'desc'  => false,
			'id'    => 'description',
			'type'  => 'hidden'
		);

		return $fields;

	}

	/**
	 *	agent_description_options()
	 *
	 *	Correctly update agent description
	 *	for all registered languages to
	 *	be available in our listing editor.
	 *
	 *	@access	public
	 *	@param	array	$agent_options
	 *	@param	integer	$user_id
	 *	@uses	pll_languages_list()
	 *
	 *	@since 1.0.0
	 */
	public function agent_description_options( $agent_options, $user_id ) {

		// Set descriptions in all languages

		foreach( pll_languages_list() as $lang )
			$agent_options[ '_agent_description_' . $lang ] = trim( $_POST[ 'description_' . $lang ] );

		return $agent_options;

	}

	/**
	 *	agent_description_post_meta()
	 *
	 *	When agent information on profile
	 *	is updated for all listings, make
	 *	sure to set the correc description
	 *	in the corresponding post language.
	 *
	 *	@access	public
	 *	@param	string	$getpost		The value set using update_post_meta
	 *	@param	string	$option			The option key
	 *	@param	integer	$post_id		The post ID of the updated listing
	 *	@param	array	$agent_options	The entire array of agent options
	 *	@uses	pll_get_post_language()
	 *
	 *	@since 1.0.0
	 */
	public function agent_description_post_meta( $getpost, $option, $post_id, $agent_options ) {

		// Get post language
		$post_lang = pll_get_post_language( $post_id );

		// Get agent description for language
		$description_lang = $agent_options[ '_agent_description_' . $post_lang ];

		// Set agent description accordingly

		if( '_agent_description' == $option && isset( $description_lang ) )
			$getpost = $description_lang;

		return $getpost;

	}

	/**
	 *	listing_agent_description()
	 *
	 *	Set the default agent description
	 *	by callback function to get post
	 *	ID first.
	 *
	 *	@access	public
	 *	@param	array	$fields
	 *
	 *	@since 1.0.0
	 */
	public function listing_agent_description( $fields ) {

		// Get post lang early
		$new_lang = isset( $_REQUEST['new_lang'] ) ? $_REQUEST['new_lang'] : false;

		if( $new_lang ) {
			// Set default value of desription
			$fields['description']['default'] = array( $this, 'listing_agent_description_default' );
		}

		return $fields;

	}

	/**
	 *	listing_agent_description_default()
	 *
	 *	Set the default agent description
	 *	depending on the post language if
	 *	already set.
	 *
	 *	@access	public
	 *	@param	array	$field_args
	 *	@param	object	$field
	 *	@uses	pll_get_post_language()
	 *	@uses	wp_get_current_user()
	 *	@uses	get_user_meta()
	 *	@return	string	$description
	 *
	 *	@since 1.0.0
	 */
	public function listing_agent_description_default( $field_args, $field ) {

		$post_lang = pll_get_post_language( $field->object_id );

		// Get default description
		$description = get_user_meta( wp_get_current_user()->ID, 'description', true );

		if( $post_lang ) {
			// Get description in post language
			$description = get_user_meta( wp_get_current_user()->ID, 'description_' . $post_lang, true );
		}

		return $description;

	}

	/**
	 *	copy_post_metas()
	 *
	 *	Remove agent desription and
	 *	optionally the gallery from
	 *	copied post meta as we will
	 *	set these values ourselves.
	 *
	 *	@access	public
	 *	@param	array	$post_meta
	 *
	 *	@since 1.0.0
	 */
	public function copy_post_metas( $post_meta ) {

		// Don't copy agent description

		if( ( $key_description = array_search( '_agent_description', $post_meta ) ) !== false )
			unset( $post_meta[ $key_description ] );

		// Don't copy image gallery if media translation is enabled

		$options = get_option( 'polylang' );

		$media_support = isset( $options['media_support'] ) && $options['media_support'] ? true : false;

		if( ( $key_gallery = array_search( '_gallery', $post_meta ) ) !== false && $media_support )
			unset( $post_meta[ $key_gallery ] );

		return $post_meta;

	}

	/**
	 *	listing_images()
	 *
	 *	When Polylang media translations
	 *	are enabled, create a callback
	 *	to set the default gallery with
	 *	translated images.
	 *
	 *	@access	public
	 *	@param	array	$fields
	 *	@uses	get_option()
	 *	@return	array
	 *
	 *	@since 1.0.0
	 */
	public function listing_images( $fields ) {

		$options = get_option( 'polylang' );

		$media_support = isset( $options['media_support'] ) && $options['media_support'] ? true : false;

		if( $media_support ) {
			// Set default value of gallery
			$fields['images']['default'] = array( $this, 'listing_images_default' );
		}

		return $fields;

	}

	/**
	 *	listing_images_default()
	 *
	 *	Callback function to set the default
	 *	gallery with translated images if
	 *	these are available.
	 *
	 *	@access	public
	 *	@param	array	$field_args
	 *	@param	object	$field
	 *	@uses	pll_get_post_language()
	 *	@uses	pll_default_language()
	 *	@uses	pll_get_post()
	 *	@uses	get_post_meta()
	 *	@return	array
	 *
	 *	@since 1.0.0
	 */
	public function listing_images_default( $field_args, $field ) {

		// Get post language
		$post_lang = pll_get_post_language( $field->object_id );

		// Get from post early
		$from_post = isset( $_REQUEST['from_post'] ) ? $_REQUEST['from_post'] : false;

		// If from_post is not available anymore, use current post ID

		if( ! $from_post )
			$from_post = $field->object_id;

		// Get post ID of default language
		$origial = pll_get_post( $from_post, pll_default_language() );

		// Get original gallery
		$gallery = get_post_meta( $origial, '_gallery', true );

		if( empty( $gallery ) )
			return;

		// Set up translated gallery
		$gallery_lang = array();

		foreach( $gallery as $id => $url ) {

			// Get ID of image translation
			$id_lang = pll_get_post( $id, $post_lang );

			if( $id_lang )
				// When available, set new ID
				$gallery_lang[ $id_lang ] = $url;

		}

		// If there are image translations, set new gallery default

		if( ! empty( $gallery_lang ) )
			return $gallery_lang;

	}

}
