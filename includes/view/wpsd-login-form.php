
<div class="alert alert-warning">
    <i class="glyphicon glyphicon-exclamation-sign"></i>
    <?php _e('Sign In to get greater access and functionalities', 'wp-support-desk'); ?>
</div>

<div class="row">
    <div class="col-sm-12">
        <form class="form-inline" method="post" action="<?php echo site_url('wp-login.php'); ?>">
            <div class="form-group">
                <label class="sr-only" for="user_login"><?php _e('Username or Email', 'wp-support-desk'); ?></label>
                <input type="text" class="form-control"  name="log" id="user_login" placeholder="<?php _e('Username or Email', 'wp-support-desk'); ?>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="user_pass"><?php _e('Password', 'wp-support-desk'); ?></label>
                <input type="password" class="form-control" id="user_pass" name="pwd" placeholder="<?php _e('Password', 'wp-support-desk'); ?>">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="rememberme" id="rememberme" value="forever"> <?php _e('Remember me', 'wp-support-desk'); ?>
                </label>
            </div>
            <button type="submit" name="wp-submit" class="btn btn-info"> <i class="glyphicon glyphicon-log-in"></i> <?php _e('Sign in', 'wp-support-desk'); ?></button>
            <input name="redirect_to" value="<?php echo $wpsd_redirect_back_url; ?>" type="hidden">
        </form>
    </div>
</div>

<hr />