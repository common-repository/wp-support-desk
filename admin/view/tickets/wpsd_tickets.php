<?php
global $wpdb;
$current_user_id                = get_current_user_id();
$get_user_info                  = get_wpsd_ticket_user_row($current_user_id);
$wpsd_ticket_search_term_admin  = sanitize_text_field(wpsd_post('wpsd_ticket_search_term_admin'));

$wpsd_ticket_query_args = array(
                                'post_type'     => 'wpsd_tickets',
                                'post_parent'   => 0,
                                'post_status'   => array( 'publish', 'private' ),
                                'order'         => 'DESC'
                            );

if ($get_user_info) {
    
    if ($get_user_info->role != 'manager') { //Check if current user are not manager

        $depament_ids   = $get_user_info->departments; //Get department and categories id
        $is_serialize   = @unserialize($depament_ids); //Determine if department ids is a serialize string, if yes, then unserialize it first

        if ($depament_ids === 'b:0;' || $is_serialize !== false) {
            $depament_ids = $is_serialize;
        }
        $depament_ids   = implode(",", $depament_ids);

        //Get post ids by default from accessible department and categories
        $default_post_ids_query = $wpdb->get_col("SELECT 
                                                    {$wpdb->posts}.ID 
                                                FROM 
                                                    {$wpdb->posts}  
                                                INNER JOIN 
                                                    {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id) 
                                                INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) 
                                                WHERE 
                                                    {$wpdb->posts}.post_parent = 0   
                                                    AND ( ( {$wpdb->postmeta}.meta_key = 'wpsd_department_id' 
                                                    AND {$wpdb->postmeta}.meta_value IN ({$depament_ids}) )) 
                                                    AND {$wpdb->posts}.post_type = 'wpsd_tickets' 
                                                    AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'private')");

        //Get assigned post id by others
        $assigned_post_ids  = $wpdb->get_col("SELECT post_id FROM {$wpdb->prefix}wpsd_assigned_tickets_users WHERE author_id = {$current_user_id}"); 
        $all_post_ids       = array_merge($assigned_post_ids, $default_post_ids_query); //Merge them both result together
        $all_post_ids       = array_unique($all_post_ids);

        if (!$all_post_ids){
            $all_post_ids = array(1);
        }

        $wpsd_ticket_query_args = array(
                                    'post_type'     => 'wpsd_tickets',
                                    'post_parent'   => 0,
                                    'post__in'      => $all_post_ids,
                                    'post_status'   => array( 'publish', 'private' ),
                                    'order'         => 'DESC'
                                );

    }
}
//Page Number
$page_numb                          = max( 1, sanitize_text_field(wpsd_post('current_page')) );
$wpsd_ticket_query_args['paged']    = $page_numb;

//Search ticket, if isset
if ($wpsd_ticket_search_term_admin){
    $wpsd_ticket_query_args['s']    = $wpsd_ticket_search_term_admin;
}

$tickets = new WP_Query($wpsd_ticket_query_args);
?>
    <div class="row">
        <div class="col-sm-12">
            <div class="header-lined">
                <div class="row">
                    <div class="col-sm-7">
                        <h1><?php _e('View Ticket', 'wp-support-desk'); ?>
                            <a href="javascript:;" class="btn btn-info btn-sm" id="wpsd_create_new_ticket_admin">
                                <i class="glyphicon glyphicon-plus"></i>
                                <?php _e('Add new ticket', 'wp-support-desk'); ?></a>
                        </h1>
                    </div>
                    <div class="col-sm-5">
                        <form role="form" method="post" action="">
                            <div class="input-group">
                                <input name="wpsd_ticket_search_admin" id="wpsd_ticket_search_admin" class="form-control" placeholder="<?php _e('Search your tickets here.', 'wp-support-desk'); ?>" type="text" value="<?php echo $wpsd_ticket_search_term_admin; ?>">
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
                        <?php _e('Support Tickets', 'wp-support-desk'); ?>
                    </li>
                </ol>
            </div>

            <div id="assignedStatusMsg"></div>
            <table class="table table-striped wpsd-table">
                <tr>
                    <th><?php _e('Subject', 'wp-support-desk'); ?></th>
                    <th><?php _e('Posted by', 'wp-support-desk'); ?></th>
                    <th><?php _e('Status', 'wp-support-desk'); ?></th>
                    <th><?php _e('Date', 'wp-support-desk'); ?></th>
                    <th><?php _e('Action', 'wp-support-desk'); ?></th>
                </tr>
                <?php
                if ($tickets->have_posts()){
                    $current_user_id = get_current_user_id();
                    while ($tickets->have_posts()): $tickets->the_post();
                        global $post;
                        ?>
                        
                        <tr>
                            <td>
                                <a href="javascript:;" data-ticket-id="<?php echo get_the_ID(); ?>" class="wpsd-ticket-view-admin"><?php the_title(); ?></a>
                                <?php
                                    $post_id = get_the_ID();
                                    $is_assigned_user_id = $wpdb->get_var("select assigned_by from {$wpdb->prefix}wpsd_assigned_tickets_users where post_id = {$post_id} AND author_id = {$current_user_id} limit 1");

                                    if ($is_assigned_user_id){
                                        $assigned_user_name = get_the_author_meta('display_name', $is_assigned_user_id);
                                        echo '<br />'.__('Assigned by: ', 'wp-support-desk')." ".$assigned_user_name;
                                    }
                                ?>
                            </td>
                            <td><?php the_author(); ?></td>
                            <td><strong><?php $status = get_wpsd_ticket_status(get_the_ID()); if ($status) echo "({$status})"; ?></strong></td>
                            <td>
                                <?php
                                    echo __('Created at', 'wp-support-desk').": ". date('d-m-Y', strtotime($post->post_date)).' '.date('H:iA', strtotime($post->post_date));
                                    echo "<br />";
                                    echo __('Modified at', 'wp-support-desk').": ". date('d-m-Y', strtotime($post->post_modified)).' '.date('H:iA', strtotime($post->post_modified));
                                ?>
                            </td>
                            <td>
                                <?php if ( (bool) $get_user_info->delete_tickets){ ?>
                                    <a href="javascript:;" class="btn btn-danger btn-xs wpsd-delete-ticket" data-ticket-id="<?php echo get_the_ID(); ?>"><i class="glyphicon glyphicon-trash"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    endwhile;
                }
                ?>
                <tr>
                    <th><?php _e('Subject', 'wp-support-desk'); ?></th>
                    <th><?php _e('Posted by', 'wp-support-desk'); ?></th>
                    <th><?php _e('Status', 'wp-support-desk'); ?></th>
                    <th><?php _e('Date', 'wp-support-desk'); ?></th>
                    <th><?php _e('Action', 'wp-support-desk'); ?></th>
                </tr>
            </table>
        </div>
    </div>

    <div class="wpsd-ticket-pagination">
        <?php
        $max_page = $tickets->max_num_pages;
        wpsd_pagination( $page_numb, $max_page );
        ?>
    </div>