<?php
/*
Plugin Name: Coronavirus Utilities
Description:  Utility and feature plugin for the UCF Coronavirus website.
Version: 0.0.0
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
define( 'CORONAVIRUS_UTILS__PLUGIN_STATIC_URL', CORONAVIRUS_UTILS__PLUGIN_URL . 'static/' );
define( 'CORONAVIRUS_UTILS__PLUGIN_JS_URL', CORONAVIRUS_UTILS__PLUGIN_STATIC_URL . 'js/' );


require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/admin.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/post-functions.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/options-weekly-email.php';
require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/api-weekly-email.php';
