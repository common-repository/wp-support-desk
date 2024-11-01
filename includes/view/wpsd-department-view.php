<?php
/**
 * Load department view
 */
$wpsd_department = sanitize_text_field($_GET['wpsd_department']);

$department_args = array(
    'name'        => $wpsd_department,
    'post_type'   => 'wpsd_department',
    'post_status' => 'publish',
    'numberposts' => 1
);
$post_department = get_posts($department_args);
$department_title = null;
$department_id = 0;
if ($post_department){
    $department_title = $post_department[0]->post_title;
    $department_id = $post_department[0]->ID;
}
?>

<div class="page-header">
    <?php
    if ($department_title){
        echo "<h1>{$department_title}</h1>";
    }else{
        echo "<h1>".__('Department Manager', 'wp-support-desk')."</h1>";
    }
    ?>
</div>


<?php
$wpsd_action = null;
if ( ! empty($_GET['wpsd_action'])){
    $wpsd_action = sanitize_text_field($_GET['wpsd_action']);
}
if ($wpsd_action){
    $load_view_file = WPSD_PLUGIN_DIR."includes/view/wpsd-{$wpsd_action}.php";
    if (file_exists($load_view_file)){
        include $load_view_file;
    }else{
        include WPSD_PLUGIN_DIR.'includes/view/wpsd-indivudial-department.php';
    }
} else {
    include WPSD_PLUGIN_DIR.'includes/view/wpsd-indivudial-department.php';
}
?>