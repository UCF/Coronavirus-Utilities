<?php
/**
 * Utility functions for Posts.
 */
namespace Coronavirus\Utils\Includes\Post;


/**
 * Returns an attachment ID for the desired thumbnail
 * image of a given post.
 *
 * Intended for use as a replacement for `get_post_thumbnail_id()`,
 * particularly when retrieval of fallback imagery is essential.
 *
 * Defining a separate function like this is faster and less of a
 * pain vs attempting to hook into `get_post_meta`'s returned value
 * (which is what `get_post_thumbnail_id()` ultimately uses.)
 *
 * Adapted from Today Child Theme (`today_get_thumbnail_id`)
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param mixed $post WP_Post object or post ID
 * @return mixed Attachment ID (int) or null on failure
 */
function get_thumbnail_id( $post ) {
	if ( is_numeric( $post ) ) {
		$post = get_post( $post );
	}
	if ( ! $post instanceof \WP_Post ) return null;

	// Try to retrieve a standard featured image first:
	$attachment_id = get_post_thumbnail_id( $post );

	// Use a Yoast social banner image if no featured image is set:
	if ( ! $attachment_id && method_exists( '\WPSEO_Meta', 'get_value' ) ) {
		// Try Twitter first, then Facebook (opengraph)
		$twitter_img_id = \WPSEO_Meta::get_value( 'twitter-image-id', $post->ID );
		if ( $twitter_img_id ) {
			$attachment_id = $twitter_img_id;
		}
		else {
			$attachment_id = \WPSEO_Meta::get_value( 'opengraph-image-id', $post->ID );
		}
	}

	return $attachment_id;
}
