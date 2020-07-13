<?php
/**
 * Functions for adding and supporting the
 * weekly email editor options page
 */
namespace Coronavirus\Utils\Includes\OptionsWeeklyEmail;


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
			'page_title' 	  => 'Weekly Email Builder',
			'menu_title'	  => 'Weekly Email Builder',
			'menu_slug' 	  => 'weekly-email',
			'post_id'         => 'options_weekly_email',
			'capability'	  => 'administrator',
			'icon_url'        => 'dashicons-email-alt',
			'redirect'        => false,
			'updated_message' => 'Email Options Updated'
		) );
	}
}

add_action( 'acf/init', __NAMESPACE__ . '\add_options_page' );
