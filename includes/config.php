<?php
/**
 * Custom configuration settings for the Coronavirus
 * site go here
 */
namespace Coronavirus\Utils\Includes\Config;


define( 'CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX', defined( 'CORONAVIRUS_THEME_CUSTOMIZER_PREFIX' ) ? CORONAVIRUS_THEME_CUSTOMIZER_PREFIX : 'ucfcoronavirus_' );
define( 'CORONAVIRUS_UTILS__CUSTOMIZER_DEFAULTS', serialize( array(
	'email_gmucf_url' => 'https://gmucf.smca.ucf.edu/coronavirus/mail/?no_cache=true',
) ) );


/**
 * Returns a plugin option's default value.
 *
 * @since 1.1.0
 * @author Jo Dickson
 * @param string $option_name The name of the option
 * @return mixed Option default value, or false if a default is not set
 */
function get_option_default( $option_name ) {
	$defaults = unserialize( CORONAVIRUS_UTILS__CUSTOMIZER_DEFAULTS );
	if ( $defaults && isset( $defaults[$option_name] ) ) {
		return $defaults[$option_name];
	}
	return false;
}


/**
 * Initialization functions to be fired early when WordPress loads the plugin.
 *
 * @since 1.1.0
 * @author Jo Dickson
 * @return void
 */
function init() {
	// Enforce default option values when `get_option()` is called.
	$options = unserialize( CORONAVIRUS_UTILS__CUSTOMIZER_DEFAULTS );
	foreach ( $options as $option_name => $option_default ) {
		// Apply our plugin prefix to the option name:
		$option_name = CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . $option_name;

		// Enforce a default value for options we've defined
		// defaults for:
		add_filter( "default_option_$option_name", function( $get_option_default, $option, $passed_default ) use ( $option_default ) {
			// If get_option() was passed a unique default value, prioritize it
			if ( $passed_default ) {
				return $get_option_default;
			}
			return $option_default;
		}, 10, 3 );

		// Enforce typecasting of returned option values,
		// based on the types of the defaults we've defined.
		// NOTE: Forces option defaults to return when empty
		// option values are retrieved.
		add_filter( "option_$option_name", function( $value, $option ) use ( $option_default ) {
			switch ( $type = gettype( $option_default ) ) {
				case 'integer':
					// Assume 0 should be "empty" here:
					$value = intval( $value );
					break;
				case 'string':
				default:
					break;
			}

			if ( empty( $value ) ) {
				$value = $option_default;
			}

			return $value;
		}, 10, 2 );
	}
}

add_action( 'init', __NAMESPACE__ . '\init' );


/**
 * Defines sections used in the WordPress Customizer.
 *
 * @since 1.1.0
 * @author Jo Dickson
 */
function define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'emails',
		array(
			'title' => 'Weekly Emails'
		)
	);
}

add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_sections' );


/**
 * Defines settings and controls used in the WordPress Customizer.
 *
 * @since 1.1.0
 * @author Jo Dickson
 */
function define_customizer_fields( $wp_customize ) {
	// Weekly Emails
	$wp_customize->add_setting(
		CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'email_gmucf_url',
		array(
			'default' => get_option_default( 'email_gmucf_url' ),
			'type'    => 'option'
		)
	);

	$wp_customize->add_control(
		CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'email_gmucf_url',
		array(
			'type'        => 'url',
			'label'       => 'Coronavirus Email on GMUCF',
			'description' => 'URL to the generated Coronavirus email markup on GMUCF on this environment.',
			'section'     => CORONAVIRUS_UTILS__CUSTOMIZER_PREFIX . 'emails'
		)
	);
}

add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_fields' );
