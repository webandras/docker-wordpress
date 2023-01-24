<?php
/*Plugin Name: bS5 Post / Page Grid / List 5
Plugin URI: https://bootscore.me/plugins/bs-post-page-grid-list/
Description: Displays posts from category or child pages from parent id in your post or page via shortcode. Post Grid [bs-post-grid type="post" category="documentation, category-default" order="ASC" orderby="title" posts="6"], Post List [bs-post-list type="post" category="documentation, category-default" order="DESC" orderby="date" posts="6"], Child Page Grid [bs-post-grid type="page" post_parent="413" order="ASC" orderby="title" posts="6"], Child Page List [bs-post-list type="page" post_parent="413" order="DESC" orderby="date"]
Version: 5.0.0.1
Author: Bastian Kreiter
Author URI: https://crftwrk.de
License: GPLv2
*/


/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/bs5-post-page-grid-list/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/bs5-post-page-grid-list/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 							Path to the template file.
 */
function bs_post_page_grid_list_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	// Set variable to search in bs5-post-page-grid-list folder of theme.
	if ( ! $template_path ) :
		$template_path = 'bs5-post-page-grid-list/';
	endif;

	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = plugin_dir_path(__FILE__) . 'templates/'; // Path to the template folder
	endif;

	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );

	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	return apply_filters( 'bs_post_page_grid_list_locate_template', $template, $template_name, $template_path, $default_path );

}


/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see bs_post_page_grid_list_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function bs_post_page_grid_list_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;

	$template_file = bs_post_page_grid_list_locate_template( $template_name, $tempate_path, $default_path );

	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;

	include $template_file;

}


/**
 * Templates.
 *
 * This function will output the templates
 * file from the /templates.
 *
 * @since 1.0.0
 */

function bs_post_page_grid() {

	return bs_post_page_grid_list_get_template( 'grid.php' );

}
add_action('wp_head', 'bs_post_page_grid');


function bs_post_page_list() {

    return bs_post_page_grid_list_get_template( 'list.php' );

}
add_action('wp_head', 'bs_post_page_list');
