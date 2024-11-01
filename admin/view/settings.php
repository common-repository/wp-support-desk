<div class="wrap wpneo-wpsd-settings-wrap">

    <?php
    // Settings Tab With slug and Display name
    $tabs = apply_filters('wpsd_settings_tabs', array(
            'overview' 	=>
                array(
                    'tab_name' => __('Overview','wp-support-desk'),
                    'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/tab-overview.php'
                ),
            'departments' 	=>
                array(
                    'tab_name' => __('Departments','wp-support-desk'),
                    'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/tab-departments.php'
                ),
            'support_staff' 	=>
                array(
                    'tab_name' => __('Support Staff','wp-support-desk'),
                    'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/tab-support_staff.php'
                ),
            'settings' 	=>
                array(
                    'tab_name' => __('Settings','wp-support-desk'),
                    'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/tab-settings.php'
                ),
            'custom_field' 	=>
                array(
                    'tab_name' => __('Custom Field','wp-support-desk'),
                    'load_form_file' => WPSD_PLUGIN_DIR.'admin/view/tabs/tab-custom_field.php'
                ),
        )
    );

    $current_page = 'overview';
    if( ! empty($_GET['tab']) ){
        $current_page = sanitize_text_field($_GET['tab']);
    }

    // Print the Tab Title
    echo '<nav class="nav-tab-wrapper wpsd-nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href=".admin_url('admin.php?page=wp-support-desk-settings')."&tab={$tab}>{$name['tab_name']}</a>";
    }
    echo '</nav>';
    ?>


    <?php
    /**
     *
     * Load settings file from view
     */

        //Load tab file
        $request_file = $tabs[$current_page]['load_form_file'];
        $default_file = WPSD_PLUGIN_DIR.'admin/view/tabs/tab-overview.php';

        if (array_key_exists(trim(esc_attr($current_page)), $tabs)){
            if (file_exists($request_file)){
                include_once $request_file;
            }else{
                if (file_exists($default_file))
                    include_once $default_file;
            }
        }else {
            if (file_exists($default_file))
                include_once $default_file;
        }
        ?>
   
</div>

