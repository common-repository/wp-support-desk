<?php
class WPSDFlashMessages {
    public function __construct() {
        add_action('admin_notices', array(&$this, 'show_flash_messages'));
    }
    //Flash Messages
    public static function wpsd_flash($message, $class = '') {
        $default_allowed_classes = array('error', 'updated');
        $allowed_classes = apply_filters('flash_messages_allowed_classes', $default_allowed_classes);
        $default_class = apply_filters('flash_messages_default_class', 'updated');
        if(!in_array($class, $allowed_classes)) $class = $default_class;
        $flash_messages = maybe_unserialize(get_option('wp_flash_messages', array()));
        $flash_messages[$class][] = $message;
        update_option('wp_flash_messages', $flash_messages);
    }
    public static function show_flash_messages() {
        $flash_messages = maybe_unserialize(get_option('wp_flash_messages', ''));
        if(is_array($flash_messages)) {
            foreach($flash_messages as $class => $messages) {
                foreach($messages as $message) {
                    ?><div class="<?php echo $class; ?>"><p><?php echo $message; ?></p></div><?php
                }
            }
        }
        //clear flash messages
        delete_option('wp_flash_messages');
    }
}
new WPSDFlashMessages();
//convenience function
if( class_exists('WPSDFlashMessages') && !function_exists('wpsd_flash') ) {
    function wpsd_flash($message, $class = null) {
        WPSDFlashMessages::wpsd_flash($message, $class);
    }
}