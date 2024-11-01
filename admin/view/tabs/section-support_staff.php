<?php
$get_wpsd_ticket_users_ids = get_wpsd_ticket_users_ids();
$get_wpsd_ticket_users = get_users(array('include' => $get_wpsd_ticket_users_ids));

if ( ! empty($get_wpsd_ticket_users_ids)){
?>
<div id="assignedStatusMsg"></div>
<table class="table table-striped wpsd-table">
    <thead>
        <tr>
            <th>#</th>
            <th><?php _e('User Name', 'wp-support-desk') ?></th>
            <th><?php _e('Departments', 'wp-support-desk') ?></th>
            <th><?php _e('Role', 'wp-support-desk') ?></th>
            <th><?php _e('Delete tickets', 'wp-support-desk') ?></th>
            <th><?php _e('Action', 'wp-support-desk') ?></th>
        </tr>
    </thead>
    <?php
    $i = 0;
    foreach ($get_wpsd_ticket_users as $user){
        $i++;
        $wpsd_ticket_user = get_wpsd_ticket_user_row($user->ID);
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $user->data->display_name; ?></td>
        <td>
            <?php
            $departments = unserialize($wpsd_ticket_user->departments);
            if ($departments){
                $inc = 0;
                foreach ($departments as $department){
                    if( $inc >=1 ){ echo ' , '; }
                    echo get_the_title($department);
                    $inc++;
                }
            }
            ?>
        </td>
        <td>
            <?php
            $wpsd_tickets_roles = get_option('wpneo_wpsd_user_roles');
            if (! empty($wpsd_tickets_roles[$wpsd_ticket_user->role])){
                echo $wpsd_tickets_roles[$wpsd_ticket_user->role];
            }
            ?>
        </td>
        <td>
            <?php
            if ($wpsd_ticket_user->delete_tickets){
                echo '<span class="text-success">Yes</span>';
            }else{
                echo '<span class="text-warning">No</span>';
            }
            ?>
        </td>
        <td>
            <a href="<?php echo  admin_url("admin.php?page=wp-support-desk-settings&tab=support_staff&section=edit_support_staff&staff_id=".$user->ID); ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top" title="<?php _e('Edit', 'wp-support-desk') ?>"> <i class="glyphicon glyphicon-pencil"></i> </a>
            <a href="javascript:;" class="btn btn-danger btn-xs wpsd-delete-support-staff" data-support-staff="<?php echo $wpsd_ticket_user->user_ID; ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
        </td>
    </tr>
    <?php } ?>
    <tfoot>
        <tr>
            <th>#</th>
            <th><?php _e('User Name', 'wp-support-desk') ?></th>
            <th><?php _e('Departments', 'wp-support-desk') ?></th>
            <th><?php _e('Role', 'wp-support-desk') ?></th>
            <th><?php _e('Delete tickets', 'wp-support-desk') ?></th>
            <th><?php _e('Action', 'wp-support-desk') ?></th>
        </tr>
    </tfoot>
</table>
<?php } else{
    ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <?php _e('No staff were found', 'wp-support-desk'); ?>
        </div>
    </div>
<?php
} ?>