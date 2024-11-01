
<!-- Start Main Wrapper -->
<div class="wpsd-login-wrapper">
    <h1 class="wpsd-title">WP SUPPORT DESK</h1>
    <h4 class="wpsd-subtitle"><?php _e('Create an account for new user', 'wp-support-desk'); ?></h4>

    <div id="wpsd_login_status_msg"></div>

    <form action="<?php the_permalink(); ?>" id="wpsd-register" class="wpsd-login" method="post">
        <P><input type="text" name="user_name" placeholder="<?php _e('User Name', 'wp-support-desk'); ?>"></P>
        <P><input type="email" name="email" placeholder="<?php _e('Email', 'wp-support-desk'); ?>"></P>
        <?php do_action('wpsd_after_registration_form_field'); ?>

        <P id="wpsdRegisterBtnWrap">
            <input type="submit" name="wpsd_registration_btn" value="Register" class="wpsd-left wpsd-btn wpsd-btn-success wpsd-btn-radius" />
            <a href="javascript:;" class="wpsdBackToLoginFormLink"><i class="fa fa-unlock-alt"></i> <?php _e('Back to login', 'wp-support-desk'); ?></a>
        </P>

    </form>
</div> <!-- End main wrapper -->
