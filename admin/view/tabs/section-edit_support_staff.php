<?php
$staff_id = sanitize_text_field($_GET['staff_id']);
$categories = get_categories(array('taxonomy'=>'wpsd_category', 'hide_empty' => 0));

$departments_query = wpsd_get_departments_query();

$get_wpsd_user = get_wpsd_ticket_user_row($staff_id);

$assigned_departments = unserialize($get_wpsd_user->departments);
if (! $assigned_departments)
    $assigned_departments = array();
$assigned_categories = unserialize($get_wpsd_user->categories);
if (! $assigned_categories)
    $assigned_categories = array();
?>

<form class="form-horizontal" method="post" action="">
    <?php wp_nonce_field('wpsd_settings_page_action', 'wpsd_settings_page_nonce_field'); ?>

    <div class="col-md-6">
        <div class="form-group">
            <label for="user" class="control-label col-md-3"><?php _e('User', 'wp-support-desk') ?></label>
            <div class="col-md-9">
                <strong><?php echo get_the_author_meta('display_name', $staff_id); ?></strong>
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
                            $selected = in_array(get_the_ID(), $assigned_departments)? 'selected': '';

                            echo "<option value='".get_the_ID()."' {$selected}>".get_the_title()."</option>";
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
                            $selected = ($role_key == $get_wpsd_user->role) ? 'selected' : '';
                            echo "<option value='{$role_key}' {$selected}>{$role_value}</option>";
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
                    <input type="radio" name="delete_tickets" id="delete_tickets" value="1" <?php if ($get_wpsd_user->delete_tickets == 1) echo "checked"; ?> > <?php _e('Yes', 'wp-support-desk') ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="delete_tickets" id="delete_tickets2" value="0" <?php if ($get_wpsd_user->delete_tickets == 0) echo "checked"; ?> > <?php _e('No', 'wp-support-desk') ?>
                </label>
            </div>
        </div>

        <?php /* ?>
        <div class="form-group">
            <label for="category_id" class="control-label col-md-3"><?php _e('Category', 'wp-support-desk') ?></label>
            <div class="col-sm-9">
                <select name="category_id[]" class="form-control  wpsd-select2" data-validation="required" multiple="multiple">
                    <?php
                    if (count($categories) > 0){
                        foreach ($categories as $category){
                            $selected = in_array($category->term_id, $assigned_categories)? 'selected': '';

                            echo "<option value='{$category->term_id}' {$selected}>{$category->name}</option>";
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
            <input type="hidden" name="user_id" value="<?php echo $staff_id; ?>" />
            <?php submit_button(__('Update Staff', 'wp-support-desk'),'primary','wpneo_wpsd_support_staff_update_btn'); ?>
        </div>
    </div>

</form>
