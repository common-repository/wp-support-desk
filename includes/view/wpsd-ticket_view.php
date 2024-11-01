<?php
/**
 * Ticket Info
 */

/**
 * Determine how come ticket_id, from ajax? or from url
 */

if ( ! empty($_POST['ticket_id'])) {
    $ticket_id = intval(sanitize_text_field($_POST['ticket_id']));
}else{
    $ticket_id = intval(sanitize_text_field($_GET['ticket_id']));
}

//Get wpsd user role
$wpsd_tickets_roles = get_option('wpneo_wpsd_user_roles');

$ticket = get_post($ticket_id);

$current_user_id = get_current_user_id();
$wpsd_ticket_user = get_wpsd_ticket_user_row($current_user_id);

if ($ticket->post_author >0) {
    $user = get_userdata($ticket->post_author);
    $ticket_owner = $user->display_name;
}else{
    $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
    $ticket_owner = $ticket_owner_details['wpsd_name']. " (Guest)";
}
$categories = get_the_taxonomies($ticket->ID);

?>

<div class="wpsd-title-wrapper wpsd-bd-bottom wpsd-msg-wrapper-title">
    <div class="wpsd-left">
        <img src="<?php echo get_avatar_url($ticket->post_author); ?>" alt="author img" class="wpsd-author-img">
    </div>
    <div class="wpsd-left">
        <h3 class="wpsd-title wpsd-left"><?php echo $ticket->post_title; ?></h3>
        <div class="wpsd-message-meta">
            <ul class="wpsd-inline">
                <li><span class="wpsd-author-name"><strong><?php echo $ticket_owner; ?></strong></span></li>
                <li><span class="wpsd-tag">
                        <?php
                        $get_department_id = get_post_meta($ticket_id, 'wpsd_department_id', true);
                        if ($get_department_id){
                            $deapartment_title = get_the_title($get_department_id);
                            echo $deapartment_title;
                        }
                        ?>
                    </span></li>
                <li><span class="wpsd-msg-date"> <?php echo date('F d, Y', strtotime($ticket->post_date)); ?></span></li>
                <li><span class="wpsd-msg-time"><?php echo date('H:i, A', strtotime($ticket->post_date)); ?></span></li>
            </ul>
        </div>
    </div>
    <div class="wpsd-msg-elements wpsd-right mtt10">
        <!--<a href="#"><i class="fa fa-download"></i></a>
        <a href="#"><i class="fa fa-print"></i></a>-->
        <?php
        $get_ticket_status = get_post_meta($ticket->ID, 'wpsd_ticket_status', true);
        if ($get_ticket_status == 'closed'){
            ?>
            <a class="wpsd_ticket_status_change_btn" href="javascript:;" title="<?php _e('Re-open', 'wp-support-desk'); ?>">
                <i class="fa fa-check-circle-o"></i>
            </a>
            <input type="hidden" id="wpsd_ticket_status_change" name="wpsd_ticket_status_change" value="re-open" />
            <?php
        } else{
            ?>
            <a class="wpsd_ticket_status_change_btn" href="javascript:;" title="<?php _e('Close Ticket', 'wp-support-desk'); ?>">
                <i class="fa fa-times-circle"></i>
            </a>
            <input type="hidden" id="wpsd_ticket_status_change" name="wpsd_ticket_status_change" value="closed" />
            <?php
        }
        ?>
    </div> <!-- //msg elements -->
</div> <!-- //title wrapper -->
<div class="wpsd-clearfix"></div>

<div class="wpsd-msg-details">
    <div class="wpsd-msg-info wpsd-bd-bottom">
        <?php echo wpsd_parse_md($ticket->post_content); ?>

        <?php
        $wpsd_ticket_custom_form_field = get_post_meta($ticket_id, 'wpsd_ticket_custom_form_field', true);
        if ($wpsd_ticket_custom_form_field){
            ?>
            <br /><br />
            <div class="wpsd-post-meta">
                <ul>
                    <?php
                    foreach ($wpsd_ticket_custom_form_field as $fkey => $fvalue){
                        echo "<li>{$fkey}: <strong>{$fvalue}</strong></li>";
                    }
                    ?>
                </ul>
            </div> <!--  //wpsd-post-meta -->
            <?php
        }
        ?>

        <div class="wpsd-has-attachment mtt30">
            <?php
            $attachments = get_attached_media('', $ticket->ID);
            if ( ! empty($attachments)){
                ?>
                <p class="wpsd-attachment-title wpsd-bd-bottom"><?php _e('Attachment', 'wp-support-desk'); ?></p>
                <ul class="wpsd-inline">
                    <?php
                    foreach ($attachments as $attachment){
                        $attachment_url = wp_get_attachment_url($attachment->ID);
                        echo "<li>  <a href='{$attachment_url}'> <i class='glyphicon glyphicon-paperclip'></i> {$attachment->post_title}</a></li>";
                    }
                    //wpsd_see_raw_data($attachments);
                    ?>
                </ul>
                <?php
            }
            ?>

        </div> <!-- //wpsd-has-attachment-->

        <div class="wpsd-msg-status">
            <ul class="wpsd-inline">
                <li>
                    <?php
                    $ticket_categories = get_the_terms($ticket->ID,'wpsd_priority');
                    if ($ticket_categories){
                        foreach ($ticket_categories as $current_category);
                        echo "<span style='color: ".get_term_meta($current_category->term_id, 'color', true).";'><i class='fa fa-circle'></i></span>";
                    }
                    ?>
                    <span class="wpsd-date-time" title="<?php echo get_the_date()." ". get_the_time(); ?>">
                        <?php echo human_time_diff( strtotime($ticket->post_date), current_time('timestamp') ) .' '. __('ago', 'wp-support-desk'); ?>
                    </span>
                </li>


                <li><?php echo __('Status', 'wp-support-desk').': '. str_replace('waiting', 'Waiting for reply', get_wpsd_ticket_status($ticket->ID)); ?></li>
            </ul>
        </div>
    </div> <!-- //wpsd-msg-info-->

    <div class="wpsd-forward-msgs mtb30">
        <div class="wpsd-msg-forward-form wpsd-bd-bottom">
            <div class="wpsd-author-img-wrap"><img src="<?php echo get_avatar_url($current_user_id) ?>" alt="author" class="wpsd-author-img"></div>

            <form action="#" id="wpsd_post_ticket_reply_from_admin" method="post" enctype="multipart/form-data">
                <textarea name="wpsd_ticket_replay_content" id="wpsd_ticket_replay_content" placeholder="<?php _e('Well , It doesn’t seem highty', 'wp-support-desk'); ?>"></textarea>
                <div class="wpsd-bottom-area">
                    <?php wp_nonce_field('wpsd_frontend_ticket_reply', 'wpsd_frontend_ticket_reply_form') ?>
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>" />
                    <input class="wpsd-btn wpsd-btn-sm wpsd-btn-primary" value="<?php _e('Post reply', 'wp-support-desk'); ?>" type="submit">
                    <?php do_action('wpsd_frontend_reply_form_end'); ?>
                </div>
            </form>

        </div> <!-- //wpsd-msg-forward-form-->

        <ul class="wpsd-common-list">
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

                if ($reply->post_author > 0) {
                    $ticket_owner = get_the_author_meta('display_name', $reply->post_author);
                }
                ?>

                <li class="wpsd-forward-msg">
                    <ul>
                        <li><img src="<?php echo $avatar_url; ?>" alt="author" class="wpsd-author-img"></li>
                        <li class="wpsd-author-name"><strong><?php echo $ticket_owner; ?></strong>
                            <?php if ($reply->post_author != $current_user_id){
                                $wpsd_ticket_user = get_wpsd_ticket_user_row($reply->post_author);
                                if (! empty($wpsd_tickets_roles[$wpsd_ticket_user->role])){
                                    echo "<span class='replied-role-badge'> {$wpsd_tickets_roles[$wpsd_ticket_user->role]} </span>";
                                }

                            } ?>
                            <span class="time" title="<?php echo get_the_date(null, $reply)." ". get_the_time(null, $reply); ?>"><?php echo human_time_diff( strtotime($reply->post_date), current_time('timestamp') ) . ' ago'; ?></span></li>
                    </ul>
                    <div class="wpsd-mtb10 wpsd-ticket-view-reply"> <?php echo wpsd_parse_md($reply->post_content); ?> </div>

                    <?php
                    $attachments = get_attached_media('', $reply->ID);
                    if ( ! empty($attachments)){
                        ?>
                        <div class="has-attachment">
                            <ul>
                                <?php

                                foreach ($attachments as $attachment){
                                    $attachment_url = wp_get_attachment_url($attachment->ID);
                                    $ext = pathinfo( $attachment_url, PATHINFO_EXTENSION );

                                    echo '<li>  '.wpsd_get_icon( $ext , $attachment_url , $attachment ).'</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                    }
                    ?>
                </li> <!-- //wpsd-forward-msg-->

            <?php endforeach;
            wp_reset_postdata();
            ?>

        </ul> <!-- wpsd-common-list -->
    </div> <!-- //wpsd-forward-msgs-->
</div> <!-- End main content -->

<?php do_action('wpsd_ticket_details_bottom', $ticket_id); ?>