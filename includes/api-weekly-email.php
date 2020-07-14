<?php
/**
 * All custom wp-json API endpoints should be defined
 * within this file.
 */
namespace Coronavirus\Utils\Includes\APIWeeklyEmail;
use Coronavirus\Utils\Includes\Post as PostUtils;


class Weekly_Email_API extends \WP_REST_Controller {

	/**
	 * Registers the rest routes for the weekly email API
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 */
	public static function register_rest_routes() {
		$root    = 'coronavirus-weekly-email';
		$version = 'v1';

		register_rest_route( "{$root}/{$version}", "/options", array(
			array(
				'methods'              => \WP_REST_Server::READABLE,
				'callback'             => array( __NAMESPACE__ . '\Weekly_Email_API', 'get_email_options' ),
				'permissions_callback' => array( __NAMESPACE__ . '\Weekly_Email_API', 'get_permissions' )
			)
		) );
	}

	/**
	 * Gets the default permissions
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 */
	public static function get_permissions() {
		return true;
	}

	/**
	 * Retrieves the weekly email options
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param WP_REST_Request $request Contains GET params
	 * @return WP_REST_Response
	 */
	public static function get_email_options( $request ) {
		$retval = get_fields( 'options_weekly_email' );
		$retval['email_content'] = self::format_email_content( $retval['email_content'] );

		return new \WP_REST_Response( $retval, 200 );
	}

	/**
	 * Loops through rows of flexible content and
	 * consolidates/sanitized returned values for each
	 * supported layout.
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param array $rows Assoc. array containing rows of email components
	 * @return array Formatted email content data
	 */
	public static function format_email_content( $rows ) {
		$rows_formatted = array();

		foreach ( $rows as $key => $row ) {
			switch ( $row['acf_fc_layout'] ) {
				case 'article':
					$rows_formatted[$key] = self::format_article( $row );
					break;
				case 'image':
					$rows_formatted[$key] = self::format_image( $row );
					break;
				case 'article_list':
					$rows_formatted[$key] = self::format_article_list( $row );
					break;
				case 'two_column_row':
					$rows_formatted[$key] = self::format_two_column_row( $row );
					break;
				default:
					$rows_formatted[$key] = $row;
					break;
			}
		}

		return $rows_formatted;
	}

	/**
	 * Returns consolidated data for a single article component.
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param array $row Assoc. array of article component data
	 * @return array Formatted article data
	 */
	public static function format_article( $row, $thumb_size='medium_large' ) {
		$row_formatted = array(
			'acf_fc_layout' => 'article',
			'thumbnail' => '',
			'article_title' => '',
			'article_deck' => '',
			'links_to' => ''
		);
		$post = null;

		if ( $row['article_type'] === 'post' && ! empty( $row['existing_post'] ) ) {
			$post = $row['existing_post'];
			$row_formatted['thumbnail'] = self::get_image_url( PostUtils\get_thumbnail_id( $post ), $thumb_size );
			$row_formatted['article_title'] = $post->post_title;
			$row_formatted['article_deck'] = ucfwp_get_excerpt( $post, 30 );
			$row_formatted['links_to'] = get_permalink( $post );
		}

		if (
			$row['article_type'] === 'custom'
			|| ( $row['article_type'] === 'post' && $row['content_overrides'] === true )
		) {
			if ( ! empty( $row['thumbnail'] ) ) {
				$row_formatted['thumbnail'] = self::get_image_url( $row['thumbnail']['ID'], $thumb_size );
			}
			if ( ! empty( $row['article_title'] ) ) {
				$row_formatted['article_title'] = $row['article_title'];
			}
			if ( ! empty( $row['article_deck'] ) ) {
				$row_formatted['article_deck'] = $row['article_deck'];
			}
			if ( ! empty( $row['links_to'] ) ) {
				$row_formatted['links_to'] = $row['links_to'];
			}
		}

		return $row_formatted;
	}

	/**
	 * Returns consolidated data for a single image component.
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param array $row Assoc. array of image component data
	 * @return array Formatted image data
	 */
	public static function format_image( $row ) {
		$image = $row['image_file'];
		$row_formatted = array(
			'acf_fc_layout' => 'image',
			'thumbnail'     => self::get_image_url( $image['ID'] ),
			'alt_text'      => $row['alt_text'],
			'links_to'      => $row['links_to']
		);

		if ( empty( $row_formatted['alt_text'] ) ) {
			$row_formatted['alt_text'] = $image['alt'];
		}

		return $row_formatted;
	}

	/**
	 * Returns consolidated data for a list of articles.
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param array $row Assoc. array of article list component data
	 * @return array Formatted article list data
	 */
	public static function format_article_list( $row ) {
		$row_formatted = $row;

		foreach ( $row['articles'] as $key => $article_row ) {
			$row_formatted['articles'][$key] = self::format_article( $article_row, 'thumbnail' );
		}

		return $row_formatted;
	}

	/**
	 * Returns consolidated data for a two-column row.
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param array $row Assoc. array of two-col row data
	 * @return array Formatted two-col row data
	 */
	public static function format_two_column_row( $row ) {
		$row_formatted = $row;

		$row_formatted['column_1_contents'] = self::format_email_content( $row['column_1_contents'] );
		$row_formatted['column_2_contents'] = self::format_email_content( $row['column_2_contents'] );

		return $row_formatted;
	}

	/**
	 * Returns the URL for an attachment by ID, sized
	 * for use in email components.
	 *
	 * @since 1.0.0
	 * @author Jo Dickson
	 * @param int $attachment_id Attachment ID
	 * @param string $thumb_size WordPress-registered thumbnail size
	 * @return string Image URL
	 */
	public static function get_image_url( $attachment_id, $thumb_size='medium_large' ) {
		return ucfwp_get_attachment_src_by_size( $attachment_id, $thumb_size );
	}

}

add_action( 'rest_api_init', array( __NAMESPACE__ . '\Weekly_Email_API', 'register_rest_routes' ), 10, 0 );
