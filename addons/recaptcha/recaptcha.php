<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Define wpneo_crowdfunding plugin's url path
 */
define('WPNEO_WPSD_RECAPTCHA_DIR_URL', plugin_dir_url(__FILE__));

/**
 * Define wpneo_crowdfunding plugin's physical path
 */
define('WPNEO_WPSD_RECAPTCHA_DIR_PATH', plugin_dir_path(__FILE__));

/**
 * Some task during plugin activation
 */
register_activation_hook(__FILE__, array('Neo_Recaptcha_Init', 'initial_plugin_setup'));


include_once WPNEO_WPSD_RECAPTCHA_DIR_PATH.'recaptcha-functions.php';
include_once WPNEO_WPSD_RECAPTCHA_DIR_PATH.'classes/class-wpsd-recaptcha-init.php';