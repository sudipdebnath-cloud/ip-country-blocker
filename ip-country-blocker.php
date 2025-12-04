<?php
/*
Plugin Name: IP & Country Blocker
Plugin URI: 
Description: Block website access based on visitor's IP or country. Fully customizable from the admin panel.
Version: 1.0.0
Author: Sudip Debnath
Author URI: https://sudipdebnath-cloud.github.io/
License: GPL2
Text Domain: ip-country-blocker
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define('WICB_PATH', plugin_dir_path(__FILE__));
define('WICB_URL', plugin_dir_url(__FILE__));

// Auto load main classes
require_once WICB_PATH.'includes/class-main.php';

// Boot Plugin
WICB_Main::init();
