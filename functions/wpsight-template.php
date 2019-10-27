<?php
/**
 *	WPSight template functions
 *	
 *	@package WPSight \ Functions
 */

/**
 *	wpsight_get_template()
 *	
 *	Load specific template file.
 *	
 *	@param	string|array	$template_names	Template name (incl. file extension like .php)
 *	@param	string			$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool			$load			Call load_template() if true or return template path if false
 *	@uses	wpsight_locate_template()
 *	@return	string			$located		Absolute path to template file (if $load is false)
 *	
 *	@since 1.0.0
 */
function wpsight_get_template( $template_names, $args = array(), $template_path = '', $load = true, $require_once = false ) {
	
	// Execute code for this template
	do_action( 'wpsight_get_template', $template_names, $args, $template_path, $load, $require_once );
	
	wpsight_locate_template( $template_names, $args, $template_path, $load, $require_once );

}

/**
 *	wpsight_locate_template()
 *	
 *	Locate a template and return the path
 *	for inclusion or load if desired.
 *	
 *	This is the load order:
 *	
 *	 	/wp-content/themes/		theme (child)	/										$template_name
 *	
 *	 	/wp-content/themes/		theme (parent)	/										$template_name
 *	 	
 *	 	$template_path (custom path from addon for example) 						/	$template_name
 *	 	
 *	 	/wp-content/plugins/		WPSIGHT_DOMAIN (e.g. wpcasa)	/	templates	/	$template_name
 *	
 *	@param	string|array	$template_names	Template name (incl. file extension like .php)
 *	@param	string			$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool			$load			Call load_template() if true or return template path if false
 *	@uses	WPSight_Template::locate_template()
 *	@return	string			$located		Absolute path to template file (if $load is false)
 *	
 *	@since 1.0.0
 */
function wpsight_locate_template( $template_names, $args = array(), $template_path = '', $load = false, $require_once = false ) {
	return WPSight_Template::locate_template( $template_names, $args, $template_path, $load, $require_once );
}

/**
 *	wpsight_get_template_part()
 *	
 *	Load specific template part.
 *	
 *	@param	string	$slug			The slug name for the generic template
 *	@param	string	$name			The name of the specialized template
 *	@param	string	$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool	$load			Call load_template() if true or return template path if false
 *	@return	string	$located		Absolute path to template file (if $load is false)
 *	
 *	@since 1.0.0
 */
function wpsight_get_template_part( $slug, $name = null, $args = array(), $template_path = '', $load = true, $require_once = false ) {
	return WPSight_Template::get_template_part( $slug, $name, $args, $template_path, $load, $require_once );
}

/**
 *	wpsight_get_templates_dir()
 *	
 *	Return path to WPSIGHT_DOMAIN (e.g. wpcasa)
 *	templates directory.
 *	
 *	@return	string
 *	
 *	@since 1.0.0
 */
function wpsight_get_templates_dir() {
	return WPSIGHT_PLUGIN_DIR . '/templates/';
}

/**
 *	wpsight_get_templates_url()
 *	
 *	Return URL to WPSIGHT_DOMAIN (e.g. wpcasa)
 *	templates directory.
 *	
 *	@return	string
 *	
 *	@since 1.0.0
 */
function wpsight_get_templates_url() {
	return WPSIGHT_PLUGIN_URL . '/templates/';
}

/**
 *	wpsight_orderby()
 *	
 *	Echo wpsight_get_orderby()
 *	
 *	@param	array	$args	Array of arguments
 *	@uses	wpsight_get_orderby()
 *	
 *	@since 1.0.0
 */
function wpsight_orderby( $args = array() ) {
	echo wpsight_get_orderby( $args );		
}

/**
 *	wpsight_get_orderby()
 *	
 *	Return orderby options for listing pages
 *	
 *	@param	array		$args		Array of arguments
 *	@return string|bool	$orderby	HTML output or false if empty
 *	
 *	@since 1.0.0
 */
function wpsight_get_orderby( $args = array() ) {
	return WPSight_Template::get_orderby( $args );
}

/**
 *	wpsight_get_panel()
 *	
 *	Return formatted listings control panel (with title and order options)
 *	
 *	@param	array 		$args	Array of arguments
 *	@uses	WPSight_Template::get_panel()
 *	@return	string|bool	HTML markup of listings panel or false if empty
 *	
 *	@since 1.0.0
 */
function wpsight_get_panel( $args = array() ) {
	return WPSight_Template::get_panel( $args );
}

/**
 *	wpsight_panel()
 *	
 *	Echo wpsight_get_panel()
 *	
 *	@param	array	$args	Array of arguments
 *	@uses wpsight_get_panel()
 *	
 *	@since 1.0.0
 */
function wpsight_panel( $args = array() ) {	
	echo wpsight_get_panel( $args );
}

/**
 *	wpsight_get_listing_title()
 *	
 *	Return formatted single listing
 *	title with title actions.
 *	
 *	@param	integer	$post_id	Post ID of specific listing
 *	@param	array	$actions	Array of listing title actions
 *	@uses	WPSight_Template::get_listing_title()
 *	@return	string	HTML markup of listing title
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_title( $post_id = '', $actions = array() ) {
	return WPSight_Template::get_listing_title( $post_id, $actions );
}

/**
 *	wpsight_listing_title()
 *	
 *	Echo wpsight_get_listing_title()
 *	
 *	@param	integer	$post_id	Post ID of specific listing
 *	@param	array	$actions	Array of listing title actions
 *	@uses	wpsight_get_listing_title()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_title( $post_id = '', $actions = array() ) {	
	echo wpsight_get_listing_title( $post_id, $actions );
}

/**
 *	wpsight_get_archive_title()
 *	
 *	Return formatted listings archive title
 *	
 *	@uses	WPSight_Template::get_archive_title()
 *	@return	string	Listings archive title for the given query
 *	
 *	@since 1.0.0
 */
function wpsight_get_archive_title() {
	return WPSight_Template::get_archive_title();
}

/**
 *	wpsight_archive_title()
 *	
 *	Echo wpsight_get_archive_title()
 *	
 *	@uses	wpsight_get_archive_title()
 *	
 *	@since 1.0.0
 */
function wpsight_archive_title() {	
	echo wpsight_get_archive_title();
}

/**
 *	wpsight_get_pagination()
 *	
 *	Return formatted pagination
 *	
 *	@param	int			$max_num_pages	max_num_pages parameter of corresponding query
 *	@param	array		$args			paginate_links() arguments
 *	@uses WPSight_Template::get_pagination()
 *	@return string|bool	HTML markup of pagination or false if empty
 *	
 *	@since 1.0.0
 */
function wpsight_get_pagination( $max_num_pages = '', $args = array() ) {
	return WPSight_Template::get_pagination( $max_num_pages, $args );
}

/**
 *	wpsight_pagination()
 *	
 *	Echo wpsight_get_pagination()
 *	
 *	@param	int		$max_num_pages	max_num_pages parameter of corresponding query
 *	@param	array 	$args			paginate_links() arguments
 *	@uses wpsight_get_pagination()
 *	
 *	@since 1.0.0
 */
function wpsight_pagination( $max_num_pages, $args = array() ) {	
	echo wpsight_get_pagination( $max_num_pages, $args );
}

/**
 *	wpsight_listing_class()
 *	
 *	Display listing/post classes.
 *	
 *	@param	string	$class 	Additional CSS class
 *	@param	mixed	$post_id
 *	@uses	wpsight_get_listing_class()
 *	
 *	@since 1.0.0
 */
function wpsight_listing_class( $class = '', $post_id = false ) {
	echo 'class="' . join( ' ', wpsight_get_listing_class( $class, $post_id ) ) . '"';
}

/**
 *	wpsight_get_listing_class()
 *	
 *	Get listing/post classes.
 *	
 *	@param	string	$class
 *	@param	mixed	$post_id
 *	@uses	WPSight_Template::get_listing_class()
 *	
 *	@return bool|array
 */
function wpsight_get_listing_class( $class = '', $post_id = false ) {
	return WPSight_Template::get_listing_class( $class, $post_id );
}

/**
 *	wpsight_get_listing_actions()
 *	
 *	Get listing actions (save, print etc.) array.
 *	
 *	@param	integer	$post_id	Post ID of specific listing
 *	@uses	WPSight_Template::get_listing_actions()
 *	@return	array	Array of listing actions
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_actions( $post_id = '' ) {
	return WPSight_Template::get_listing_actions( $post_id );
}

/**
 *	wpsight_listing_actions()
 *
 *  Return link to listing actions
 *
 *  @param	string	$post_id
 *  @param	array	$actions
 *  @uses	WPSight_Template::listing_actions()
 *  @return	string
 *
 *	@since 1.0.0
 */
function wpsight_listing_actions( $post_id = '', $actions = array() ) {
	echo WPSight_Template::listing_actions( $post_id, $actions );
}
