<!-- Start Main Wrapper -->
<div class="wpsd-login-wrapper">
    <h1 class="wpsd-title">WP SUPPORT DESK</h1>
    <h4 class="wpsd-subtitle"><?php _e('Sign In to get greater access', 'wp-support-desk'); ?></h4>

    <div id="wpsd_login_status_msg"></div>

    <form action="<?php the_permalink(); ?>" id="wpsd-login" class="wpsd-login" method="post">
        <P><input type="text" name="log" placeholder="<?php _e('User Name', 'wp-support-desk'); ?>"></P>
        <P><input type="password" name="pwd" placeholder="<?php _e('Password', 'wp-support-desk'); ?>" /></P>
        <P>
            <label class="wpsd-checkbox">
                <input name="rememberme" id="rememberme" value="forever" type="checkbox">
                <span class="wpsd-checkbox-indicator"></span>
                <?php _e('Remember Me', 'wp-support-desk'); ?>
            </label>
        </P>
        <?php do_action('wpsd_after_login_form'); ?>
        <P id="wpsdLoginBtnWrap">
            <input type="submit" name="wpsd_login_btn" value="LOGIN" class="wpsd-left wpsd-btn wpsd-btn-success wpsd-btn-radius" />
            <a href="javascript:;" class="wpsdCreateAccountLink"><i class="fa fa-user-circle-o"></i> <?php _e('Create an account?', 'wp-support-desk'); ?></a>
            <!--<span class="wpsd-left wpsd-forget-text">Forgot <a href="#">Username</a> or <a href="#">Password?</a></span>-->
        </P>
    </form>
</div> <!-- End main wrapper -->