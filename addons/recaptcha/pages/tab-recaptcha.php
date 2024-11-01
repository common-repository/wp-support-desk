<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>


<form class="form-horizontal" method="post" action="">
    <?php wp_nonce_field('wpsd_settings_page_action', 'wpsd_settings_page_nonce_field'); ?>

    <div class="row">
        <div class="col-sm-3">
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <li role="presentation" class="active"><a href="#wpsd_recaptcha_settings" aria-controls="wpsd_recaptcha_settings" role="tab" data-toggle="tab"><?php _e('Settings', 'wp-support-desk'); ?></a></li>
                <li role="presentation"><a href="#wpsd_recaptcha_implementation" aria-controls="wpsd_recaptcha_implementation" role="tab" data-toggle="tab"><?php _e('Implementation', 'wp-support-desk'); ?></a></li>
            </ul>
        </div>

        <div class="col-sm-9">

            <div>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="wpsd_recaptcha_settings">

                        <div class="row">
                            <div class="col-sm-12">
                                <!-- #creating ticket email notification -->

                                <legend><?php _e('reCaptcha General Settings', 'wp-support-desk'); ?></legend>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Enable / Disable', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <label>
                                            <input type="checkbox" name="wpsd_enable_recaptcha" value="1" <?php echo wpsd_recaptcha_options('wpsd_enable_recaptcha') == 1 ?  'checked="checked" ':''; ?> /> <?php _e('Enable Google reCaptcha to WP Support Desk'); ?>
                                        </label>
                                        <p class="howto"><?php _e('Mail server address, something like imap.gmail.com', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Site key / Public Key', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wpsd_recaptcha_site_key" value="<?php echo wpsd_recaptcha_options('wpsd_recaptcha_site_key'); ?>" placeholder="<?php _e('reCaptcha site key / public key', 'wp-support-desk'); ?>" />
                                        <p class="howto"><?php echo sprintf(__('Put your Google reCAPTCHA public key here. %s Visit this link %s to generate one.', 'wp-support-desk'), '<a href="https://www.google.com/recaptcha/admin#list" target="_blank">', '</a>') ; ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Secret Key', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wpsd_recaptcha_secret_key" value="<?php echo wpsd_recaptcha_options('wpsd_recaptcha_secret_key'); ?>" placeholder="<?php _e('reCaptcha secret key', 'wp-support-desk'); ?>" />
                                        <p class="howto"><?php echo sprintf(__('Put your Google reCAPTCHA secret key here. %s Visit this link %s to generate one.', 'wp-support-desk'), '<a href="https://www.google.com/recaptcha/admin#list" target="_blank">', '</a>') ; ?></p>
                                    </div>
                                </div>

                                <hr />

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"></label>

                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-primary" name="wpsd_recaptcha_settings_save_btn" value="wpsd_recaptcha_save_settings"> <i class="glyphicon glyphicon-floppy-disk"></i> <?php _e('Save', 'wp-support-desk'); ?> </button>
                                    </div>
                                </div>

                            </div>

                        </div> <!-- .row -->

                    </div> <!-- #wpsd_recaptcha_settings -->

                    <div role="tabpanel" class="tab-pane" id="wpsd_recaptcha_implementation">

                        <div class="row">
                            <div class="col-sm-12">

                                <legend><?php _e('Implementation Settings', 'wp-support-desk'); ?></legend>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Enable / Disable', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <label>
                                            <input type="checkbox" name="wpsd_enable_recaptcha_in_login_form" value="1"  <?php echo wpsd_recaptcha_options('wpsd_enable_recaptcha_in_login_form') == 1 ?  'checked="checked" ':''; ?>  /> <?php _e('Enable Google reCaptcha to WP Support Desk Login Form'); ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Enable / Disable', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <label>
                                            <input type="checkbox" name="wpsd_enable_recaptcha_in_registration_form" value="1"  <?php echo wpsd_recaptcha_options('wpsd_enable_recaptcha_in_registration_form') == 1 ?  'checked="checked" ':''; ?>  /> <?php _e('Enable Google reCaptcha to WP Support Desk Registration Form'); ?>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Enable / Disable', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <label>
                                            <input type="checkbox" name="wpsd_enable_recaptcha_in_ticekt_creating_form" value="1"  <?php echo wpsd_recaptcha_options('wpsd_enable_recaptcha_in_ticekt_creating_form') == 1 ?  'checked="checked" ':''; ?>  /> <?php _e('Enable Google reCaptcha to in ticket creating form'); ?>
                                        </label>
                                    </div>
                                </div>
                                
                                <hr />
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"></label>

                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-primary" name="wpsd_recaptcha_settings_save_btn" value="wpsd_recaptcha_save_settings"> <i class="glyphicon glyphicon-floppy-disk"></i> <?php _e('Save', 'wp-support-desk'); ?> </button>
                                    </div>
                                </div>

                            </div>

                        </div>


                    </div> <!-- #wpsd_recaptcha_implementation -->

                </div>

            </div>
        </div>

    </div>

</form>
