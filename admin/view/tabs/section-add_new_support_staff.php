<?php
//$categories = get_categories(array('taxonomy'=>'wpsd_category', 'hide_empty' => 0));

$get_wpsd_ticket_users_ids = get_wpsd_ticket_users_ids();
$users = get_users(array('exclude' => $get_wpsd_ticket_users_ids));
$departments_query = wpsd_get_departments_query();
?>

<form class="form-horizontal" method="post" action="">
    <?php wp_nonce_field('wpsd_settings_page_action', 'wpsd_settings_page_nonce_field'); ?>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="user" class="control-label col-md-3"><?php _e('User', 'wp-support-desk') ?></label>
            <div class="col-md-9">
                <select name="user_id" class="form-control wpsd-select2">
                    <option value=""><?php _e('Select user', 'sp-support-desk'); ?></option>
                    <?php
                    foreach($users as $user){
                        echo "<option value='{$user->data->ID}'>{$user->data->display_name}</option>";
                    }
                    ?>
                </select>

            </div>
        </div>
        <div class="form-group">
            <label for="department" class="control-label col-md-3"><?php _e('Department', 'wp-support-desk') ?></label>
            <div class="col-md-9">
                <select class="form-control wpsd-select2" name="department[]" id="department" multiple="multiple">
                    <option value=""><?php _e('Select department', 'wp-support-desk') ?></option>
                    <?php
                        if ($departments_query->have_posts()) {
                            while($departments_query->have_posts()) {
                                $departments_query->the_post();
                                echo "<option value='".get_the_ID()."'>".get_the_title()."</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="support_user_type" class="control-label col-md-3"><?php _e('Support user type', 'wp-support-desk') ?></label>
            <div class="col-md-9">
                <select class="form-control" name="support_user_type" id="support_user_type">
                    <?php
                    $wpsd_user_role = get_option('wpneo_wpsd_user_roles');
                    if (is_array($wpsd_user_role)){
                        foreach ($wpsd_user_role as $role_key => $role_value){
                            echo "<option value='{$role_key}'>{$role_value}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label for="delete_tickets" class="control-label col-md-3"><?php _e('Delete tickets', 'wp-support-desk') ?></label>
            <div class="col-md-9">
                <label class="radio-inline">
                    <input type="radio" name="delete_tickets" id="delete_tickets" value="1"> <?php _e('Yes', 'wp-support-desk') ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="delete_tickets" id="delete_tickets2" value="0"> <?php _e('No', 'wp-support-desk') ?>
                </label>
            </div>
        </div>

        <?php /** ?>
        <div class="form-group">
            <label for="category_id" class="control-label col-md-3"><?php _e('Category', 'wp-support-desk') ?></label>
            <div class="col-sm-9">
                <select name="category_id[]" class="form-control" data-validation="required" multiple="multiple">
                    <?php
                    if (count($categories) > 0){
                        foreach ($categories as $category){
                            echo "<option value='{$category->term_id}'>{$category->name}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php */ ?>

    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php submit_button(__('Save Staff', 'wp-support-desk'),'primary','wpneo_wpsd_support_staff_save_btn'); ?>
        </div>
    </div>

</form>