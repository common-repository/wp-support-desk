
<div class="wpsd-navbar">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!--<a class="navbar-brand" href="#">Project name</a>-->
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">

                    <li><a href="<?php echo $wpsd_page_url; ?>" class="wpsd_load_home"><i class="glyphicon glyphicon-home"></i> </a> </li>
                    
                    
                  <!--  <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tickets <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php /*if ($departments_query->have_posts()) {
                                while($departments_query->have_posts()) { $departments_query->the_post();
                                    global $post;
                                    */?>
                                    <li><a href="<?php /*echo esc_url(add_query_arg(array('wpsd_page' => 'departments', 'wpsd_department'=>$post->post_name), $wpsd_page_url)); */?>"><?php /*echo get_the_title() */?></a></li>
                                    <?php
/*                                }
                            }
                            */?>
                        </ul>
                    </li>-->

                    
                    
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <!--<a href="<?php /*echo esc_url(add_query_arg(array('wpsd_page' => 'departments'), $wpsd_page_url)); */?>"> <i class="glyphicon glyphicon-search"></i> <?php /*_e('Browse Department', 'wp-support-desk') */?></a> -->
                        <a href="javascript:;" class="wpsd_create_new_ticket"> <i class="glyphicon glyphicon-new-window"></i> <?php _e('Create new ticket'); ?></a>
                    </li>

                    <?php if (is_user_logged_in()){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyphicon glyphicon-user"></i> <?php echo $wp_current_user->display_name; ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">My Account</a> </li>
                            <li><a href="<?php echo wp_logout_url(); ?>"><i class="glyphicon glyphicon-log-out"></i> Logout</a> </li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
</div>