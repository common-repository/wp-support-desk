<?php
/**
 * Ticket Info
 */

$ticket_id                  = sanitize_text_field($_POST['ticket_id']);
$wpsd_tickets_roles         = get_option('wpneo_wpsd_user_roles');
$ticket                     = get_post($ticket_id);
$current_user_id            = get_current_user_id();
$wpsd_ticket_user           = get_wpsd_ticket_user_row($current_user_id);

if ($ticket->post_author >0) {
    $user                   = get_userdata($ticket->post_author);
    $ticket_owner           = $user->display_name;
}else{
    $ticket_owner_details   = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
    $ticket_owner           = $ticket_owner_details['wpsd_name']. " (Guest)";
}
$categories                 = get_the_taxonomies($ticket->ID);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="header-lined">
            <div class="row">
                <div class="col-sm-7">
                    <h1><?php _e('View Ticket', 'wp-support-desk'); ?>
                        <a id="wpsd_create_new_ticket_admin" href="javascript:;" class="btn btn-info btn-sm">
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
                <li><a href="javascript:;" class="wpsd-load-tickets-home-admin"> <?php _e('WP Support Desk Home', 'wp-support-desk'); ?></a></li>
                <li><a href="javascript:;" class="wpsd-load-tickets-home-admin"> <?php _e('Support Tickets', 'wp-support-desk'); ?></a></li>
                <li class="active"><?php _e('View Ticket', 'wp-support-desk'); ?></li>
            </ol>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 class="header">
                    <?php echo $ticket->post_title; ?>
                    <span class="pull-right">
                        <?php
                        $get_ticket_status = get_post_meta($ticket->ID, 'wpsd_ticket_status', true);
                        if ($get_ticket_status == 'closed'){
                            ?>
                            <a class="btn btn-info wpsd_ticket_status_change_btn" href="javascript:;">
                                <i class="glyphicon glyphicon-folder-open"></i> <?php _e('Re Open', 'wp-support-desk'); ?>
                            </a>
                            <input type="hidden" id="wpsd_ticket_status_change" name="wpsd_ticket_status_change" value="re-open" />
                            <?php
                        } else{
                            ?>
                            <a class="btn btn-success wpsd_ticket_status_change_btn" href="javascript:;">
                                <i class="glyphicon glyphicon-folder-close"></i> <?php _e('Close Ticket', 'wp-support-desk'); ?>
                            </a>
                            <input type="hidden" id="wpsd_ticket_status_change" name="wpsd_ticket_status_change" value="closed" />
                            <?php
                        }
                        ?>
                        <?php if ( (bool) $wpsd_ticket_user->delete_tickets){ ?>
                            <a class="btn btn-danger" href="<?php echo get_delete_post_link($ticket->ID); ?>"><?php _e('Delete', 'wp-support-desk'); ?></a>
                        <?php } ?>
                    </span>
                </h3>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong><?php _e('Posted by', 'wp-support-desk'); ?></strong>: <?php echo $ticket_owner ?></p>
                        <?php
                        if (count($categories) > 0){
                            foreach ($categories as $key => $value){
                                $get_the_terms = get_the_terms($ticket->ID, $key);
                                foreach ($get_the_terms as $attached_term){
                                    echo "<p><strong>".ucfirst(str_replace('wpsd_','',$attached_term->taxonomy))."</strong> : <span style='color: ".get_term_meta($attached_term->term_id, 'color', true).";'>{$attached_term->name}</span></p>";
                                }
                            }
                        }

                        $get_department_id = get_post_meta($ticket_id, 'wpsd_department_id', true);
                        if ($get_department_id){
                            $deapartment_title = get_the_title($get_department_id);
                            echo "<p><strong>".__('Department', 'wp-support-desk')." </strong>: {$deapartment_title}</p>";
                        }

                        $wpsd_ticket_custom_form_field = get_post_meta($ticket_id, 'wpsd_ticket_custom_form_field', true);
                        if ($wpsd_ticket_custom_form_field){
                            echo "<hr />";
                            foreach ($wpsd_ticket_custom_form_field as $fkey => $fvalue){
                                echo "<p><strong>{$fkey}</strong>: {$fvalue}</p>";
                            }
                        }
                        ?>

                    </div>
                    <div class="col-md-6">
                        <p><strong><?php _e('Created At', 'wp-support-desk'); ?></strong>: <?php echo human_time_diff( strtotime($ticket->post_date), current_time('timestamp') ) . ' ago'; ?></p>
                        <p><strong>Last Update</strong>: <?php echo human_time_diff( strtotime($ticket->post_modified), current_time('timestamp') ) . ' ago'; ?></p>
                        <p><strong>Status : </strong> <?php echo get_wpsd_ticket_status($ticket->ID) ?> </p>
                    </div>
                </div>
                <hr />
                <div class="wpsd-ticket-description">
                    <?php echo wpsd_parse_md($ticket->post_content); ?>
                </div>
            </div>
            <?php
            $attachments = get_attached_media('', $ticket->ID);
            if ( ! empty($attachments)){
                ?>
                <div class="panel-footer">
                    <ul class="wpsd-attachments-list">
                        <?php
                        foreach ($attachments as $attachment){
                            $attachment_url = wp_get_attachment_url($attachment->ID);
                            echo "<li>  <a href='{$attachment_url}'> <i class='glyphicon glyphicon-paperclip'></i> {$attachment->post_title}</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        $reply_args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'wpsd_tickets',
            'post_parent'       => $ticket->ID,
            'order'             => 'ASC',
        );
        $replies = get_posts($reply_args);
        foreach ( $replies as $reply ) : setup_postdata( $reply );
            $avatar_url = get_avatar_url($reply->post_author);
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="tickets-replied panel panel-default">
                        <div class="panel-body">
                            <div class="ticket-reply-heading">
                                <div class="pull-left image">
                                    <?php echo get_avatar($reply->post_author, 50); ?>
                                </div>
                                <div class="pull-left meta">
                                    <div class="title h5">
                                        <b><?php the_author() ?></b>
                                        <?php if ($reply->post_author != $current_user_id){
                                            $wpsd_ticket_user = get_wpsd_ticket_user_row($reply->post_author);
                                            if (! empty($wpsd_tickets_roles[$wpsd_ticket_user->role])){
                                                echo "<span class='replied-role-badge'> {$wpsd_tickets_roles[$wpsd_ticket_user->role]} </span>";
                                            }
                                        } ?>
                                    </div>
                                    <h6 class="text-muted time">
                                        <span  data-toggle="tooltip" data-placement="top" title="<?php echo get_the_date(null, $reply)." ". get_the_time(null, $reply); ?>">
                                            <?php echo human_time_diff( strtotime($reply->post_date), current_time('timestamp') ) . ' ago'; ?>
                                        </span>
                                    </h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="ticket-reply-description">
                                <?php echo wpsd_parse_md(get_the_content()); ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        $attachments = get_attached_media('', $reply->ID);
                        if ( ! empty($attachments)){
                            ?>
                            <div class="panel-footer">
                                <ul class="wpsd-attachments-list">
                                    <?php
                                    foreach ($attachments as $attachment){
                                        $attachment_url = wp_get_attachment_url($attachment->ID);
                                        $ext = pathinfo( $attachment_url, PATHINFO_EXTENSION );
                                        echo '<li>'.wpsd_get_icon( $ext , $attachment_url , $attachment ).'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach;
        wp_reset_postdata();
        ?>
        <div class="panel panel-default">
            <form id="wpsd_post_ticket_reply_from_admin" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <textarea name="wpsd_ticket_replay_content" id="wpsd_ticket_replay_content" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <?php do_action('wpsd_reply_ticket_end_of_form_admin'); ?>
                    <?php wp_nonce_field('wpsd_frontend_ticket_reply', 'wpsd_frontend_ticket_reply_form') ?>
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>" />
                    <button class="btn btn-primary pull-right wpsd_admin_reply_btn" type="submit"> <i class="glyphicon glyphicon-floppy-disk"></i> <?php _e('Post reply', 'wp-support-desk'); ?></button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-3">
        <?php include WPSD_PLUGIN_DIR.'admin/view/tickets/ticket-assign-metabox.php'; ?>
    </div>
</div>




