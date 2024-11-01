<?php
$page_numb = 1;
if( isset($_GET['paged']) ){
    $page_numb = $_GET['paged'];
}
$departments_query = wpsd_get_departments_query( $page_numb );
?>

<?php if ($departments_query->have_posts()) :?>

    <div id="assignedStatusMsg"></div>
    <table class="table table-striped wpsd-table">

        <thead>
            <tr>
                <th width="50">#</th>
                <th><?php _e('Name', 'wp-support-desk') ?></th>
                <th><?php _e('Description', 'wp-support-desk') ?></th>
                <th><?php _e('Action', 'wp-support-desk'); ?></th>
            </tr>
        </thead>

        <?php
        $inc = 0;
        while($departments_query->have_posts()) : $departments_query->the_post(); $inc++; ?>
            <tr>
                <td><?php echo $inc; ?></td>
                <td> <?php the_title(); ?> </td>
                <td> <?php echo strip_tags( get_the_content() ); ?> </td>
                <td>
                    <a href="<?php echo  admin_url("admin.php?page=wp-support-desk-settings&tab=departments&section=add_new_department&p_id=".get_the_ID()); ?>" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a href="javascript:;" class="btn btn-danger btn-xs wpsd-delete-department" data-department-id="<?php echo get_the_ID(); ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
            </tr>
        <?php endwhile; ?>

        <tfoot>
            <tr>
                <th width="50">#</th>
                <th><?php _e('Name', 'wp-support-desk') ?></th>
                <th><?php _e('Description', 'wp-support-desk') ?></th>
                <th><?php _e('Action', 'wp-support-desk'); ?></th>
            </tr>
        </tfoot>
    </table>

    <?php
        $max_page = $departments_query->max_num_pages;
        wpsd_pagination( $page_numb, $max_page );
    ?>

<?php endif; ?>