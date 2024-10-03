<?php
/**
 * Functions for adding and supporting the
 * weekly email editor options page
 */
namespace Coronavirus\Utils\Includes\OptionsWeeklyEmail;


/**
 * Returns the menu slug for the email editor's options page.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return string
 */
function menu_slug() {
	return 'weekly-email';
}


/**
 * Returns the screen ID for the email editor's options page.
 * This value equates to the `id` property of a `WP_Screen`
 * object, as well as the `$hook_suffix` param available in the
 * `admin_enqueue_scripts` hook.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return string
 */
function screen_id() {
	$menu_slug = menu_slug();
	return "toplevel_page_$menu_slug";
}


/**
 * Registers the options page for editing the
 * weekly emails.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function add_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' 	  => 'ICYMI Email Builder',
			'menu_title'	  => 'ICYMI Email Builder',
			'menu_slug' 	  => menu_slug(),
			'post_id'         => 'options_weekly_email',
			'capability'	  => 'administrator',
			'icon_url'        => 'dashicons-email-alt',
			'redirect'        => false,
			'updated_message' => 'Email Options Updated'
		) );
	}
}

add_action( 'acf/init', __NAMESPACE__ . '\add_options_page' );

if ( function_exists( 'pantheon_clear_edge_paths' ) ) {
	function on_options_page_save( $post_id, $menu_slug ) {
		if ( $post_id === 'options_weekly_email' ) {
			pantheon_clear_edge_paths( [ '/icymi/mail/' ] );
		}
	}

	add_action( 'acf/options_page/save', __NAMESPACE__ . '\on_options_page_save', 10, 2 );
}
