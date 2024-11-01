<?php
$wpsd_options = get_option('wpsd_settings_option'); ?>

<form class="form-horizontal" method="post" action="">
    <?php wp_nonce_field('wpsd_settings_page_action', 'wpsd_settings_page_nonce_field'); ?>


    <div class="row">
        <div class="col-sm-3">
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <!--<li role="presentation" class="active"><a href="#general-tab" aria-controls="home" role="tab" data-toggle="tab"><?php /*_e('General', 'wp-support-desk'); */?></a></li>
                <li role="presentation"><a href="#tickets" aria-controls="profile" role="tab" data-toggle="tab"><?php /*_e('Tickets', 'wp-support-desk'); */?></a></li>-->
                <li role="presentation" class="active"><a href="#email_templates" aria-controls="profile" role="tab" data-toggle="tab"><?php _e('Email Templates', 'wp-support-desk'); ?></a></li>
            </ul>
        </div>

        <div class="col-sm-9">

            <div>

                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="email_templates">

                        <div class="row">
                            <div class="col-sm-12">

                                <div class="alert alert-info">
                                    <h4><?php _e('Available variable', 'wp-support-desk'); ?></h4>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p><strong><?php _e('Creating ticket', 'wp-support-desk'); ?></strong></p>
                                            <p>
                                                <code>{ticket_owner}</code> - <?php _e('Ticket Owner', 'wp-support-desk'); ?><br />
                                                <code>{ticket_owner_email}</code> - <?php _e('Customer Email', 'wp-support-desk'); ?><br />
                                                <code>{ticket_id}</code> - <?php _e('Ticket ID', 'wp-support-desk'); ?><br />
                                                <code>{ticket_subject}</code> - <?php _e('Ticket Subject', 'wp-support-desk'); ?><br />
                                                <code>{ticket_description}</code> - <?php _e('Ticket Description', 'wp-support-desk'); ?><br />
                                                <code>{ticket_priority}</code> - <?php _e('Ticket Priority', 'wp-support-desk'); ?><br />
                                                <code>{time_created}</code> - <?php _e('Ticket Created', 'wp-support-desk'); ?><br />
                                            </p>
                                        </div>

                                        <div class="col-sm-6">
                                            <p><strong><?php _e('Replied ticket', 'wp-support-desk'); ?></strong></p>
                                            <p>
                                                <code>{replied_by}</code> - <?php _e('Replied by', 'wp-support-desk'); ?><br />
                                                <code>{previous_replied_by}</code> - <?php _e('Previous replied by', 'wp-support-desk'); ?><br />
                                                <code>{replied_description}</code> - <?php _e('Ticket Description', 'wp-support-desk'); ?><br />
                                                <code>{time_replied}</code> - <?php _e('Ticket Created', 'wp-support-desk'); ?><br /><br />
                                            </p>

                                            <p><strong><?php _e('Assigned Ticket', 'wp-support-desk'); ?></strong></p>
                                            <p>
                                                <code>{assigned_to}</code> - <?php _e('Replied by', 'wp-support-desk'); ?><br />
                                                <code>{assigned_by}</code> - <?php _e('Previous replied by', 'wp-support-desk'); ?><br />
                                                <code>{assigned_time}</code> - <?php _e('Ticket Description', 'wp-support-desk'); ?><br />
                                            </p>

                                        </div>

                                        <div class="col-sm-12">
                                            <?php _e('You can also use all of your variable from creating ticket part', 'wp-support-desk'); ?>

                                        </div>
                                    </div>

                                </div>

                                <!-- #creating ticket email notification -->

                                <legend><?php _e('Creating Ticket', 'wp-support-desk'); ?></legend>
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Activate email', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline" for="wpsd_creating_ticket_email_activate1">
                                            <input type="radio" name="wpsd_settings_option[wpsd_creating_ticket_email_activate]" id="wpsd_creating_ticket_email_activate1" value="1" <?php echo wpsd_options('wpsd_creating_ticket_email_activate') ? 'checked':''; ?> > <?php _e('Yes', 'wp-support-desk'); ?>
                                        </label>
                                        <label class="radio-inline" for="wpsd_creating_ticket_email_activate0">
                                            <input type="radio" name="wpsd_settings_option[wpsd_creating_ticket_email_activate]" id="wpsd_creating_ticket_email_activate0" value="0" <?php echo wpsd_options('wpsd_creating_ticket_email_activate') ? '':'checked'; ?> > <?php _e('No', 'wp-support-desk') ?>
                                        </label>
                                        <p class="howto"><?php _e('Activate or deactivate email send during creating ticket', 'wp-support-desk'); ?></p>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Subject', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wpsd_settings_option[wpsd_email_creating_ticket_subject]" value="<?php echo wpsd_options('wpsd_email_creating_ticket_subject'); ?>" />
                                        <p class="howto"><?php _e('This will be appear in user email subject', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Email body', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <textarea name="wpsd_settings_option[wpsd_email_creating_ticket_body]" class="form-control" rows="10"> <?php echo wpsd_options('wpsd_email_creating_ticket_body'); ?></textarea>
                                        <p class="howto"><?php _e('This will be email body when a ticket has been created', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>



                                <!-- Replying ticket email notification -->
                                <legend><?php _e('Replying Ticket', 'wp-support-desk'); ?></legend>
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Activate email, for ticket owner', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline" for="wpsd_replied_ticket_email_activate1">
                                            <input type="radio" name="wpsd_settings_option[wpsd_replied_ticket_email_activate_ticket_owner]" id="wpsd_replied_ticket_email_activate1" value="1" <?php echo wpsd_options('wpsd_replied_ticket_email_activate_ticket_owner') ? 'checked':''; ?> > <?php _e('Yes', 'wp-support-desk'); ?>
                                        </label>
                                        <label class="radio-inline" for="wpsd_replied_ticket_email_activate0">
                                            <input type="radio" name="wpsd_settings_option[wpsd_replied_ticket_email_activate_ticket_owner]" id="wpsd_replied_ticket_email_activate0" value="0" <?php echo wpsd_options('wpsd_replied_ticket_email_activate_ticket_owner') ? '':'checked'; ?> > <?php _e('No', 'wp-support-desk') ?>
                                        </label>
                                        <p class="howto"><?php _e('Activate or deactivate if you wish to send email notification to ticket creator, or not', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Activate email, for ticket follower', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline" for="wpsd_replied_ticket_email_activate_ticket_follower1">
                                            <input type="radio" name="wpsd_settings_option[wpsd_replied_ticket_email_activate_ticket_follower]" id="wpsd_replied_ticket_email_activate_ticket_follower1" value="1" <?php echo wpsd_options('wpsd_replied_ticket_email_activate_ticket_follower') ? 'checked':''; ?> > <?php _e('Yes', 'wp-support-desk'); ?>
                                        </label>
                                        <label class="radio-inline" for="wpsd_replied_ticket_email_activate_ticket_follower0">
                                            <input type="radio" name="wpsd_settings_option[wpsd_replied_ticket_email_activate_ticket_follower]" id="wpsd_replied_ticket_email_activate_ticket_follower0" value="0" <?php echo wpsd_options('wpsd_replied_ticket_email_activate_ticket_follower') ? '':'checked'; ?> > <?php _e('No', 'wp-support-desk') ?>
                                        </label>
                                        <p class="howto"><?php _e('Activate or deactivate if you wish to send email notification to ticket creator, or not', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3"><?php _e('Subject for owner', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wpsd_settings_option[wpsd_email_replied_ticket_subject_owner]" value="<?php echo wpsd_options('wpsd_email_replied_ticket_subject_owner'); ?>" />
                                        <p class="howto"><?php _e('This subject will be at ticket creator email', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?php _e('Subject for ticket follower', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wpsd_settings_option[wpsd_email_replied_ticket_subject_following]" value="<?php echo wpsd_options('wpsd_email_replied_ticket_subject_following'); ?>" />
                                        <p class="howto"><?php _e('This subject will be at ticket follower email who is already replied, if any', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Email body, for ticket owner', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <textarea name="wpsd_settings_option[wpsd_email_replied_ticket_owner_body]" class="form-control" rows="10"> <?php echo wpsd_options('wpsd_email_replied_ticket_owner_body'); ?></textarea>
                                        <p class="howto"><?php _e('This will be email body when a ticket has been replied', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Email body, for ticket follower', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <textarea name="wpsd_settings_option[wpsd_email_replied_ticket_follower_body]" class="form-control" rows="10"> <?php echo wpsd_options('wpsd_email_replied_ticket_follower_body'); ?></textarea>
                                        <p class="howto"><?php _e('Ticket follower (who already replied this ticket before) will receive this body', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>





                                <!--  Assigned Ticket -->

                                <legend><?php _e('Assigned Ticket', 'wp-support-desk'); ?></legend>
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Activate email', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline" for="wpsd_assigned_ticket_email_activate1">
                                            <input type="radio" name="wpsd_settings_option[wpsd_assigned_ticket_email_activate]" id="wpsd_assigned_ticket_email_activate1" value="1" <?php echo wpsd_options('wpsd_assigned_ticket_email_activate') ? 'checked':''; ?> > <?php _e('Yes', 'wp-support-desk'); ?>
                                        </label>
                                        <label class="radio-inline" for="wpsd_assigned_ticket_email_activate0">
                                            <input type="radio" name="wpsd_settings_option[wpsd_assigned_ticket_email_activate]" id="wpsd_assigned_ticket_email_activate0" value="0" <?php echo wpsd_options('wpsd_assigned_ticket_email_activate') ? '':'checked'; ?> > <?php _e('No', 'wp-support-desk') ?>
                                        </label>
                                        <p class="howto"><?php _e('Activate or deactivate email send during assigned ticket', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Subject', 'wp-support-desk') ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wpsd_settings_option[wpsd_email_assigned_ticket_subject]" value="<?php echo wpsd_options('wpsd_email_assigned_ticket_subject'); ?>" />
                                        <p class="howto"><?php _e('This will be appear in assigned ticket email subject', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user" class="control-label col-md-3"><?php _e('Email body', 'wp-support-desk') ?></label>

                                    <div class="col-sm-9">
                                        <textarea name="wpsd_settings_option[wpsd_email_assigned_ticket_body]" class="form-control" rows="10"> <?php echo wpsd_options('wpsd_email_assigned_ticket_body'); ?></textarea>
                                        <p class="howto"><?php _e('This will be email body when a ticket has been assigned', 'wp-support-desk'); ?></p>
                                    </div>
                                </div>



                            </div>

                        </div> <!-- .row -->
                    </div> <!-- #tickets -->


                    <div role="tabpanel" class="tab-pane" id="messages">Messages</div>
                    <div role="tabpanel" class="tab-pane" id="settings">Settings</div>


                    <div class="row">
                        <hr />
                        <div class="col-sm-6 col-sm-offset-3">
                            <button type="submit" class="btn btn-primary" name="wpsd_settings_save_btn" value="save_settings"> <i class="glyphicon glyphicon-floppy-disk"></i> <?php _e('Save', 'wp-support-desk'); ?> </button>
                        </div>
                    </div>


                </div>

            </div>
        </div>

    </div>

</form>