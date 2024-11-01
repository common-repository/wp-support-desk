<?php
$departments_query = wpsd_get_departments_query();
global $post, $user_ID;
$wp_current_user = wp_get_current_user();
$wpsd_page_url = get_permalink();
$wpsd_redirect_back_url = wpsd_redirect_back_url();
//echo $page_url =  esc_url(add_query_arg(array('wpsd_page' => 'department'), $wpsd_page_url));
//echo '<a href="'.$page_url.'">View Page</a>';
$priorities = get_categories(array('taxonomy'=>'wpsd_priority', 'hide_empty' => 0));
$kb_categories = get_categories(array('taxonomy'=>'wpsd_kb_categories', 'hide_empty' => 0));
?>

<div class="wpsd-main">
    <div class="wpsd-container">
        <!-- Start header -->
        <header class="header wpsd-bd-bottom">
            <h1 class="wpsd-logo wpsd-left">Wp Support Desk</h1>
            <a href="#" class="wpsd-left wpsd_load_home"><span class="wpsd-home-button"><i class="fa fa-home"></i></span></a>

            <div class="wpsd-author-bar wpsd-right">
                <img src="<?php echo get_avatar_url($wp_current_user->ID) ?>" alt="author img" class="wpsd-author-img wpsd-left">
                <select id="wpsd-author-menu" class="wpsd-select-dropdown" name="wpsd-author-menu">
                    <option value="name" selected="selected"><?php echo $wp_current_user->display_name; ?></option>
                    <option value="logout"><?php _e('Logout', 'wp-support-desk'); ?></span></option>
                </select>
            </div><!-- //wpsd-author-bar -->
            <div class="wpsd-search wpsd-right">

                <form role="form" method="post" action="" id="wpsd_article_search_form">
                    <input name="wpsd_kb_search" id="wpsd_kb_search" placeholder="<?php _e('Have a question?', 'wp-support-desk'); ?>" type="text">
                    <span class="wpsd-search-button wpsd-right"><i class="fa fa-search"></i></span>
                </form>

            </div> <!-- //wpsd-search -->
        </header>
        <div class="wpsd-clearfix"></div>
        <!-- End header -->

        <!-- Start Main Wrapper -->
        <div class="wpsd-main-wrapper">
            <!-- Start navbar -->
            <div class="wpsd-main-navbar wpsd-grid-3">
                <p class="wpsd-center wpsd-ticket-btn"><a href="javascript:;" class="wpsd_create_new_ticket wpsd-btn wpsd-btn-xlg wpsd-btn-primary wpsd-btn-radius"> <span class="wpsd-visible-desktop"><?php _e('New Ticket', 'wp-support-desk'); ?></span> <i class="fa fa-plus-square-o"></i></a></p>

                <div class="wpsd-nav">
                    <h3 class="wpsd-nav-title"><i class="fa fa-ticket"></i> <span class="wpsd-visible-desktop"><?php _e('Tickets', 'wp-support-desk'); ?></span></h3>
                    <ul>
                        <li class="active"><a href="javascript:;" class="wpsd_load_home wpsd-visible-mobile"><i class="fa fa-history"></i></a>
                            <a href="javascript:;" class="wpsd_load_home wpsd-visible-desktop"><?php _e('All tickets', 'wp-support-desk'); ?> </span></a>
                        </li>

                        <li><a href="javascript:;" class="wpsd_load_waiting_for_reply wpsd-visible-mobile"><i class="fa fa-history"></i></a>
                            <a href="javascript:;" class="wpsd_load_waiting_for_reply wpsd-visible-desktop"><?php _e('Waiting for reply', 'wp-support-desk'); ?>  <span class="wpsd-amount"><?php echo wpsd_count_ticket_status('pending'); ?> </span></a></li>

                        <li><a href="javascript:;" class="wpsd_load_in_discussion wpsd-visible-mobile"><i class="fa fa-comments-o"></i></a>
                            <a href="javascript:;" class="wpsd_load_in_discussion wpsd-visible-desktop"><?php _e('In Discussion', 'wp-support-desk'); ?> <span class="wpsd-amount"><?php echo wpsd_count_ticket_status('open'); ?></span></a>
                        </li>

                        <li><a href="javascript:;" class="wpsd_load_in_completed_tickets wpsd-visible-mobile"><i class="fa fa-heart-o"></i></a><a href="javascript:;" class="wpsd_load_in_completed_tickets wpsd-visible-desktop">Completed <span class="wpsd-amount"><?php echo wpsd_count_ticket_status('closed'); ?></span></a></li>
                    </ul>
                </div> <!-- //tickets nav --->

                <div class="wpsd-nav">
                    <h3 class="wpsd-nav-title"><i class="fa fa-exclamation-circle"></i> <span class="wpsd-visible-desktop"></i><?php _e('Knowledgebase', 'wp-support-desk'); ?></span></h3>
                    <ul>
                        <?php
                        if (count($kb_categories) > 0){
                            foreach ($kb_categories as $kb_category){
                                echo '<li><a href="javascript:;" class="wpsd_load_kb_category wpsd-visible-mobile"><i class="fa fa-folder-open-o"></i></a><a href="javascript:;" class="wpsd_load_kb_category wpsd-visible-desktop" data-kb-category-id="'.$kb_category->term_id.'">'.$kb_category->name.' <span class="wpsd-amount">'.$kb_category->category_count.'</span></a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div> <!-- //Knowledgebase nav -->

                <div class="wpsd-nav wpsd-level wpsd-visible-desktop">
                    <h3 class="wpsd-nav-title"><i class="fa fa-star"></i><?php _e(' Priority Labels', 'wp-support-desk'); ?></h3>
                    <ul>
                        <?php
                        if (count($priorities) > 0){
                            foreach ($priorities as $priority){
                                echo "<li><span style='color: ".get_term_meta($priority->term_id, 'color', true).";'><i class='fa fa-circle'></i> </span>{$priority->name}</li>";
                            }
                        }
                        ?>
                    </ul>
                </div> <!-- //priority level -->

                <div class="wpsd-copyright">
                    <h3><strong class="wpsd-visible-desktop"><?php _e('Powered by', 'wp-support-desk'); ?>: </strong>
                        <a href="https://wpneo.com/" target="_blank"> <?php echo apply_filters('wpsd_powered_by_text', 'WP Support Desk' ); ?> </a>
                    </h3>
                </div> <!-- //copyright -->
            </div>
            <!-- End navbar -->

            <!-- Start main content -->
            <div class="wpsd-main-content wpsd-grid-7">

                <!--Loader is now started-->
                <div class="wpsd-loading" style="display: none;"><div class="load"><?php _e('Loading....', 'wp-support-desk'); ?> <i class="fa-li fa fa-spinner fa-spin"></i></div></div>

                <!-- Main Ajax wrap -->
                <div class="wpsd_content_wrap">
                    
                    <?php

                    /**
                     * View url based ticket single page
                     */
                    //wpsd_page=ticket_view&ticket_id=63

                    if (empty($_GET['wpsd_page'])){
                        include WPSD_PLUGIN_DIR.'includes/view/wpsd-ajax-home.php';
                    }else{
                        $wpsd_page = sanitize_text_field($_GET['wpsd_page']);
                        if ($wpsd_page === 'ticket_view' && ( ! empty($_GET['ticket_id'])) ){
                            include WPSD_PLUGIN_DIR.'includes/view/wpsd-ticket_view.php';
                        }
                    }
                    
                    ?>

                </div>

            </div> <!-- End main content -->
        </div>
        <!-- End main wrapper -->
    </div> <!--  wpsd-container -->
</div> <!--  wpsd-main -->
