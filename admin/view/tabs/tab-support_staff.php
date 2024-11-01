<?php
$sections = apply_filters('wpsd_settings_support_staff_sections', array(
        'support_staff' 	=>
            array(
                'tab_name' => __('Manage','wp-support-desk'),
                'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/section-support_staff.php'
            ),
        'add_new_support_staff' 	=>
            array(
                'tab_name' => __('Add new','wp-support-desk'),
                'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/section-add_new_support_staff.php'
            ),
    )
);

$current_page = 'support_staff';
if( ! empty($_GET['section']) ){
    $current_page = sanitize_text_field($_GET['section']);
}

?>

<ul class="subsubsub">
    <?php
    $total_sections = count($sections);
    $i = 0;
    foreach( $sections as $tab => $name ){
        $i++;
        $divider_pipe = ($i < $total_sections) ? '|':'';
        $class = ( $tab == $current_page ) ? ' current' : '';
        echo "<li><a class='{$class}' href='".admin_url('admin.php?page=wp-support-desk-settings&amp;tab=support_staff&amp;section='.$tab)."'>{$name['tab_name']}</a> {$divider_pipe} </li>";
    }
    ?>
</ul>

<div class="clearfix"></div>
<?php
//Load tab file

$current_section_page = WPSD_PLUGIN_DIR . "admin/view/tabs/section-{$current_page}.php";
$default_file = WPSD_PLUGIN_DIR . 'admin/view/tabs/section-support_staff.php';

if (! empty($sections[$current_page])) {
    $request_file = $sections[$current_page]['load_form_file'];
}

if (array_key_exists(trim(esc_attr($current_page)), $sections)){
    if (file_exists($request_file)){
        include_once $request_file;
    }else{
        if (file_exists($default_file))
            include_once $default_file;
    }
}else {
    if (file_exists($current_section_page)){
        include_once $current_section_page;
    }else{
        include_once $default_file;
    }
}
?>
