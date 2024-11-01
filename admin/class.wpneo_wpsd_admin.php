<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPNEO_WPSD_Admin')){
    class WPNEO_WPSD_Admin{
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
            add_action( 'admin_menu',   array($this, 'wpneo_wpsd_menu_page') );
            add_action( 'parent_file',  array($this, 'wpneo_wpsd_admin_menu_arrange'));
        }

        public function wpneo_wpsd_admin_menu_arrange( $parent_file ) {
            global $current_screen;
            $taxonomy = $current_screen->taxonomy;
            if ( $taxonomy == 'wpsd_priority' ){
                $parent_file = 'wp-support-desk';
            }
            return $parent_file;
        }

        public function wpneo_wpsd_menu_page(){
            add_menu_page(__('WP Support Desk', 'wp-support-desk'), __('WP Support Desk', 'wp-support-desk'), 'manage_wpsd_support_user_options', 'wp-support-desk', array($this, 'admin_page'), WPSD_PLUGIN_DIR_URL.'assets/images/wpsd-logo.png');
            add_submenu_page('wp-support-desk', __('Priorities', 'wp-support-desk'), __('Priorities', 'wp-support-desk'), 'manage_wpsd_team_lead_options', 'edit-tags.php?taxonomy=wpsd_priority&post_type=wpsd_tickets', null);
            add_submenu_page('wp-support-desk', __('Knowledgebase', 'wp-support-desk'), __('Knowledgebase', 'wp-support-desk'), 'manage_wpsd_team_lead_options', 'edit.php?post_type=wpsd_knowledgebase', null);
            add_submenu_page('wp-support-desk', __('Settings', 'wp-support-desk'), __('Settings', 'wp-support-desk'), 'manage_wpsd_manager_options', 'wp-support-desk-settings', array($this, 'wpsd_admin_settings_page'));
        }

        public function admin_page(){
            include_once WPSD_PLUGIN_DIR.'admin/view/tickets/wpsd_admin_home.php';
        }

        public function wpsd_admin_settings_page(){
            include_once WPSD_PLUGIN_DIR.'admin/view/settings.php';
        }

        public function wpneo_wpsd_no_connection_error_notice(){
            $html = '<div class="error">';
            $html .= '<p> '.__('Something went wrong, please try again later', 'wp-support-desk').' </p>';
            $html .= '</div>';
            echo $html;
        }

    }
}

WPNEO_WPSD_Admin::instance();