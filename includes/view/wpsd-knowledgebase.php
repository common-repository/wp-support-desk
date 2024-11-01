<div class="row">

    <div class="col-sm-12">
        <div class="header-lined">
            <h1><?php _e('Knowledgebase', 'wp-support-desk'); ?></h1>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:;" class="wpsd_load_home"> <i class="glyphicon glyphicon-home"></i> WP Support Desk <?php _e('Home', 'wp-support-desk'); ?></a>
                </li>
                <li class="active">
                    <?php _e('Knowledgebase', 'wp-support-desk'); ?>
                </li>
            </ol>
        </div>

        <form role="form" method="post" action="">
            <div class="input-group">
                <input name="wpsd_kb_search" id="wpsd_kb_search" class="form-control" placeholder="<?php _e('Have a question? Start your search here.', 'wp-support-desk'); ?>" type="text">
                <span class="input-group-btn">
                    <input class="btn btn-primary wpsd_kb_search_btn" value="Search" type="submit">
                </span>
            </div>
        </form>

        <h2>Categories</h2>

        <div class="row kbcategories">

            <?php
            $wpsd_kb_categories = get_categories(array('taxonomy'=>'wpsd_kb_categories', 'hide_empty' => 0));
            //wpsd_see_raw_data($wpsd_kb_categories);
            if (count($wpsd_kb_categories) > 0){
                foreach($wpsd_kb_categories as $kbc){
                    ?>
                    <div class="col-sm-4">
                        <a href="javascript:;" class="wpsd_load_kb_category kb_category_<?php echo $kbc->term_id; ?>" data-kb-category-id="<?php echo $kbc->term_id; ?>">
                            <span class="glyphicon glyphicon-folder-close"></span> <?php echo $kbc->name; ?><span class="badge"><?php echo $kbc->count; ?></span>
                        </a>
                        <p><?php echo $kbc->description; ?></p>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

    </div>

</div>