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


require_once CORONAVIRUS_UTILS__PLUGIN_DIR . 'includes/options-weekly-email.php';
