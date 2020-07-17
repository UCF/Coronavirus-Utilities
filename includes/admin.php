<?php
/**
 * Admin-related functions/overrides
 */
namespace Coronavirus\Utils\Includes\Admin;
use Coronavirus\Utils\Includes\OptionsWeeklyEmail;


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
	$toolbars['Email Content'][1] = array( 'bold', 'italic', 'link', 'unlink', 'bullist', 'numlist', 'undo', 'redo' );
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

	// Paragraph field
	if (fieldID === 'field_5ec43f369be15') {
		mceInit.valid_elements = '-a[href],-strong/b,-em,-p,-ul,-ol,-li,-sup,-sub';
	}
	// Article deck field
	else if (fieldID === 'field_5ec443edb99ec') {
		mceInit.valid_elements = '-a[href],-strong/b,-em,-sup,-sub';
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
	$menu_id        = OptionsWeeklyEmail\menu_slug();
	$current_screen = get_current_screen();

	if (
		$current_screen
		&& $current_screen->id === "toplevel_page_$menu_id"
		&& $editor_id === 'acf_content'
	) {
		$qt_init['buttons'] = ',';
	}

	return $qt_init;
}

add_filter( 'quicktags_settings', __NAMESPACE__ . '\acf_wysiwyg_quicktags_toolbar', 10, 2 );
