<div class="wpsd-title-wrapper wpsd-bd-bottom">
    <h2 class="wpsd-title wpsd-left"><?php _e('Completed tickets', 'wp-support-desk'); ?></h2>
        <span class="delete-all wpsd-right">
            <a href="javascript:;" class="wpsd-ticket-batch-delete-btn"><i class="delete-icon fa fa-trash"></i></a>

            <label class="wpsd-checkbox">
                <input type="checkbox" id="wpsd-ticket-checked-all" value="second_checkbox" class="wpsd-ticket-checked-all"> 
                <div class="wpsd-checkbox-indicator"></div>
            </label>
        </span>
</div> <!-- //title wrapper -->
<div class="wpsd-clearfix"></div>

<?php
/**
 * Query logged in  user Ticket
 */
$page_numb = max( 1, sanitize_text_field(wpsd_post('current_page')) );
$user_ID = get_current_user_id();

$user_tickets_args = array(
    'author' => $user_ID,
    'post_type' => 'wpsd_tickets',
    'paged'             => $page_numb,
    'post_parent' => 0,
    'meta_query' => array(
        array(
            'key'     => 'wpsd_ticket_status',
            'value'   => 'closed',
            'compare' => '=',
        ),
    ),
);
$user_tickets = new WP_Query($user_tickets_args);

if ($user_tickets->have_posts()){
    ?>
    <ul class="wpsd-common-list">
        <?php
        while($user_tickets->have_posts()){ $user_tickets->the_post();
            global $post;
            $status = get_wpsd_ticket_status(get_the_ID()); ?>

            <li class="wpsd-msg  <?php echo ($status=='waiting')? 'wpsd-unread':''; ?>">
                <h3 class="wpsd-msg-title wpsd-left">
                    <a class="wpsd_ajax_ticket_view" data-ticket-id="<?php echo get_the_ID() ?>" href="<?php echo esc_url(add_query_arg(array('wpsd_page' => 'ticket_view', 'ticket_id' => get_the_ID()), $wpsd_page_url)); ?>">
                        <?php echo get_the_title(); ?>
                    </a>
                </h3>
                <span class="wpsd-msg-delete wpsd-right">
                    <label class="wpsd-checkbox">
                        <input type="checkbox" id="wpsd-ticket-check-<?php echo get_the_ID() ?>" value="<?php echo get_the_ID() ?>" class="wpsd-tickets-checkbox" name="wpsd_tickets_checkbox[]" />
                        <div class="wpsd-checkbox-indicator"></div>
                    </label>
                </span>
                <div class="wpsd-clearfix wpsd-mtb10"></div>

                <p class="wpsd-msg-info"><?php echo wp_trim_words(get_the_content()); ?></p>
                <div class="wpsd-post-meta">
                    <ul>
                        <li>
                            <span class="name-tilte"><?php _e('Posted By', 'wp-support-desk'); ?> </span>
                            <strong><?php the_author(); ?></strong>
                        </li>
                        <li>

                            <?php
                            $ticket_categories = get_the_terms(get_the_ID(),'wpsd_priority');
                            if ($ticket_categories){
                                foreach ($ticket_categories as $current_category);
                                echo "<span style='color: ".get_term_meta($current_category->term_id, 'color', true).";'><i class='fa fa-circle'></i></span>";
                            }
                            ?>
                            <span class="wpsd-date-time" title="<?php echo get_the_date()." ". get_the_time(); ?>">
                                <?php echo human_time_diff( strtotime($post->post_date), current_time('timestamp') ) .' '. __('ago', 'wp-support-desk'); ?>
                            </span>
                        </li>
                        <li class="wpsd-right">
                            <?php
                            $attachments = get_attached_media('', get_the_ID());
                            if (! empty($attachments)){
                                echo '<span class="wpsd-has-attachment"><i class="fa fa-paperclip"></i></span>';
                            }
                            ?>


                            <a class="wpsd_ajax_ticket_view" data-ticket-id="<?php echo get_the_ID() ?>" href="<?php echo esc_url(add_query_arg(array('wpsd_page' => 'ticket_view', 'ticket_id' => get_the_ID()), $wpsd_page_url)); ?>">
                                <span class="wpsd-msg-forward-icon"><i class="fa fa-mail-forward"></i></span>
                            </a>
                        </li>
                    </ul>
                </div> <!--  //wpsd-post-meta -->
            </li> <!-- //wpsd-msg -->
            <?php
        }
        $user_tickets->reset_postdata();
        ?>
    </ul> <!-- wpsd-common-list -->

    <div class="support-tickets">
        <?php
        $max_page = $user_tickets->max_num_pages;
        wpsd_pagination( $page_numb, $max_page );
        ?>
    </div>
    

<?php } else{
    ?>
    <div class="wpsd-alert-info">
        <h2> <i class="fa fa-exclamation-circle"></i> <?php _e('There is no ticket associate with your account', 'wp-support-desk'); ?></h2>
    </div>
    <?php
}
?>