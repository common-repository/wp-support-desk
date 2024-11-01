<?php

/**
 * Get assigned attached category with this department
 */
//get categories
/*$category_ids = get_taxonomy_ids_by_department($department_id);
if ( ! $category_ids)
    $category_ids = array(1);*/

/**
 * get category and priorities
 */
$priorities = get_categories(array('taxonomy'=>'wpsd_priority', 'hide_empty' => 0));
//$categories = get_categories(array('taxonomy'=>'wpsd_category', 'hide_empty' => 0, 'include' => $category_ids));

$departments_query = wpsd_get_departments_query();
?>


<div class="row">
    <div class="col-sm-12">

        <div class="header-lined">

            <div class="row">
                <div class="col-sm-7">
                    <h1><?php _e('Create New Ticket', 'wp-support-desk'); ?> </h1>
                </div>
                <div class="col-sm-5">
                    <form role="form" method="post" action="">
                        <div class="input-group">
                            <input name="wpsd_ticket_search_admin" id="wpsd_ticket_search_admin" class="form-control" placeholder="<?php _e('Search your tickets here.', 'wp-support-desk'); ?>" type="text" value="">
                                <span class="input-group-btn">
                                    <input class="btn btn-primary wpsd_ticket_search_admin_btn" value="Search" type="submit">
                                </span>
                        </div>
                    </form>
                </div>
            </div>

            <ol class="breadcrumb">
                <li>
                    <a href="javascript:;" class="wpsd-load-tickets-home-admin"> <?php _e('WP Support Desk Home', 'wp-support-desk'); ?></a>
                </li>
                <li class="active">
                    <?php _e('Create new ticket', 'wp-support-desk'); ?>
                </li>
            </ol>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-12">

        <div class="well bs-component">

            <legend><?php _e('Create new ticket', 'wp-support-desk'); ?></legend>

            <form class="form-horizontal" id="wpsd-create-ticket-form" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="wpsd_description" class="col-sm-3 control-label"><?php _e('On behalf of', 'wp-support-desk') ?></label>
                    <div class="col-sm-9">

                        <?php
                        $users = get_users();
                        ?>
                        <select name="user_id" class="form-control wpsd-select2" data-validation="required">
                            <option value=""><?php _e('Select User', 'wp-support-desk'); ?></option>
                            <?php

                            if ($users){
                                foreach($users as $user){
                                    echo "<option value='{$user->ID}'>{$user->display_name}</option>";
                                }
                            }
                            ?>

                        </select>

                    </div>
                </div>


                <div class="form-group">
                    <label for="wpsd_subject" class="col-sm-3 control-label"><?php _e('Subject', 'wp-support-desk') ?></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="wpsd_subject" name="wpsd_subject" placeholder="<?php _e('Subject', 'wp-support-desk') ?>" data-validation="required">
                    </div>
                </div>

                <?php if( ! is_user_logged_in()) { ?>
                    <div class="form-group">
                        <label for="wpsd_name" class="col-sm-3 control-label"><?php _e('Name', 'wp-support-desk') ?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="wpsd_name" name="wpsd_name" placeholder="<?php _e('Name', 'wp-support-desk') ?>" data-validation="required">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="wpsd_email" class="col-sm-3 control-label"><?php _e('Email', 'wp-support-desk') ?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="wpsd_email" name="wpsd_email" placeholder="<?php _e('Email', 'wp-support-desk') ?>" data-validation="required">
                        </div>
                    </div>
                <?php } ?>

                <?php
                $sql_custom_field = wpsd_get_ticket_custom_field();
                if ($sql_custom_field){
                    foreach ($sql_custom_field as $cfield){
                        ?>

                        <div class="form-group">
                            <label for="<?php echo $cfield->field_name; ?>" class="col-sm-3 control-label"><?php echo $cfield->title; ?></label>
                            <div class="col-sm-9">

                                <?php
                                $input_element = '';
                                $data_validation = ($cfield->field_required ==1 ) ? "data-validation='required'" : "";

                                switch ($cfield->type){
                                    case 'text':
                                    case 'date':
                                        $input_element .= "<input type='{$cfield->type}' class='form-control'  id='{$cfield->field_name}' name='{$cfield->field_name}' placeholder='{$cfield->title}' {$data_validation} />";
                                        break;
                                    case 'textarea':
                                        $input_element .= "<textarea class='form-control' id='{$cfield->field_name}' name='{$cfield->field_name}'  {$data_validation} ></textarea>";
                                        break;
                                    case 'select':
                                        $select_value = explode(',', $cfield->value);

                                        if ($select_value){
                                            $input_element .= "<select  id='{$cfield->field_name}' name='{$cfield->field_name}' class='form-control wpsd-select2' {$data_validation}>";
                                            $input_element .= "<option value=''>Select {$cfield->title}</option>";
                                            foreach ($select_value as $key => $value){
                                                $input_element .= "<option value='{$value}'>{$value}</option>";
                                            }
                                            $input_element .= "</select>";
                                        }
                                        break;
                                    case 'checkbox':
                                        $checkbox_value = explode(',', $cfield->value);
                                        if ($checkbox_value){
                                            foreach ($checkbox_value as $key => $value){
                                                $input_element .= "<input type='checkbox'  id='{$cfield->field_name}{$key}' name='{$cfield->field_name}[]' value='{$value}' {$data_validation} /> {$value}";
                                            }
                                        }
                                        break;
                                    case 'radio':
                                        $checkbox_value = explode(',', $cfield->value);
                                        if ($checkbox_value){
                                            foreach ($checkbox_value as $key => $value){
                                                $input_element .= "<label>";
                                                $input_element .= "<input type='radio'  id='{$cfield->field_name}{$key}' name='{$cfield->field_name}' value='{$value}' {$data_validation} /> {$value}";
                                                $input_element .= "</label>";
                                            }
                                        }
                                        break;
                                }

                                //Generate input html now
                                echo $input_element;

                                if ($cfield->help_text){
                                    echo "<p class='help-block'>{$cfield->help_text}</p>";
                                }


                                ?>

                            </div>
                        </div>


                        <?php
                    }
                    ?>
                    <?php
                }
                ?>

                <div class="form-group">
                    <label for="wpsd_description" class="col-sm-3 control-label"><?php _e('Description', 'wp-support-desk') ?></label>
                    <div class="col-sm-9">
                        <?php //wp_editor(null, 'wpsd_description', array('media_buttons' => false, 'textarea_rows' => 6, 'attribute' => 'value')) ?>

                        <textarea name="wpsd_description" class="form-control" id="wpsd_description" rows="6" data-validation="required"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="wpsd_description" class="col-sm-3 control-label"><?php _e('Priority', 'wp-support-desk') ?></label>
                    <div class="col-sm-9">
                        <select name="priority_id" class="form-control wpsd-select2" data-validation="required">
                            <option value=""><?php _e('Select Priority', 'wp-support-desk'); ?></option>
                            <?php
                            if (count($priorities) > 0){
                                foreach ($priorities as $priority){
                                    echo "<option value='{$priority->term_id}'>{$priority->name}</option>";
                                }
                            }
                            ?>
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <label for="wpsd_description" class="col-sm-3 control-label"><?php _e('Departments', 'wp-support-desk') ?></label>
                    <div class="col-sm-9">

                        <select name="department_id" class="form-control wpsd-select2" data-validation="required">
                            <option value=""><?php _e('Select Department', 'wp-support-desk'); ?></option>

                            <?php if ($departments_query->have_posts()) {
                                while($departments_query->have_posts()) { $departments_query->the_post();
                                    global $post;
                                    echo "<option value='{$post->ID}'>{$post->post_title}</option>";
                                }
                            }
                            ?>

                        </select>

                    </div>
                </div>

                <br />
                <div class="form-group">
                    <?php do_action('wpsd_create_new_ticket_end_of_form_admin'); ?>


                    <div class="col-sm-offset-1 col-sm-5">
                        <!--<input type="hidden" name="department_id" value="<?php /*echo $department_id; */?>" />-->
                        <input type="hidden" name="wpsd_success_redirect_url" value="<?php echo esc_url(remove_query_arg('wpsd_action')); ?>" />
                        <button type="submit" class="btn btn-primary" id="wpsd_ticket_submit_btn"><?php _e('Submit Ticket', 'wp-support-desk') ?></button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>