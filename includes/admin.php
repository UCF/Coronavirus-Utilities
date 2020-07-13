<?php
/**
 * Admin-related functions/overrides
 */
namespace Coronavirus\Utils\Includes\Admin;


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
	$toolbars['Inline Text'][1] = array( 'bold', 'italic', 'link', 'unlink', 'undo', 'redo' );

	// "Email Content"
	$toolbars['Email Content'] = array();
	$toolbars['Email Content'][1] = array( 'bold', 'italic', 'link', 'unlink', 'bullist', 'numlist', 'undo', 'redo' );
	return $toolbars;
}

add_filter( 'acf/fields/wysiwyg/toolbars', __NAMESPACE__ . '\acf_toolbars' );
