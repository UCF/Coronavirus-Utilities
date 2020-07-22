<?php
/**
 * Admin-related functions/overrides
 */
namespace Coronavirus\Utils\Includes\Admin;
use Coronavirus\Utils\Includes\OptionsWeeklyEmail;


/**
 *
 */
function is_local_env() {
	// Custom constant used by UCF Webcom for defining a local
	// development environment.
	// This constant is intentionally separate from WP_DEBUG
	// to distinguish between hosted DEV environments that have
	// debug mode enabled.
	if ( defined( 'WP_LOCAL_DEV' ) ) {
		return WP_LOCAL_DEV;
	}
	else {
		return false;
	}
}


/**
 *
 */
function acf_register_fields() {
	if ( ! is_local_env() ) {
		// Get the current plugin version number
		$plugin         = get_plugin_data( CORONAVIRUS_UTILS__PLUGIN_FILE );
		$plugin_version = '';
		if ( isset( $plugin['Version'] ) ) {
			$plugin_version = $plugin['Version'];
		}
		else {
			$plugin_version = date( 'Ymd' );
		}

		// Retrieve our ACF export data from an existing option,
		// or directly from the acf-export.json file in the plugin
		// repo if an existing option isn't already set/if the
		// option value is stale.
		$acf_export_data = get_option( 'coronavirus_utils__acf_config' );
		if (
			! $acf_export_data
			|| (
				isset( $acf_export_data['version'] )
				&& $acf_export_data['version'] !== $plugin_version
			)
		) {
			$acf_json = json_decode( file_get_contents( CORONAVIRUS_UTILS__PLUGIN_DIR . '/dev/acf-export.json' ) ?: array(), true );
			$acf_export_data = array(
				'version'      => $plugin_version,
				'field_groups' => $acf_json
			);
			update_option( 'coronavirus_utils__acf_config', $acf_export_data );
		}

		// Register the field groups
		foreach ( $acf_export_data['field_groups'] as $field_group ) {
			acf_add_local_field_group( $field_group );
		}
	}
}

add_action( 'init', __NAMESPACE__ . '\acf_register_fields' );


/**
 * Defines custom ACF WYSIWYG field toolbars.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $toolbars Array of toolbar information from ACF
 * @return array
 */
function acf_toolbars( $toolbars ) {
	// "Inline Text"
	$toolbars['Inline Text'] = array();
	$toolbars['Inline Text'][1] = array( 'bold', 'italic', 'undo', 'redo' );

	// "Email Content"
	$toolbars['Email Content'] = array();
	$toolbars['Email Content'][1] = array( 'formatselect', 'bold', 'italic', 'link', 'unlink', 'bullist', 'numlist', 'undo', 'redo' );
	return $toolbars;
}

add_filter( 'acf/fields/wysiwyg/toolbars', __NAMESPACE__ . '\acf_toolbars' );


/**
 * Update TinyMCE settings for ACF WYSIWYG fields.
 *
 * @see https://www.tiny.cloud/docs-3x/reference/configuration/Configuration3x@valid_elements/
 * @since 1.0.0
 * @author Jo Dickson
 * @return void
 */
function acf_configure_tinymce() {
?>
<script type="text/javascript">
acf.add_filter('wysiwyg_tinymce_settings', function(mceInit, id, $field){

	var fieldID = $field.data('key');
	var sanitizeHTMLWhitelist = [];

	// Paragraph field
	if (fieldID === 'field_5ec43f369be15') {
		sanitizeHTMLWhitelist = ['a', 'strong', 'em', 'p', 'ul', 'ol', 'li', 'sup', 'sub', 'h2', 'h3'];
		mceInit.valid_elements = '-a[href],-strong/b,-em/i,-p,-ul,-ol,-li,-sup,-sub,-h2,-h3';
		mceInit.block_formats = 'Paragraph=p;Heading 2=h2;Heading 3=h3';
	}
	// Article deck field
	else if (fieldID === 'field_5ec443edb99ec') {
		sanitizeHTMLWhitelist = ['a', 'strong', 'em', 'sup', 'sub'];
		mceInit.valid_elements = '-a[href],-strong/b,-em/i,-sup,-sub';
	}

	if (fieldID === 'field_5ec43f369be15' || fieldID === 'field_5ec443edb99ec') {
		// Pass pasted content through the sanitizehtml lib.
		// Some of this is redundant, but configuring TinyMCE's
		// `valid_elements` doesn't seem to catch all outlying
		// elements or empty tags.
		mceInit.paste_preprocess = function(plugin, args) {
			var contentClean = sanitizeHtml(args.content, {
				allowedTags: sanitizeHTMLWhitelist,
				allowProtocolRelative: false,
				transformTags: {
					'b': 'strong',
					'i': 'em'
				},
				exclusiveFilter: function(frame) {
					return (
						// Strip out empty tags
						!frame.text.trim()
					)
				}
			});
			args.content = contentClean;
		}
	}

	return mceInit;

});
</script>
<?php
}

add_action( 'acf/input/admin_footer', __NAMESPACE__ . '\acf_configure_tinymce' );


/**
 * Remove plaintext editor's quicktags toolbar on
 * all ACF-generated WYSIWYG fields in the email editor
 * interface.
 *
 * NOTE: We narrow down this logic by the current screen because
 * we don't have access to per-field editor options for quicktags.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $qt_init Quicktags toolbar options
 * @param string $editor_id Type of editor (e.g. "content" or "acf_content")
 * @return array Modified quicktags options
 */
function acf_wysiwyg_quicktags_toolbar( $qt_init, $editor_id ) {
	$menu_id        = OptionsWeeklyEmail\screen_id();
	$current_screen = get_current_screen();

	if (
		$current_screen
		&& $current_screen->id === $menu_id
		&& $editor_id === 'acf_content'
	) {
		$qt_init['buttons'] = ',';
	}

	return $qt_init;
}

add_filter( 'quicktags_settings', __NAMESPACE__ . '\acf_wysiwyg_quicktags_toolbar', 10, 2 );
