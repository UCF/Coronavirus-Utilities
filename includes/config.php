<?php
/**
 * Custom configuration settings for the Coronavirus
 * site go here
 */
namespace Coronavirus\Utils\Includes\Config;


/**
 * Defines sections used in the WordPress Customizer.
 *
 * @since 1.1.0
 * @author Jo Dickson
 */
function define_customizer_sections( $wp_customize ) {
	if ( defined( 'CORONAVIRUS_THEME_CUSTOMIZER_PREFIX' ) ) {
		$wp_customize->add_section(
			CORONAVIRUS_THEME_CUSTOMIZER_PREFIX . 'emails',
			array(
				'title' => 'Weekly Emails'
			)
		);
	}
}

add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_sections' );


/**
 * Defines settings and controls used in the WordPress Customizer.
 *
 * @since 1.1.0
 * @author Jo Dickson
 */
function define_customizer_fields( $wp_customize ) {
	if ( defined( 'CORONAVIRUS_THEME_CUSTOMIZER_PREFIX' ) ) {
		// Weekly Emails
		$wp_customize->add_setting(
			CORONAVIRUS_THEME_CUSTOMIZER_PREFIX . 'email_gmucf_url',
			array(
				'default' => CORONAVIRUS_UTILS__DEFAULT_GMUCF_URL,
				'type'    => 'option'
			)
		);

		$wp_customize->add_control(
			CORONAVIRUS_THEME_CUSTOMIZER_PREFIX . 'email_gmucf_url',
			array(
				'type'        => 'url',
				'label'       => 'Coronavirus Email on GMUCF',
				'description' => 'URL to the generated Coronavirus email markup on GMUCF on this environment.',
				'section'     => CORONAVIRUS_THEME_CUSTOMIZER_PREFIX . 'emails'
			)
		);
	}
}

add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_fields' );
