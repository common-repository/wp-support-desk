<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPNEO_WPSD')){
    class WPNEO_WPSD{
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
            //Add script in admin panel
            if (is_admin()){
                add_action('admin_enqueue_scripts', array($this, 'wpneo_wpsd_admin_script'));
            }
            add_action('wp_enqueue_scripts', array($this, 'wpneo_wpsd_script'));
        }

        /**
         * Load WP Support Desk css and javascript file to admin section
         */
        public function wpneo_wpsd_admin_script($hook){
            
            

            if (strpos($hook, 'wp-support-desk')  !== false) {

                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_style('wpsd-admin-style', WPSD_PLUGIN_DIR_URL . 'assets/css/wpneo_wpsd_admin.css', array(), WP_SUPPORT_DESK_VERSION);
                wp_enqueue_style('wpsd-admin-bootstrap-css', WPSD_PLUGIN_DIR_URL . 'assets/bootstrap/css/bootstrap.min.css', array(), WP_SUPPORT_DESK_VERSION);
                wp_enqueue_script('wpsd-bootstrap-js', WPSD_PLUGIN_DIR_URL . 'assets/bootstrap/js/bootstrap.min.js', array('jquery'), WP_SUPPORT_DESK_VERSION, true);

                //Simplemde
                wp_enqueue_style('wpsd-simplemde-css', WPSD_PLUGIN_DIR_URL . 'assets/css/simplemde.min.css', array(), WP_SUPPORT_DESK_VERSION);
                wp_enqueue_script('wpsd-simplemde-js', WPSD_PLUGIN_DIR_URL . 'assets/js/simplemde.min.js', array('jquery'), WP_SUPPORT_DESK_VERSION, true);

                //Select2
                wp_enqueue_style('wpsd-admin-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', array(), WP_SUPPORT_DESK_VERSION);
                wp_enqueue_script('wpsd-admin-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery'), WP_SUPPORT_DESK_VERSION, true);
                wp_enqueue_script('wpsd-main-admin-js', WPSD_PLUGIN_DIR_URL . 'assets/js/wpsd-main-admin.js', array('jquery'), WP_SUPPORT_DESK_VERSION, true);
            }else{
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_script('wpsd-main-color', WPSD_PLUGIN_DIR_URL . 'assets/js/color.js', array('jquery'), WP_SUPPORT_DESK_VERSION, true);
            }
            
        }

        public function wpneo_wpsd_script(){
            wp_enqueue_style('wpsd-style', WPSD_PLUGIN_DIR_URL.'assets/css/wpneo_wpsd_main_style.css', array(),WP_SUPPORT_DESK_VERSION);
            wp_enqueue_style('wpsd-font-awesome', WPSD_PLUGIN_DIR_URL.'assets/font-awesome/font-awesome.min.css', array(),WP_SUPPORT_DESK_VERSION);
            wp_enqueue_style('wpsd-simplemde-css', WPSD_PLUGIN_DIR_URL.'assets/css/simplemde.min.css', array(),WP_SUPPORT_DESK_VERSION);

            wp_enqueue_script('wpsd-form-validator-js', WPSD_PLUGIN_DIR_URL.'assets/js/form.validator.min.js', array('jquery'),'2.3.26', true);
            wp_enqueue_script('wpsd-simplemde-js', WPSD_PLUGIN_DIR_URL.'assets/js/simplemde.min.js', array('jquery'),WP_SUPPORT_DESK_VERSION, true);
            
            wp_enqueue_script('wpsd-main-js', WPSD_PLUGIN_DIR_URL.'assets/js/wpsd-main.js', array('jquery'),WP_SUPPORT_DESK_VERSION, true);
            wp_localize_script('wpsd-main-js', 'wpsd_ajax_object', array('ajax_url'=> admin_url('admin-ajax.php')));
        }

    }
}

//Include necessary file
include WPSD_PLUGIN_DIR.'includes/class.wpsd_flash_messages.php';
include WPSD_PLUGIN_DIR.'includes/wpneo_wpsd_functions.php';
include WPSD_PLUGIN_DIR.'includes/wpneo_wpsd_ajax.php';
include WPSD_PLUGIN_DIR.'includes/class.wpneo_wpsd_shortcode.php';
include WPSD_PLUGIN_DIR.'includes/class.wpneo_wpsd_register_post_type.php';
include WPSD_PLUGIN_DIR.'includes/wpneo_wpsd_controller.php';
include WPSD_PLUGIN_DIR.'includes/class.wpneo_wpsd_bootstrap.php';

/**
 * Plugin Action during activation
 */
register_activation_hook( WPSD_PLUGIN_BASE_FILE, array('WPNEO_WPSD_Bootstrap', 'wpsd_initial_setup') );

/**
 * Include Addons directory and there main file
 */

$addons_dir = array_filter(glob(WPSD_PLUGIN_DIR.'addons/*'), 'is_dir');
if (count($addons_dir) > 0) {
    foreach ($addons_dir as $key => $value) {
        $addon_dir_name = str_replace(dirname($value).'/', '', $value);
        $file_name = WPSD_PLUGIN_DIR . 'addons/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
        if ( file_exists($file_name) ){
            include_once $file_name;
        }
    }
}