<?php
/*
Plugin Name: Coronavirus Utilities
Description:  Utility and feature plugin for the UCF Coronavirus website.
Version: 1.2.0
Author: UCF Web Communications
License: GPL3
GitHub Plugin URI: UCF/Coronavirus-Utilities
*/

namespace Coronavirus\Utils;


if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'CORONAVIRUS_UTILS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CORONAVIRUS_UTILS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CORONAVIRUS_UTILS__PLUGIN_FILE', __FILE__ );
define( 'CORONAVIRUS_UTILS__PLUGIN_STATIC_URL', CORONAVIRUS_UTILS__PLUGIN_URL . 'static/' );
define( 'CORONAVIRUS_UTILS__PLUGIN_CSS_URL', CORONAVIRUS_UTILS__PLUGIN_STATIC_URL . 'css/' );
define( 'CORONAVIRUS_UTILS__PLUGIN_JS_URL', CORONAVIRUS_UTILS__PLUGIN_STATIC_URL . 'js/' );

define( 'CORONAVIRUS_UTILS__DEFAULT_GMUCF_URL', 'https://gmucf.smca.ucf.edu/coronavirus/mail/?no_cache=true' );


require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/config.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/admin.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/meta.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/post-functions.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/options-weekly-email.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/api-weekly-email.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/email-send-functions.php';
