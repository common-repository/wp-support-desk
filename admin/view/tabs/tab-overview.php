<h1><?php _e('Overview', 'wp-support-desk'); ?></h1>


<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <h3 class="huge"><?php echo wpsd_count_ticket_status_global('pending'); ?></h3>
                        <div><?php _e('Pending Tickets', 'wp-support-desk'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <h3 class="huge"><?php echo wpsd_count_ticket_status_global('open'); ?></h3>
                        <div><?php _e('In-Discussion Tickets', 'wp-support-desk'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <h3 class="huge"><?php echo wpsd_count_ticket_status_global('closed'); ?></h3>
                        <div><?php _e('Closed Tickets', 'wp-support-desk'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <h3 class="huge"><?php $result = count_users();
                            echo $result['total_users']; ?></h3>
                        <div><?php _e('Total Users', 'wp-support-desk'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php do_action('wpsd_after_settings_overview_tab'); ?>