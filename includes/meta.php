<?php
/**
 * Functions related to metadata and static asset enqueuing
 */
namespace Coronavirus\Utils\Includes\Meta;
use Coronavirus\Utils\Includes\OptionsWeeklyEmail;


/**
 * Returns a cache-busting param for use when enqueuing
 * this plugin's static assets in the WordPress admin.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return string
 */
function get_asset_cache_bust() {
	$cache_bust = '';

	// If debug mode is enabled, force editor stylesheets to
	// reload on every page refresh.  Caching of these stylesheets
	// is very aggressive
	if ( WP_DEBUG === true ) {
		$cache_bust = date( 'YmdHis' );
	}
	else {
		$plugin = get_plugin_data( CORONAVIRUS_UTILS__PLUGIN_FILE );
		if ( isset( $plugin['Version'] ) ) {
			$cache_bust = $plugin['Version'];
		}
		else {
			$cache_bust = date( 'Ymd' );
		}
	}

	return $cache_bust;
}


/**
 * Enqueue TinyMCE styles for the email editor
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return void
 */
function email_wysiwyg_styles() {
	// get_current_screen() returns null on this hook,
	// so sniff the request URI instead when is_admin() is true
	if ( is_admin() ) {

		// Enqueue Email WYSIWYG stylesheet on email editor screen
		if (
			stristr( $_SERVER['REQUEST_URI'], 'admin.php' ) !== false
			&& isset( $_GET['page'] )
			&& $_GET['page'] === OptionsWeeklyEmail\menu_slug()
		) {
			// Remove any existing editor stylesheets, if present
			remove_editor_styles();
			// Add our styles
			$cache_bust = get_asset_cache_bust();
			add_editor_style( CORONAVIRUS_UTILS__PLUGIN_CSS_URL . 'editor-email.min.css?v=' . $cache_bust );
		}

	}
}

add_action( 'init', __NAMESPACE__ . '\email_wysiwyg_styles', 99 ); // Enqueue late to ensure logic happens after Athena SC Plugin (if activated)


/**
 * Enqueue admin styles/scripts
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $hook Current admin page
 * @return void
 */
function admin_enqueue_scripts( $hook ) {
	if ( $hook === OptionsWeeklyEmail\screen_id() ) {
		// Enqueue the sanitize-html lib
		wp_enqueue_script( 'coronavirus_utils__sanitizehtml', CORONAVIRUS_UTILS__PLUGIN_JS_URL . 'sanitize-html.min.js' );
	}
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );
