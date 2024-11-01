<?php
/**
 * Load view conditionally
 */
if (is_user_logged_in()){
    include WPSD_PLUGIN_DIR.'includes/view/wpsd-ticket-manager.php';
}else{
    include WPSD_PLUGIN_DIR.'includes/view/wpsd-guest-home.php';
}