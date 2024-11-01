<?php
/*
 * Plugin Name:       WP Support Desk
 * Plugin URI:        https://www.themeum.com
 * Description:       WP Support Desk helps you to track tickets made by your clients or customer.
 * Version:           1.1.1
 * Author:            Themeum
 * Author URI:        https://www.themeum.com
 * Text Domain:       wp-support-desk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

//Constants
define('WP_SUPPORT_DESK_VERSION', '1.1.1');
define('WPSD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPSD_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define('WPSD_PLUGIN_BASE_FILE', __FILE__);

if ( ! defined( 'ABSPATH' ) ) {
    echo "Hello, I can't do anything individually, i can do really something awesome with Wordpress.";
    exit; // Exit if accessed directly
}
/**
 * Include pluggable file if not found
 */
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php");
}

add_action('init', array('WPNEO_WPSD', 'instance'));
include_once WPSD_PLUGIN_DIR.'admin/class.wpneo_wpsd_admin.php';
include_once WPSD_PLUGIN_DIR.'includes/class.wpneo_wpsd.php';