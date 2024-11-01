<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <span class="badge"><?php echo wpsd_count_ticket_status('pending'); ?> </span> Pending tickets
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <span class="badge badge-info"><?php echo wpsd_count_ticket_status('open'); ?></span> In Discussion
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <span class="badge badge-success"><?php echo wpsd_count_ticket_status('closed'); ?></span> Completed tickets
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <div class="caption">
                <a href="<?php echo esc_url(add_query_arg(array('wpsd_action' => 'create_new_ticket'))); ?>">
                    <h3><?php _e('Create new ticket', 'wp-support-desk'); ?></h3>
                    <p><?php _e(sprintf('Create ticket under %s department', $department_title), 'wp-support-desk'); ?></p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Query logged in  user Ticket
 */

$user_tickets_args = array(
    'author' => $user_ID,
    'post_type' => 'wpsd_tickets',
    'post_parent' => 0,
    'meta_query' => array(
        array(
            'key'     => 'wpsd_department_id',
            'value'   => $department_id,
            'compare' => '=',
        ),
    ),
);
$user_tickets = new WP_Query($user_tickets_args);


/**
 * Looping Start
 */

if ($user_tickets->have_posts()){
    ?>
    <table class="table table-bordered table-striped">
        <tr>
            <th><?php _e('Subject', 'wp-support-desk'); ?></th>
            <th><?php _e('Created at', 'wp-support-desk'); ?></th>
        </tr>

        <?php
        while($user_tickets->have_posts()){ $user_tickets->the_post(); ?>
            <tr>
                <td> <a href="<?php echo esc_url(add_query_arg(array('wpsd_page' => 'ticket_view', 'ticket_id' => get_the_ID()), $wpsd_page_url)); ?>">  <?php echo get_the_title(); ?> </a>
                    <strong>
                        <?php $status = get_wpsd_ticket_status(get_the_ID()); if ($status) echo "({$status})"; ?>
                    </strong>
                    <br /> <br />
                    <?php _e('Category', 'wp-support-desk'); ?> :

                    <?php
                    $ticket_categories = get_the_terms(get_the_ID(),'wpsd_category');
                    if ($ticket_categories){
                        foreach ($ticket_categories as $current_category);
                        echo "<span style='color: ".get_term_meta($current_category->term_id, 'color', true).";'>{$current_category->name}</span>";
                    }
                    ?> <br />

                    <?php _e('Priority', 'wp-support-desk'); ?> :

                    <?php
                    $ticket_categories = get_the_terms(get_the_ID(),'wpsd_priority');
                    if ($ticket_categories){
                        foreach ($ticket_categories as $current_category);
                        echo "<span style='color: ".get_term_meta($current_category->term_id, 'color', true).";'>{$current_category->name}</span>";
                    }
                    ?>

                </td>

                <td>

                    <span  data-toggle="tooltip" data-placement="top" title="<?php echo get_the_date()." ". get_the_time(); ?>">
                        <?php echo human_time_diff( strtotime(get_the_date()), current_time('timestamp') ) . ' ago'; ?>
                    </span>

                </td>

            </tr>
            <?php
        }
        $user_tickets->reset_postdata();
        ?>
    </table>

<?php } else{
    ?>
    <div class="alert alert-info">
        <h2> <i class="glyphicon glyphicon-exclamation-sign"></i> <?php _e('There is no ticket associate with '.$department_title, 'wp-support-desk'); ?></h2>
    </div>
    <?php
}