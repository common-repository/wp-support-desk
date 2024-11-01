<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPNEO_WPSD_Shortcode')){
    class WPNEO_WPSD_Shortcode{
        protected static $_instance = null;

        /**
         * @return null|WPNEO_WPSD_Shortcode
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
            add_shortcode('wpsd_ticket_manager', array($this, 'wpsd_ticket_manager'));
        }

        public function wpsd_ticket_manager($atts, $content = ""){
            //$this->include_view_file();
            ob_start();
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-home.php';
            $main_content = ob_get_clean();
            return $main_content;
        }

        /**
         * Include view file based on query string
         */
        public function include_view_file(){
            //include WPSD_PLUGIN_DIR.'includes/view/wpsd-home.php';
        }

    }
}

WPNEO_WPSD_Shortcode::instance();