<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPSD_Recaptcha_Init')) {
    class WPSD_Recaptcha_Init
    {
        /**
         * @var null
         *
         * Instance of this class
         */
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

            $is_active_recaptcha = wpsd_recaptcha_options('wpsd_enable_recaptcha');
            if ( $is_active_recaptcha == '1') {
                add_action('wp_enqueue_scripts', array($this, 'wpneo_recaptcha_enqueue_frontend_script')); // Add recaptcha js in footer
            }

            /**
             * Add recaptcha to WP Support Desk login form
             */
            if ( $is_active_recaptcha == '1') {
                if (wpsd_recaptcha_options('wpsd_enable_recaptcha_in_login_form') == 1) {
                    add_action('wpsd_after_login_form', array($this, 'wpsd_after_login_form')); // Short code for HTML section google reCAPTCHA
                }
            }

            if ( $is_active_recaptcha == '1') {
                if (wpsd_recaptcha_options('wpsd_enable_recaptcha_in_registration_form') == 1) {
                    add_action('wpsd_after_registration_form_field', array($this, 'wpsd_after_login_form')); // Short code for HTML section google reCAPTCHA
                }
            }

            if ( $is_active_recaptcha == '1') {
                if (wpsd_recaptcha_options('wpsd_enable_recaptcha_in_ticekt_creating_form') == 1) {
                    add_action('wpsd_create_new_ticket_end_of_form', array($this, 'wpsd_after_login_form')); // Short code for HTML section google reCAPTCHA
                }
            }
            
            add_filter('wpsd_settings_tabs', array($this, 'wpsd_settings_tabs_recaptcha'));
        }

        /**
         * Some task during plugin activate
         */
        public static function initial_plugin_setup(){
            //Check is plugin used before or not
            if (get_option('wpneo_recaptcha_is_used')){ return false; }

            update_option('wpneo_wpsd_recaptcha_is_used', WP_SUPPORT_DESK_VERSION);
            update_option('wpneo_wpsd_enable_recaptcha', 'false');
            update_option('wpneo_wpsd_enable_recaptcha_in_user_registration', 'false');
        }

        public function wpsd_after_login_form(){
            $wpsd_recaptcha_site_key =  wpsd_recaptcha_options('wpsd_recaptcha_site_key');
            $html = '<p><div class="g-recaptcha" data-sitekey="'.$wpsd_recaptcha_site_key.'" id="wpsd-g-recaptcha"></div></p>';
            echo $html;
        }

        public function wpneo_recaptcha_enqueue_frontend_script(){
            wp_enqueue_script('wpsd-recaptcha-main-js', 'https://www.google.com/recaptcha/api.js', null, WP_SUPPORT_DESK_VERSION, true);
        }

        public function wpsd_settings_tabs_recaptcha($tabs){
            //Defining page location into variable
            $tabs['recaptcha'] = array(
                'tab_name' => __('reCAPTCHA','wp-support-desk'),
                'load_form_file' => WPNEO_WPSD_RECAPTCHA_DIR_PATH.'pages/tab-recaptcha.php'
            );
            return $tabs;
        }

    }
}

WPSD_Recaptcha_Init::instance();

