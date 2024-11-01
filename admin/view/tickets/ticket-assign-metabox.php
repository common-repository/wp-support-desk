<?php

$wpsd_staff = get_wpsd_ticket_users_ids();
$current_user_id = get_current_user_id();
$wpsd_get_user_id_without_current_user = array_diff($wpsd_staff, array($current_user_id));
$users = get_users(array('include' => $wpsd_get_user_id_without_current_user));

$assigned_user_ids_to_this_ticket = get_wpsd_assigned_user_ids($ticket_id);
?>

<div class="jaxtag">
    <p>
        <select class="wpsd-select2 form-control" name="assigned_users[]" multiple="multiple" id="assignUserSelect">
            <option value=""> <?php _e('select user', 'wpsd-support-desk'); ?> </option>

            <?php
            foreach($users as $user){
                if ( ! in_array($user->data->ID, $assigned_user_ids_to_this_ticket)) {
                    echo "<option value='{$user->data->ID}'>{$user->data->display_name}</option>";
                }
            }
            ?>
        </select>

    </p>

    <p><input class="button" id="assignUserToTicket" value="Assign" type="button"></p>

    <p class="howto" id="new-tag-post_tag-desc"><?php _e('You can assign multiple user at a time', 'wp-support-desk'); ?></p>
    <div id="assignedStatusMsg"></div>


    <h5><?php _e('Assigned users'); ?></h5>
    <div id="ticket_details_backend_assigned_user_list_table_wrap">
        <table class="table table-striped assigned_user_list_ticket_backend">

            <?php if ($assigned_user_ids_to_this_ticket) {
                foreach ($assigned_user_ids_to_this_ticket as $assigned_uid) {
                    ?>

                    <tr>
                        <td class="column-username">
                            <?php
                            echo get_avatar($assigned_uid, 30);
                            echo "<strong>".get_the_author_meta( 'display_name', $assigned_uid )."</strong>";
                            ?>
                        </td>
                        <td>
                            <a href="javascript:;" class="btn btn-danger btn-xs removeAssignedUser" data-id="<?php echo $assigned_uid; ?>"><i class="glyphicon glyphicon-trash"></i> </a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>

        </table>
    </div>
    

    <input type="hidden" id="post_ID" value="<?php echo $ticket_id; ?>" />

</div>