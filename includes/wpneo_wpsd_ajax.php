<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPNEO_WPSD_Ajax')){
    class WPNEO_WPSD_Ajax{
        protected static $_instance = null;

        /**
         * @return null|WPNEO_WPSD_Shortcode
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * WPNEO_WPSD_Ajax constructor
         *
         * Add actions
         */
        public function __construct(){
            add_action( 'wp_ajax_wpsd_ticket_view_frontend', array( $this, 'wpsd_ticket_view_frontend' ) );
            //add_action( 'wp_ajax_nopriv_wpsd_ticket_view_frontend', array( $this, 'wpsd_ticket_view_frontend' ) );

            add_action( 'wp_ajax_save_reply_from_frontend_ticket_form', array( $this, 'save_reply_from_frontend_ticket_form' ) );
            add_action( 'wp_ajax_nopriv_save_reply_from_frontend_ticket_form', array( $this, 'save_reply_from_frontend_ticket_form' ) );

            add_action( 'wp_ajax_wpsd_create_new_ticket', array( $this, 'wpsd_create_new_ticket' ) );
            add_action( 'wp_ajax_nopriv_wpsd_create_new_ticket', array( $this, 'wpsd_create_new_ticket' ) );

            add_action( 'wp_ajax_wpsd_load_home_page', array( $this, 'wpsd_load_home_page' ) );
            add_action( 'wp_ajax_nopriv_wpsd_load_home_page', array( $this, 'wpsd_load_home_page' ) );

            add_action( 'wp_ajax_wpsd_load_knowledgebase', array( $this, 'wpsd_load_knowledgebase' ) );
            add_action( 'wp_ajax_nopriv_wpsd_load_knowledgebase', array( $this, 'wpsd_load_knowledgebase' ) );

            add_action( 'wp_ajax_wpsd_open_kb_category', array( $this, 'wpsd_open_kb_category' ) );
            add_action( 'wp_ajax_nopriv_wpsd_open_kb_category', array( $this, 'wpsd_open_kb_category' ) );

            add_action( 'wp_ajax_wpsd_search_kb_article', array( $this, 'wpsd_search_kb_article' ) );
            add_action( 'wp_ajax_nopriv_wpsd_search_kb_article', array( $this, 'wpsd_search_kb_article' ) );

            add_action( 'wp_ajax_wpsd_open_kb_article', array( $this, 'wpsd_open_kb_article' ) );
            add_action( 'wp_ajax_nopriv_wpsd_open_kb_article', array( $this, 'wpsd_open_kb_article' ) );
            
            add_action( 'wp_ajax_wpsd_change_ticket_status', array( $this, 'wpsd_change_ticket_status' ) );
            add_action( 'wp_ajax_nopriv_wpsd_change_ticket_status', array( $this, 'wpsd_change_ticket_status' ) );

            add_action( 'wp_ajax_nopriv_wpsd_track_ticket_guest', array( $this, 'wp_ajax_nopriv_wpsd_track_ticket_guest' ) );
            add_action( 'wp_ajax_nopriv_wpsd_login_action', array( $this, 'wpsd_login_action' ) );
            add_action( 'wp_ajax_nopriv_wpsd_load_login_form', array( $this, 'wpsd_load_login_form' ) );
            add_action( 'wp_ajax_nopriv_wpsd_load_user_registration_form', array( $this, 'wpsd_load_user_registration_form' ) );
            add_action( 'wp_ajax_nopriv_wpsd_user_register', array( $this, 'wpsd_user_register' ) );
            add_action( 'wp_ajax_wpsd_batch_delete_ticket', array( $this, 'wpsd_batch_delete_ticket' ) );
            add_action( 'wp_ajax_wpsd_logout', array( $this, 'wpsd_logout' ) );


            add_action( 'wp_ajax_wpsd_load_waiting_for_reply', array( $this, 'wpsd_load_waiting_for_reply' ) );
            add_action( 'wp_ajax_wpsd_load_in_discussion', array( $this, 'wpsd_load_in_discussion' ) );
            add_action( 'wp_ajax_wpsd_load_in_completed_tickets', array( $this, 'wpsd_load_in_completed_tickets' ) );
            add_action( 'wp_ajax_wpsd_article_vote', array( $this, 'wpsd_article_vote' ) );
            add_action( 'wp_ajax_wpsd_suggestions_kb', array( $this, 'wpsd_suggestions_kb' ) );


            //Admin ajax hook
            add_action( 'wp_ajax_wpsd_ticket_view_admin', array( $this, 'wpsd_ticket_view_admin' ) );
            add_action( 'wp_ajax_nopriv_wpsd_ticket_view_admin', array( $this, 'wpsd_ticket_view_admin' ) );

            add_action( 'wp_ajax_wpsd_change_ticket_status_admin', array( $this, 'wpsd_change_ticket_status_admin' ) );
            add_action( 'wp_ajax_save_reply_from_admin_ticket_form', array( $this, 'save_reply_from_admin_ticket_form' ) );

            add_action( 'wp_ajax_wpsd_load_tickets_home_admin', array( $this, 'wpsd_load_tickets_home_admin' ) );
            add_action( 'wp_ajax_wpsd_ticket_search_admin', array( $this, 'wpsd_load_tickets_home_admin' ) );
            add_action( 'wp_ajax_wpsd_create_new_ticket_admin', array( $this, 'wpsd_create_new_ticket_admin' ) );

            //Hook, Action
            //Creating ticket
            add_action('wpsd_after_ticket_create', array($this, 'wpsd_after_ticket_create'));
            //Replied Ticket
            add_action('wpsd_after_ticket_replied', array($this, 'wpsd_after_ticket_replied'));
            //Assigned Ticket
            add_action('wpsd_during_single_user_assign', array($this, 'wpsd_during_single_user_assign'), 10, 3);


            
        }

        /**
         * Include view file based on query string
         */
        public function wpsd_ticket_view_frontend(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-ticket_view.php';
            die();
        }

        public function save_reply_from_frontend_ticket_form(){
            save_reply_from_frontend_ticket_form();
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-ticket_view.php';
            die();
        }

        public function wpsd_create_new_ticket(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-create_new_ticket.php';
            die();
        }

        public function wp_ajax_nopriv_wpsd_track_ticket_guest(){
            //print_r($_POST);
            $ticket_id  = sanitize_text_field(wpsd_post('ticket_id'));
            $email      = sanitize_text_field(wpsd_post('email'));

            $args = array(
                'post_type'  => 'wpsd_tickets',
                'meta_query' => array(
                    array(
                        'key'       => 'wpsd_ticket_id',
                        'value'     => $ticket_id,
                        'compare'   => '=',
                    ),
                    array(
                        'key'       => 'wpsd_ticket_email',
                        'value'     => $email,
                        'compare'   => '=',
                    ),
                ),
            );
            $query = new WP_Query( $args );

            if ($query->post_count > 0) {
                die(json_encode(array('success' => 1, 'msg' => __('Ticket has been found', 'wp-support-desk'), 'ticket_id' =>$query->post->ID )));
            }
            die(json_encode(array('success' => 0, 'msg' => __('Sorry, not found any ticket', 'wp-support-desk') )));
        }

        public function wpsd_login_action(){
            
            do_action('wpsd_before_login_action');
            
            //print_r($_POST);
            $log            = sanitize_text_field(wpsd_post('log'));
            $pwd            = sanitize_text_field(wpsd_post('pwd'));
            $rememberme     = sanitize_text_field(wpsd_post('rememberme'));

            $is_rememberd = ( ! empty($rememberme) ) ? true : false;
            $creds = array(
                'user_login'    => $log,
                'user_password' => $pwd,
                'remember'      => $is_rememberd
            );
            $user = wp_signon( $creds, false );

            $errors = '';
            if ( is_wp_error( $user ) ) {
                $errors = $user->get_error_message();
                die(json_encode(array('success' => 0, 'msg' => $errors)));
            }else{
                die(json_encode(array('success' => 1, 'msg' => __('Logged In Successfully, you are now redirecting', 'wp-support-desk')  )));
            }
        }

        /**
         * Load login form via ajax
         */

        public function wpsd_load_login_form(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-user-login-form.php';
            die();
        }
        
        public function wpsd_load_user_registration_form(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-user-registration-form.php';
            die();
        }

        /**
         * Register new users
         */
        
        public function wpsd_user_register(){
            do_action('wpsd_before_registration_action');

            $user_name = sanitize_text_field(wpsd_post('user_name'));
            $email = sanitize_text_field(wpsd_post('email'));

            $errors = register_new_user($user_name, $email);
            if ( !is_wp_error($errors) ) {
                die(json_encode(array('success' => 1, 'msg' => __('<p class="wpsd-alert-warning">Registration complete. Please check your email.</p>', 'wp-support-desk')  )));
            }
            die(json_encode(array('success' => 0, 'msg' => '<p class="wpsd-alert-warning">'.$errors->get_error_message().'</p>' )));
        }

        public function wpsd_batch_delete_ticket(){
            $checkedValues= $_POST['checkedValues'];

            if (is_array($checkedValues)){
                foreach ($checkedValues as $post_id){
                    $pid = (int) sanitize_text_field($post_id);

                    //Child post/reply delete
                    $args = array(
                        'post_parent' => $pid,
                        'post_type' => 'wpsd_tickets'
                    );
                    $posts = get_posts( $args );
                    if (is_array($posts) && count($posts) > 0) {
                        foreach($posts as $post){
                            wp_delete_post($post->ID, true);
                        }
                    }

                    wp_delete_post( $pid, true );
                }
            }
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-ajax-home.php';
            die();
        }

        public function wpsd_load_home_page(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-ajax-home.php';
            die();
        }

        public function wpsd_load_waiting_for_reply(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-tickets-waiting-for-reply.php';
            die();
        }
        public function wpsd_load_in_discussion(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-tickets-in-discussion.php';
            die();
        }
        public function wpsd_load_in_completed_tickets(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-tickets-completed.php';
            die();
        }
        public function wpsd_article_vote(){
            $user_id = get_current_user_id();
            $article_id = (int) sanitize_text_field(wpsd_post('article_id'));
            $vote_value = sanitize_text_field(wpsd_post('vote_value'));

            $get_all_voted_user_ids = get_post_meta($article_id, 'wpsd_kb_article_voted');
            if ( ! in_array($user_id, $get_all_voted_user_ids)){
                update_post_meta($article_id, 'wpsd_kb_article_voted', $user_id);
                update_post_meta($article_id, 'wpsd_kb_article_voted_user_'.$user_id, $vote_value);

                $vote_count = get_post_meta($article_id, 'wpsd_kb_article_total_vote');
                if ( ! $vote_count){
                    $vote_count = 1;
                }else{
                    $vote_count = $vote_count+1;
                }
                update_post_meta($article_id, 'wpsd_kb_article_total_vote', $vote_count);
                die(json_encode(array('success' => 1, 'msg' => __('You have successfully voted', 'wp-support-desk') )));
            }
            die(json_encode(array('success' => 0, 'msg' => __('You already voted on this article', 'wp-support-desk') )));

        }

        public function wpsd_suggestions_kb(){
            $wpsd_suggestions_kb = sanitize_text_field(wpsd_post('subject_term'));
            $args = array(
                's'                     => $wpsd_suggestions_kb,
                'post_type'             => 'wpsd_knowledgebase',
                'posts_per_page'        => 10
            );
            $search_data = new WP_Query($args);
            
            $output = '';
            if($search_data->have_posts()):
                $output .= '<ul>';
                while ($search_data->have_posts()): $search_data->the_post();
                    global $post;
                    $output .= '<li><a href="'.get_permalink().'" class="wpsd-open-kb wpsd-kb-'.$post->ID.'" data-kb-id="'.$post->ID.'">'.get_the_title().'</a></li>';
                endwhile;
                $output .= '</ul>';
                wp_reset_postdata();
            endif;

            wp_die($output);
        }
    
        public function wpsd_load_knowledgebase(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-knowledgebase.php';
            die();
        }

        public function wpsd_open_kb_category(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-open-kb-category.php';
            die();
        }

        public function wpsd_open_kb_article(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-open-kb-article.php';
            die();
        }

        public function wpsd_search_kb_article(){
            include WPSD_PLUGIN_DIR.'includes/view/wpsd-search-kb-article.php';
            die();
        }

        public function wpsd_change_ticket_status(){
            wpsd_ticket_status_change();
            $this->wpsd_ticket_view_frontend();
        }

        public function wpsd_logout(){
            wp_logout();
        }
    
        //Admin view method
        public function wpsd_ticket_view_admin(){
            include_once WPSD_PLUGIN_DIR.'admin/view/tickets/wpsd_ticket_view.php';
            die();
        }
        public function wpsd_load_tickets_home_admin(){
            include_once WPSD_PLUGIN_DIR.'admin/view/tickets/wpsd_tickets.php';
            die();
        }
        public function wpsd_change_ticket_status_admin(){
            wpsd_ticket_status_change();
            $this->wpsd_ticket_view_admin();
        }
        public function save_reply_from_admin_ticket_form(){
            save_reply_from_frontend_ticket_form();
            $this->wpsd_ticket_view_admin();
            die();
        }
        public function wpsd_create_new_ticket_admin(){
            include_once WPSD_PLUGIN_DIR.'admin/view/tickets/wpsd_create_new_ticket_admin.php';
            die();
        }


        //Action Hook method
        //send email just after ticket created
        public function wpsd_after_ticket_create($ticket_id){
            //Determine whether admin give permission?

            $is_send_email = (bool) wpsd_options('wpsd_creating_ticket_email_activate');
            if ($is_send_email) {
                //Get ticket
                $ticket = get_post($ticket_id);

                $ticket_attached_id = get_post_meta($ticket_id, 'wpsd_ticket_id', true);
                $ticket_subject = $ticket->post_title;
                $ticket_description = nl2br($ticket->post_content);

                $time_created = date(get_option('date_format'), strtotime($ticket->post_date))." ".date(get_option('time_format'), strtotime($ticket->post_date));

                //Get priority
                $ticket_priority = '';
                $get_priority = get_the_terms($ticket_id, 'wpsd_priority');
                if ( ! empty($get_priority[0]) ){
                    $ticket_priority = $get_priority[0]->name;
                }

                if ($ticket->post_author >0) {
                    $ticket_owner = get_the_author_meta('display_name', $ticket->post_author);
                    $ticket_owner_email = get_the_author_meta('user_email', $ticket->post_author);
                }else{
                    $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
                    $ticket_owner = $ticket_owner_details['wpsd_name']. " (Guest)";
                    $ticket_owner_email = $ticket_owner_details['wpsd_email'];
                }

                //get template variable
                $get_changable_variable = array(
                    '{ticket_owner}',
                    '{ticket_owner_email}',
                    '{ticket_id}',
                    '{ticket_subject}',
                    '{ticket_description}',
                    '{ticket_priority}',
                    '{time_created}',
                );
                //replace template by below variable
                $replaced_variable = array(
                    $ticket_owner,
                    $ticket_owner_email,
                    $ticket_attached_id,
                    $ticket_subject,
                    $ticket_description,
                    $ticket_priority,
                    $time_created
                );

                //get email body
                ob_start();
                include WPSD_PLUGIN_DIR . 'includes/view/emails/wpsd_ticket_created.php';
                $email_body = ob_get_clean();
                $email_subject = wpsd_options('wpsd_email_creating_ticket_subject');

                //Prepare variable for email
                $email_subject  = str_replace($get_changable_variable, $replaced_variable, $email_subject);
                $email_body     = str_replace($get_changable_variable, $replaced_variable, $email_body);

                $headers = array('Content-Type: text/html; charset=UTF-8');
                //Send it now
                wp_mail( $ticket_owner_email, $email_subject, $email_body, $headers );
                // Reset content-type to avoid conflicts
            }
        }

        public function wpsd_after_ticket_replied($replied_id){

            $is_send_email_to_owner = (bool) wpsd_options('wpsd_replied_ticket_email_activate_ticket_owner');
            $is_send_email_to_follower = (bool) wpsd_options('wpsd_replied_ticket_email_activate_ticket_follower');


            $replied_ticket = get_post($replied_id);
            //Get ticket
            $ticket = get_post($replied_ticket->post_parent);
            $ticket_id = $ticket->ID;

            /**
             * Make sure, email receiver and replied user is not same person
             */

            if ($replied_ticket->post_author != $ticket->post_author) {
                if ($is_send_email_to_owner) {

                    if ($ticket->post_author > 0) {
                        $ticket_owner = get_the_author_meta('display_name', $ticket->post_author);
                        $ticket_owner_email = get_the_author_meta('user_email', $ticket->post_author);
                    } else {
                        $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
                        $ticket_owner = $ticket_owner_details['wpsd_name'] . " (Guest)";
                        $ticket_owner_email = $ticket_owner_details['wpsd_email'];
                    }

                    $ticket_attached_id = get_post_meta($ticket_id, 'wpsd_ticket_id', true);
                    $ticket_subject = $ticket->post_title;
                    $ticket_description = nl2br($ticket->post_content);

                    $time_created = date(get_option('date_format'), strtotime($ticket->post_date)) . " " . date(get_option('time_format'), strtotime($ticket->post_date));

                    //Get priority
                    $ticket_priority = '';
                    $get_priority = get_the_terms($ticket_id, 'wpsd_priority');
                    if (!empty($get_priority[0])) {
                        $ticket_priority = $get_priority[0]->name;
                    }


                    //Replied variable

                    //check is it guest or loggedIn user?
                    if ($replied_ticket->post_author > 0) {
                        $replied_by = get_the_author_meta('display_name', $replied_ticket->post_author);
                    } else {
                        $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
                        $replied_by = $ticket_owner_details['wpsd_name'] . " (Guest)";
                    }

                    $previous_replied_by = '';
                    $replied_description = nl2br($replied_ticket->post_content);
                    $time_replied = date(get_option('date_format'), strtotime($replied_ticket->post_date)) . " " . date(get_option('time_format'), strtotime($replied_ticket->post_date));

                    //End replied variable

                    //get template variable
                    $get_changable_variable = array(
                        '{ticket_owner}',
                        '{ticket_owner_email}',
                        '{ticket_id}',
                        '{ticket_subject}',
                        '{ticket_description}',
                        '{ticket_priority}',
                        '{time_created}',

                        //Replied Ticket
                        '{replied_by}',
                        '{previous_replied_by}',
                        '{replied_description}',
                        '{time_replied}',
                    );
                    //replace template by below variable
                    $replaced_variable = array(
                        $ticket_owner,
                        $ticket_owner_email,
                        $ticket_attached_id,
                        $ticket_subject,
                        $ticket_description,
                        $ticket_priority,
                        $time_created,

                        //Replied Ticket
                        $replied_by,
                        $previous_replied_by,
                        $replied_description,
                        $time_replied,
                    );

                    //get email body
                    ob_start();
                    include WPSD_PLUGIN_DIR . 'includes/view/emails/wpsd_ticket_replied.php';
                    $email_body = ob_get_clean();
                    $email_subject = wpsd_options('wpsd_email_replied_ticket_subject_owner');

                    //Prepare variable for email
                    $email_subject = str_replace($get_changable_variable, $replaced_variable, $email_subject);
                    $email_body = str_replace($get_changable_variable, $replaced_variable, $email_body);

                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    //Send it now
                    wp_mail($ticket_owner_email, $email_subject, $email_body, $headers);
                }
            }else{
                /**
                 * If same, then send email to others user who already replied in this tickets
                 * Send to follower
                 */

                if ($is_send_email_to_follower){
                    global $wpdb;
                    $get_all_child_posts_author_id = $wpdb->get_col("select post_author from {$wpdb->posts} where post_parent = {$ticket_id}");

                    if ($get_all_child_posts_author_id) {
                        $all_follower_with_post_author = array_unique($get_all_child_posts_author_id);
                        foreach ($all_follower_with_post_author as $follower) {
                            if ($follower != $ticket->post_author){

                                if ($ticket->post_author > 0) {
                                    $ticket_owner = get_the_author_meta('display_name', $ticket->post_author);
                                    $ticket_owner_email = get_the_author_meta('user_email', $ticket->post_author);
                                } else {
                                    $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
                                    $ticket_owner = $ticket_owner_details['wpsd_name'] . " (Guest)";
                                    $ticket_owner_email = $ticket_owner_details['wpsd_email'];
                                }

                                $followered_email = get_the_author_meta('user_email', $follower);

                                $ticket_attached_id = get_post_meta($ticket_id, 'wpsd_ticket_id', true);
                                $ticket_subject = $ticket->post_title;
                                $ticket_description = nl2br($ticket->post_content);

                                $time_created = date(get_option('date_format'), strtotime($ticket->post_date)) . " " . date(get_option('time_format'), strtotime($ticket->post_date));

                                //Get priority
                                $ticket_priority = '';
                                $get_priority = get_the_terms($ticket_id, 'wpsd_priority');
                                if (!empty($get_priority[0])) {
                                    $ticket_priority = $get_priority[0]->name;
                                }


                                //Replied variable

                                //check is it guest or loggedIn user?
                                if ($replied_ticket->post_author > 0) {
                                    $replied_by = get_the_author_meta('display_name', $replied_ticket->post_author);
                                } else {
                                    $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
                                    $replied_by = $ticket_owner_details['wpsd_name'] . " (Guest)";
                                }

                                $previous_replied_by = get_the_author_meta('display_name', $follower);;
                                $replied_description = nl2br($replied_ticket->post_content);
                                $time_replied = date(get_option('date_format'), strtotime($replied_ticket->post_date)) . " " . date(get_option('time_format'), strtotime($replied_ticket->post_date));

                                //End replied variable

                                //get template variable
                                $get_changable_variable = array(
                                    '{ticket_owner}',
                                    '{ticket_owner_email}',
                                    '{ticket_id}',
                                    '{ticket_subject}',
                                    '{ticket_description}',
                                    '{ticket_priority}',
                                    '{time_created}',

                                    //Replied Ticket
                                    '{replied_by}',
                                    '{previous_replied_by}',
                                    '{replied_description}',
                                    '{time_replied}',
                                );
                                //replace template by below variable
                                $replaced_variable = array(
                                    $ticket_owner,
                                    $ticket_owner_email,
                                    $ticket_attached_id,
                                    $ticket_subject,
                                    $ticket_description,
                                    $ticket_priority,
                                    $time_created,

                                    //Replied Ticket
                                    $replied_by,
                                    $previous_replied_by,
                                    $replied_description,
                                    $time_replied,
                                );

                                //get email body
                                ob_start();
                                include WPSD_PLUGIN_DIR . 'includes/view/emails/wpsd_email_to_ticket_follower.php';
                                $email_body = ob_get_clean();
                                $email_subject = wpsd_options('wpsd_email_replied_ticket_subject_following');

                                //Prepare variable for email
                                $email_subject = str_replace($get_changable_variable, $replaced_variable, $email_subject);
                                $email_body = str_replace($get_changable_variable, $replaced_variable, $email_body);

                                $headers = array('Content-Type: text/html; charset=UTF-8');
                                //Send it now
                                wp_mail($followered_email, $email_subject, $email_body, $headers);
                            }
                        }
                    }
                }
            }
        }

        /**
         * Assigned ticket individual
         */
        public function wpsd_during_single_user_assign($ticket_id, $assigned_to, $assigned_by){

            $is_send_email = (bool) wpsd_options('wpsd_assigned_ticket_email_activate');

            if ($is_send_email) {
                //Get ticket
                $ticket = get_post($ticket_id);

                $ticket_attached_id = get_post_meta($ticket_id, 'wpsd_ticket_id', true);
                $ticket_subject = $ticket->post_title;
                $ticket_description = nl2br($ticket->post_content);

                $time_created = date(get_option('date_format'), strtotime($ticket->post_date))." ".date(get_option('time_format'), strtotime($ticket->post_date));

                //Get priority
                $ticket_priority = '';
                $get_priority = get_the_terms($ticket_id, 'wpsd_priority');
                if ( ! empty($get_priority[0]) ){
                    $ticket_priority = $get_priority[0]->name;
                }

                if ($ticket->post_author >0) {
                    $ticket_owner = get_the_author_meta('display_name', $ticket->post_author);
                    $ticket_owner_email = get_the_author_meta('user_email', $ticket->post_author);
                }else{
                    $ticket_owner_details = get_post_meta($ticket->ID, 'wpsd_guest_info', true);
                    $ticket_owner = $ticket_owner_details['wpsd_name']. " (Guest)";
                    $ticket_owner_email = $ticket_owner_details['wpsd_email'];
                }

                //Assigned email
                $assigned_to_name = get_the_author_meta('display_name', $assigned_to);
                $assigned_to_email = get_the_author_meta('user_email', $assigned_to);

                $assigned_by_name = get_the_author_meta('display_name', $assigned_by);
                $assigned_time = date(get_option('date_format'))." ".date(get_option('time_format'));

                //get template variable
                $get_changable_variable = array(
                    '{ticket_owner}',
                    '{ticket_owner_email}',
                    '{ticket_id}',
                    '{ticket_subject}',
                    '{ticket_description}',
                    '{ticket_priority}',
                    '{time_created}',

                    '{assigned_to}',
                    '{assigned_by}',
                    '{assigned_time}',
                );
                //replace template by below variable
                $replaced_variable = array(
                    $ticket_owner,
                    $ticket_owner_email,
                    $ticket_attached_id,
                    $ticket_subject,
                    $ticket_description,
                    $ticket_priority,
                    $time_created,

                    $assigned_to_name,
                    $assigned_by_name,
                    $assigned_time,
                );

                //get email body
                ob_start();
                include WPSD_PLUGIN_DIR . 'includes/view/emails/wpsd_ticket_assigned.php';
                $email_body = ob_get_clean();
                $email_subject = wpsd_options('wpsd_email_assigned_ticket_subject');

                //Prepare variable for email
                $email_subject  = str_replace($get_changable_variable, $replaced_variable, $email_subject);
                $email_body     = str_replace($get_changable_variable, $replaced_variable, $email_body);

                $headers = array('Content-Type: text/html; charset=UTF-8');
                //Send it now
                wp_mail( $assigned_to_email, $email_subject, $email_body, $headers );
                // Reset content-type to avoid conflicts
            }
        }

    }
}

WPNEO_WPSD_Ajax::instance();