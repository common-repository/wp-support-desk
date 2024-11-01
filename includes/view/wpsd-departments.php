<?php
/**
 * Load file
 */
$wpsd_department_view = null;
if ( ! empty($_GET['wpsd_department'])){
    $wpsd_department_view = sanitize_text_field($_GET['wpsd_department']);
}
if ($wpsd_department_view){
    $load_view_file = WPSD_PLUGIN_DIR."includes/view/wpsd-department-view.php";
    if (file_exists($load_view_file)){
        include $load_view_file;
    }else{
        include WPSD_PLUGIN_DIR.'includes/view/wpsd-departments-manager.php';
    }
} else {
    include WPSD_PLUGIN_DIR.'includes/view/wpsd-departments-manager.php';
}
?>