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

<div class="wpsd-title-wrapper wpsd-bd-bottom">
    <h2 class="wpsd-title"><?php _e('Create new ticket', 'wp-support-desk'); ?></h2>
    <div class="wpsd_response_msg"></div>
</div> <!-- //title wrapper -->

<form class="wpsd-new-ticket" id="wpsd-create-ticket-form" method="post" enctype="multipart/form-data">

    <div class="wpsd-form-group">
        <label for="wpsd-subject"> <?php _e('Subject', 'wp-support-desk') ?> </label>

        <div class="wpsd-new-ticket-subject-wrap">
            <input type="text" class="form-control" id="wpsd_subject" name="wpsd_subject" autocomplete="off" placeholder="<?php _e('Subject', 'wp-support-desk') ?>" data-validation="required">
            <div id="wpsd-kb-suggestion"></div>
        </div>
    </div>

    <?php
    $sql_custom_field = wpsd_get_ticket_custom_field();
    if ($sql_custom_field){
        foreach ($sql_custom_field as $cfield){
            ?>
            <div class="wpsd-form-group">
                <label for="<?php echo $cfield->field_name; ?>"><?php echo $cfield->title; ?></label>

                <?php
                $input_element = '';
                $data_validation = ($cfield->field_required ==1 ) ? "data-validation='required'" : "";

                switch ($cfield->type){
                    case 'text':
                    case 'date':
                        $input_element .= "<input type='{$cfield->type}' class='wpsd-form-control'  id='{$cfield->field_name}' name='{$cfield->field_name}' placeholder='{$cfield->title}' {$data_validation} />";
                        break;
                    case 'textarea':
                        $input_element .= "<textarea class='wpsd-form-control' id='{$cfield->field_name}' name='{$cfield->field_name}'  {$data_validation} ></textarea>";
                        break;
                    case 'select':
                        $select_value = explode(',', $cfield->value);

                        if ($select_value){
                            $input_element .= "<select  id='{$cfield->field_name}' name='{$cfield->field_name}' class='wpsd-select-menu' {$data_validation}>";
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
                            $input_element .= '<span class="wpsd-list">';
                            foreach ($checkbox_value as $key => $value){
                                $input_element .= "<input type='checkbox'  id='{$cfield->field_name}{$key}' name='{$cfield->field_name}[]' value='{$value}' {$data_validation} />  <label for='{$cfield->field_name}{$key}'>{$value}</label>";
                            }
                            $input_element .= '</span>';
                        }
                        break;
                    case 'radio':
                        $checkbox_value = explode(',', $cfield->value);
                        if ($checkbox_value){
                            $input_element .= '<span class="wpsd-list">';
                            foreach ($checkbox_value as $key => $value){
                                $input_element .= "<input type='radio'  id='{$cfield->field_name}{$key}' name='{$cfield->field_name}' value='{$value}' /> <label for='{$cfield->field_name}{$key}'>{$value}</label>";

                            }
                            $input_element .= '</span>';
                        }
                        break;
                }

                //Generate input html now
                echo $input_element;

                if ($cfield->help_text){
                    echo "<span class='help-block'>{$cfield->help_text}</span>";
                }


                ?>

            </div>

            <?php
        }
        ?>
        <?php
    }
    ?>

    <div class="wpsd-form-group">
        <!--<label for="wpsd_description"><?php /*_e('Description', 'wp-support-desk') */?></label>-->
        <textarea name="wpsd_description" class="" id="wpsd_description" rows="6" data-validation="required"></textarea>
    </div>

    <div class="wpsd-form-group">
        <label for="priority_id" ><?php _e('Priority', 'wp-support-desk') ?></label>
        <select name="priority_id" id="priority_id" class="wpsd-select-menu" data-validation="required">
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

    <div class="wpsd-form-group">
        <label for="department_id"><?php _e('Departments', 'wp-support-desk') ?></label>
        <select name="department_id" id="department_id" class="wpsd-select-menu" data-validation="required">
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
    
    <?php do_action('wpsd_create_new_ticket_end_of_form'); ?>

    <hr>
    <div class="wpsd-form-group">
        <input type="hidden" name="wpsd_success_redirect_url" value="<?php echo esc_url(remove_query_arg('wpsd_action')); ?>" />
        <button type="submit" class="wpsd-btn wpsd-btn-lg wpsd-btn-primary wpsd-pull-right" id="wpsd_ticket_submit_btn"><?php _e('Submit Ticket', 'wp-support-desk') ?></button>
    </div>
</form> <!-- new-ticket -->


