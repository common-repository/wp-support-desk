
<div class="wpsd-main">
    <div class="wpsd-container wpsd-center mtt50 mtb50">

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
        </div>
        <!-- End main wrapper -->
    </div> <!--  wpsd-container -->
</div> <!--  wpsd-main -->


<!--<div class="page-header">
    <?php
/*    echo "<h1>".__('Tickets Manager', 'wp-support-desk')."</h1>";
    */?>
</div>
<p>
    <strong><?php /*_e('Track the progress of your request', 'wp-support-desk'); */?></strong> <br />
    <?php /*_e('If you are already a registered user, it is recommended you login to the members area to track a support issue.', 'wp-support-desk'); */?>
</p>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="" id="wpsd_track_ticket_guest">
            <div class="form-group">
                <label for="ticket_id" class="col-sm-4 control-label"><?php /*_e('Ticket ID', 'wp-support-desk'); */?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ticket_id" name="ticket_id" placeholder="<?php /*_e('Ticket ID', 'wp-support-desk'); */?>"  data-validation="required" >
                </div>
            </div>
            <div class="form-group">
                <label for="wpsd_track_ticket_email" class="col-sm-4 control-label"><?php /*_e('Email', 'wp-support-desk'); */?></label>
                <div class="col-sm-8">
                    <input type="email" name="email" class="form-control" id="wpsd_track_ticket_email" placeholder="<?php /*_e('Email', 'wp-support-desk'); */?>"  data-validation="required" >
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-default" id="wpsd-guest-ticket-track-btn"><?php /*_e('Track your ticket', 'wp-support-desk'); */?></button>
                </div>
            </div>
        </form>

    </div>
</div>

-->