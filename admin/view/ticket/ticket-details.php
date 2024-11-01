<?php
global $post;
$current_user_id            = get_current_user_id();
$wpsd_ticket_user           = get_wpsd_ticket_user_row($current_user_id);

if ($post->post_author >0) {
    $user                   = get_userdata($post->post_author);
    $ticket_owner           = $user->display_name;
} else {
    $ticket_owner_details   = get_post_meta($post->ID, 'wpsd_guest_info', true);
    $ticket_owner           = $ticket_owner_details['wpsd_name']. " (Guest)";
}
$categories                 = get_the_taxonomies($post->ID);
?>
<div class="wpsd-admin-ticket-details-wrap">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="content">
                <h2 class="header">
                    <?php echo $post->post_title; ?>
                    <span class="pull-right">
                    <button data-target="#ticket-edit-modal" data-toggle="modal" class="btn btn-info" type="button"> Edit </button>
                    <?php if ( (bool) $wpsd_ticket_user->delete_tickets){ ?>
                        <a class="btn btn-danger" href="<?php echo get_delete_post_link($post->ID) ?>"><?php _e('Delete', 'wp-support-desk'); ?></a>
                    <?php } ?>
                </span>
                </h2>
                <div class="panel well well-sm">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <p><strong><?php _e('Owner', 'wp-support-desk'); ?></strong>: <?php echo $ticket_owner; ?></p>
                                <p>
                                    <strong><?php _e('Status', 'wp-support-desk'); ?></strong>:
                                    <span style="color: #549943">ReSolved</span>
                                </p>

                                <?php
                                if (count($categories) > 0){
                                    foreach ($categories as $key => $value){
                                        $get_the_terms = get_the_terms($post->ID, $key);

                                        foreach ($get_the_terms as $attached_term){
                                            echo "<p><strong>".ucfirst(str_replace('wpsd_','',$attached_term->taxonomy))."</strong> : <span style='color: ".get_term_meta($attached_term->term_id, 'color', true).";'>{$attached_term->name}</span></p>";
                                        }
                                    }
                                }
                                ?>

                            </div>
                            <div class="col-md-6">
                                <p> <strong><?php _e('Created At', 'wp-support-desk'); ?></strong>: <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></p>
                                <p> <strong>Last Update</strong>: <?php echo human_time_diff( get_post_modified_time('U'), current_time('timestamp') ) . ' ago'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="wpsd-ticket-description">
                        <?php echo $post->post_content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $reply_args = array(
        'posts_per_page' => -1,
        'post_type'=> 'wpsd_tickets',
        'post_parent' => $post->ID,
        'order'            => 'ASC',
    );
    $replies = get_posts($reply_args);
    foreach ( $replies as $reply ) : setup_postdata( $reply );
        $avatar_url = get_avatar_url($reply->post_author);
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="ticket-reply-heading">
                            <div class="pull-left image">
                                <img src="<?php echo $avatar_url; ?>" class="img-circle avatar" alt="user profile image">
                            </div>
                            <div class="pull-left meta">
                                <div class="title h5">
                                    <a href="#"><b><?php the_author() ?></b></a>
                                </div>
                                <h6 class="text-muted time"><?php echo human_time_diff( strtotime($reply->post_date), current_time('timestamp') ) . ' ago'; ?></h6>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="ticket-reply-description"><?php the_content(); ?></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach;
    wp_reset_postdata();
    ?>
    <div class="panel panel-default">
        <form class="form-horizontal">
            <div class="panel-body">
                <div class="form-group">
                    <?php wp_editor(null, 'wpsd_ticket_replay_content', array('media_buttons' => false, 'textarea_rows'=>4)) ?>
                </div>
            </div>
            <div class="panel-footer">
                <button class="btn btn-primary pull-right">Reply</button>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div>