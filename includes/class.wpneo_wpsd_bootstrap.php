<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( ! class_exists('WPNEO_WPSD_Bootstrap')){
    class WPNEO_WPSD_Bootstrap{
        static function wpsd_initial_setup(){
            global $wpdb;

            $get_wpneo_wpsd_version = get_option('wpsd_version');

            if ( ! $get_wpneo_wpsd_version) {
                update_option('wpsd_version', WP_SUPPORT_DESK_VERSION);
                //Insert WP Support Desk Page
                $my_post = array(
                    'post_title'    => 'WP Support Desk',
                    'post_content'  => '[wpsd_ticket_manager]',
                    'post_type'     => 'page',
                    'post_status'   => 'publish',
                    'post_author'   => get_current_user_id()
                );
                // Insert the post into the database.
                wp_insert_post( $my_post );


                //Set Ticket User Roles
                $wpsd_roles = array('manager' => 'Manager', 'support_user' => 'Support User');
                update_option('wpneo_wpsd_user_roles', $wpsd_roles);

                $pre_settings = unserialize('a:21:{s:13:"jquery_source";s:6:"google";s:25:"include_twitter_bootstrap";s:1:"1";s:22:"unregister_user_access";s:1:"1";s:18:"enable_html_editor";s:1:"0";s:15:"hide_powered_by";s:1:"1";s:26:"use_uncategorized_category";s:1:"1";s:35:"wpsd_creating_ticket_email_activate";s:1:"1";s:37:"wpsd_creating_ticket_email_deactivate";s:1:"0";s:34:"wpsd_email_creating_ticket_subject";s:58:"Your ticket has been submitted successfully (#{ticket_id})";s:31:"wpsd_email_creating_ticket_body";s:301:"Dear {ticket_owner},

Thank you for contacting Support. Your ticket has been created Successfully!

We hope you may want to see what you have submitted-

Subject: {ticket_subject}

Description: {ticket_description}

Priority: {ticket_priority}

Posted at: {time_created}

Kind Regards,
WP Support Desk";s:34:"wpsd_replied_ticket_email_activate";s:1:"1";s:39:"wpsd_email_replied_ticket_subject_owner";s:53:"You have got a reply from {replied_by} (#{ticket_id})";s:43:"wpsd_email_replied_ticket_subject_following";s:68:"{replied_by} replied a ticket which you are following (#{ticket_id})";s:30:"wpsd_email_replied_ticket_body";s:0:"";s:47:"wpsd_replied_ticket_email_activate_ticket_owner";s:1:"1";s:50:"wpsd_replied_ticket_email_activate_ticket_follower";s:1:"1";s:36:"wpsd_email_replied_ticket_owner_body";s:132:"Hi {ticket_owner}

{replied_by} replied to your ticket #{ticket_id}

Replied Content
{replied_description}


Regards
WP Support Desk";s:39:"wpsd_email_replied_ticket_follower_body";s:156:"Hi {previous_replied_by}

{replied_by} has been replied to a ticket that you are following


Replied Content
{replied_description}


Regards
WP Support Desk";s:35:"wpsd_assigned_ticket_email_activate";s:1:"1";s:34:"wpsd_email_assigned_ticket_subject";s:38:"{assigned_by} assigned a ticket to you";s:31:"wpsd_email_assigned_ticket_body";s:156:"Hi {assigned_to}

{assigned_by} has been assigned a ticket to you

Ticket Subject: {ticket_subject}

Assigned Time: {assigned_time}

Regards
WP Support Desk";}');

                update_option('wpsd_settings_option', $pre_settings);

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                //Query database
                    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpsd_ticket_users (
                  user_ID int(11) NOT NULL,
                  departments text NOT NULL,
                  categories text NOT NULL,
                  assign_report_users text NOT NULL,
                  role varchar(255) NOT NULL,
                  delete_tickets int(1) NOT NULL,
                  manage_bugtrackers int(1) NOT NULL,
                  support_levels int(2) NOT NULL,
                  PRIMARY KEY (user_ID)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                    dbDelta($sql);
                    //Create assigned table
                $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpsd_assigned_tickets_users (
                  AID int(11) NOT NULL AUTO_INCREMENT,
                  post_id int(11) NOT NULL,
                  author_id int(11) NOT NULL,
                  assigned_by int(11) NOT NULL,
                  assigned_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  UNIQUE KEY AID (AID)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                dbDelta($sql);
                //Create custom field
                $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpsd_custom_field (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  title varchar(500) NOT NULL,
                  field_name varchar(50) NOT NULL,
                  type enum('text','select','radio','checkbox','textarea','date') NOT NULL,
                  value text NOT NULL,
                  size int(3) NOT NULL,
                  max_length int(3) NOT NULL,
                  help_text text NOT NULL,
                  default_value text NOT NULL,
                  field_required int(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                dbDelta($sql);

                /**
                 * Set capabilities to current user
                 */
                $current_user_id = get_current_user_id();
                $get_current_user = get_user_by('ID', $current_user_id);
                if ( ! $get_current_user->has_cap('manage_wpsd_manager_options')){
                    $get_current_user->add_cap('manage_wpsd_support_user_options');
                    $get_current_user->add_cap('manage_wpsd_team_lead_options');
                    $get_current_user->add_cap('manage_wpsd_manager_options');

                    $get_current_user->add_cap( 'read' );
                    $get_current_user->add_cap( 'read_wpsd_knowledgebase');
                    $get_current_user->add_cap( 'read_private_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'edit_wpsd_knowledgebase' );
                    $get_current_user->add_cap( 'edit_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'edit_others_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'edit_published_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'publish_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'delete_others_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'delete_private_wpsd_knowledgebases' );
                    $get_current_user->add_cap( 'delete_published_wpsd_knowledgebases' );
                    $get_current_user->add_cap('manage_kb_categories');
                    $get_current_user->add_cap('manage_wpsd_priority');

                }

            }

            //Add a hook
            do_action('wpsd_after_plugin_actiavtion');



        }
    }
}