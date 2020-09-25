<?php
/**
 * Provides the functions necessary for instantly
 * sending weekly email markup
 */
namespace Coronavirus\Utils\Includes\EmailSend;
use Coronavirus\Utils\Includes\Config;


/**
 * Properly formats incoming arguments
 * to send an email from WordPress
 *
 * @author Jim Barnes
 * @since 1.1.0
 * @param array $args The argument array
 * @return bool True if the email was sent.
 */
function send_test( $args ) {
	$args = shortcode_atts(
		array(
			'to'            => array( 'webcom@ucf.edu' ),
			'subject'       => '**TEST** Test Email **TEST**',
			'from_friendly' => 'Coronavirus Site Admin',
			'from'          => 'webcom@ucf.edu',
			'body'          => 'Hello World',
		),
		$args
	);

	$headers = array();

	$from_friendly = $args['from_friendly'];
	$from_email    = $args['from'];

	$sender = "From: $from_friendly <$from_email>";

	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = $sender;

	return wp_mail(
		$args['to'],
		trim( $args['subject'] ),
		trim( $args['body'] ),
		$headers
	);
}


/**
 * Helper function for text/html issues
 *
 * @author Jim Barnes
 * @since 1.1.0
 * @param string $content_type
 * @return string
 */
function content_type( $content_type ) {
	return 'text/html';
}


/**
 * Retrieves email markup to send within a test.
 *
 * @author Jo Dickson
 * @since 1.1.0
 * @return string
 */
function retrieve_email_markup() {
	$url           = get_option( CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'email_gmucf_url' );
	$response      = wp_remote_get( $url, array( 'timeout' => 15 ) );
	$response_code = wp_remote_retrieve_response_code( $response );
	$result        = false;

	if ( is_array( $response ) && is_int( $response_code ) && $response_code < 400 ) {
		$result = wp_remote_retrieve_body( $response );
	}

	return $result;
}


/**
 * Sanitizes a test recipient's email address to ensure
 * errant spaces are removed around the address, and that
 * consistent lowercase letters are used.
 *
 * @author Jo Dickson
 * @since 1.1.0
 * @param string $recipient_email A test recipient's email address
 * @return string Sanitized recipient's email address
 */
function sanitize_recipient_email( $recipient_email ) {
	return strtolower( trim( $recipient_email ) );
}


/**
 * Retrieves coronavirus email markup and sends a test
 * with that markup instantly.
 *
 * @author Jim Barnes
 * @since 1.1.0
 * @return bool Whether the email contents were sent successfully
 */
function instant_send() {
	$markup = retrieve_email_markup();

	$args = array(
		'body' => $markup
	);

	// Get recipients
	$test_recipients_raw = get_field( 'preview_recipients', 'options_weekly_email' );
	$recipients = explode( ',', $test_recipients_raw ) ?: array();
	$recipients = array_unique( array_filter( array_map( __NAMESPACE__ . '\sanitize_recipient_email', $recipients ) ) );

	if ( count( $recipients ) > 0 ) {
		$args['to'] = $recipients;
	}

	// Get subject line and from details
	$subject       = get_option( CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'email_test_subject_line' );
	$from_email    = get_option( CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'email_test_from_address' );
	$from_friendly = get_option( CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'email_test_from_friendly' );

	if ( $subject ) {
		$args['subject'] = "**TEST** $subject **TEST**";
	}

	if ( $from_email && $from_friendly ) {
		$args['from']          = $from_email;
		$args['from_friendly'] = $from_friendly;
	}

	$send = send_test( $args );

	return $send;
}


/**
 * The ajax handler for coronavirus email instant sends.
 *
 * @author Jim Barnes
 * @since 1.1.0
 */
function instant_send_ajax() {
	$send = instant_send();

	$retval = array(
		'success' => $send
	);

	echo json_encode( $retval );

	wp_die();
}

add_action( 'wp_ajax_instant-send', __NAMESPACE__ . '\instant_send_ajax', 10 );
