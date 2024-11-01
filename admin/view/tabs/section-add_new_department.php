<form id="wpsd-settings-form" class="form-horizontal" role="form" method="post" action="">
    <?php wp_nonce_field('wpsd_settings_page_action', 'wpsd_settings_page_nonce_field'); ?>

    <?php
        $title = $description = '';
        if( isset($_GET['p_id']) ){
            $title = get_the_title( $_GET['p_id'] );
            $description = apply_filters('the_content', get_post_field('post_content', $_GET['p_id'] ));
        }
    ?>

    <div class="form-group">
        <label for="department_name" class="control-label col-md-3"><?php _e('Department name', 'wp-support-desk') ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="department_name" id="department_name" value="<?php echo $title; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="department_description" class="control-label col-md-3"><?php _e('Description', 'wp-support-desk') ?></label>
        <div class="col-md-9">
            <textarea name="department_description" class="form-control" id="department_description"><?php echo strip_tags( $description ); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php if( isset($_GET['p_id']) ){ echo '<input type="hidden" name="department_edit" value="'.$_GET['p_id'].'"  />'; } ?>
            <?php submit_button(__('Save department', 'wp-support-desk'),'primary','wpneo_wpsd_department_save_btn'); ?>
        </div>
    </div>
    
</form>
