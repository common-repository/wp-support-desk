<?php
$departments_query = wpsd_get_departments_query();
?>
<div class="row">
    <?php if ($departments_query->have_posts()) {
        while($departments_query->have_posts()) { $departments_query->the_post();
            global $post;
            ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail department-thumb">
                    <div class="caption">
                        <a href="<?php echo esc_url(add_query_arg(array('wpsd_page' => 'departments', 'wpsd_department'=>$post->post_name), $wpsd_page_url)); ?>" class="wpsd_ajax_load_department">
                            <h1><i class="glyphicon glyphicon-folder-open"></i> </h1>
                            <h4><?php echo get_the_title() ?></h4>
                            <p><?php echo get_the_content(); ?></p>
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>