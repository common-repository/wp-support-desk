<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Save department
 */

if (! function_exists('wpsd_post')){
    function wpsd_post($post_item){
        if (array_key_exists($post_item, $_POST)) {
            return $_POST[$post_item];
        }
        return null;
    }
}

if (! function_exists('wpsd_get_departments_query')){
    function wpsd_get_departments_query( $paged=1 ){
        $args = array( 'post_type' => 'wpsd_department', 'paged'=> $paged ) ;
        $departments_query = new WP_Query($args);
        return $departments_query;
    }
}

/**
 * get_taxonomy_ids_by_department()
 */
if (! function_exists('get_taxonomy_ids_by_department')) {
    function get_taxonomy_ids_by_department($department_id)
    {
        global $wpdb;
        $get_query = $wpdb->get_col("select term_id from {$wpdb->termmeta} where meta_key = 'department_id' and meta_value={$department_id} ");
        return $get_query;
    }
}

if ( ! function_exists('get_wpsd_ticket_users_ids')){
    function get_wpsd_ticket_users_ids(){
        global $wpdb;
        $get_query = $wpdb->get_col("select user_ID from {$wpdb->prefix}wpsd_ticket_users");
        return $get_query;
    }
}

/**
 * Get assigned user ids as array to the specific tickets
 */
if ( ! function_exists('get_wpsd_assigned_user_ids')){
    function get_wpsd_assigned_user_ids($post_id = 0){
        global $wpdb;
        $get_query = $wpdb->get_col("select author_id from {$wpdb->prefix}wpsd_assigned_tickets_users where post_id = {$post_id}");
        return $get_query;
    }
}

if ( ! function_exists('wpsd_see_raw_data')){
    function wpsd_see_raw_data($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

if ( ! function_exists('get_wpsd_ticket_user_row')){
    function get_wpsd_ticket_user_row($user_ID){
        global $wpdb;
        $query = $wpdb->get_row("select * from {$wpdb->prefix}wpsd_ticket_users where user_ID = {$user_ID}");
        return $query;
    }
}

if ( ! function_exists('get_wpsd_ticket_status')){
    function get_wpsd_ticket_status($post_id = 0){
        if ( ! $post_id){
            return null;
        }
        $status = get_post_meta($post_id, 'wpsd_ticket_status', true);
        if ($status){
            $contex = '';
            switch ($status){
                case 'pending':
                    $status = str_replace(array('pending'), __('Waiting for reply'), $status );
                    //$contex = "<span class='text-muted'>{$status}</span>";
                    $contex = "waiting";
                    break;
                case 'open':
                    $contex = "<span class='text-info'>{$status}</span>";
                    break;
                case 'closed':
                    $contex = "<span class='text-success'>{$status}</span>";
                    break;
                case 're-open':
                    $contex = "<span class='text-info'>{$status}</span>";
                    break;
            }
            return $contex;
        }
        return null;
    }
}

/**
 * @param string $status
 * @return int
 */
if ( ! function_exists('wpsd_count_ticket_status')){
    function wpsd_count_ticket_status($status = 'pending'){
        $user_ID = get_current_user_id();
        $user_tickets_args = array(
            'author' => $user_ID,
            'post_type' => 'wpsd_tickets',
            'posts_per_page' => -1,
            'post_parent' => 0,
            'meta_query' => array(
                array(
                    'key'     => 'wpsd_ticket_status',
                    'value'   => $status,
                    'compare' => '=',
                ),
            ),
        );
        $user_tickets = new WP_Query($user_tickets_args);
        return $user_tickets->post_count;
    }
}

/**
 * @param string $status
 * @return int
 *
 * wpsd_count_ticket_status()
 */
if ( ! function_exists('wpsd_count_ticket_status_global')){

    function wpsd_count_ticket_status_global($status = 'pending'){
        $user_ID = get_current_user_id();
        $user_tickets_args = array(
            'post_type' => 'wpsd_tickets',
            'posts_per_page' => -1,
            'post_parent' => 0,
            'meta_query' => array(
                array(
                    'key'     => 'wpsd_ticket_status',
                    'value'   => $status,
                    'compare' => '=',
                ),
            ),
        );
        $user_tickets = new WP_Query($user_tickets_args);
        return $user_tickets->post_count;
    }
}


if ( ! function_exists('wpsd_admin_notice')){
    function wpsd_admin_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
        </div>
        <?php
    }
}

/**
 * @return string
 * 
 * Will return back url
 */
if (! function_exists('wpsd_redirect_back_url')){
    function wpsd_redirect_back_url(){
        $redirect = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $redirect;
    }
}

/**
 * Will be redirect back url immediately
 * 
 */
if (! function_exists('wpsd_redirect_back')){
    function wpsd_redirect_back($url = 'back'){
        $back_url = ($url == 'back') ? $_SERVER['HTTP_REFERER'] : $url;
        wp_redirect($back_url);
        die();
    }
}

/**
 * @param string $option
 * @param null $value
 * @return bool
 *
 * update WP Support Desk settings option
 */
if (! function_exists('wpsd_update_settings_option')){
    function wpsd_update_settings_option($option = '', $value){
        if ($option){
            $get_all_settings = get_option('wpsd_settings_option');

            if (is_array($get_all_settings)){
                $get_all_settings[$option] = $value;
                $update_again = update_option('wpsd_settings_option', $get_all_settings);
                if ($update_again)
                    return true;
            }else{
                $update = update_option('wpsd_settings_option', array($option => $value));
                if ($update)
                    return true;
            }
        }
        return false;
    }
}

/**
 * @param string $option
 * @return bool|mixed
 * 
 * Return wpsd option if exists
 */
function wpsd_get_settings_option($option = ''){
    if ( ! empty($option)){
        $get_all_settings = get_option('wpsd_settings_option');
        if ($get_all_settings && is_array($get_all_settings)){
            if (array_key_exists($option, $get_all_settings)){
                return $get_all_settings[$option];
            }
        }
    }
    return false;
}

/**
 * @param string $option
 * @return array|null|object
 * 
 * this function will return all of saved ticket custom form field
 */

if ( ! function_exists('wpsd_get_ticket_custom_field')) {
    function wpsd_get_ticket_custom_field() {
        global $wpdb;
        $sql_custom_field = $wpdb->get_results("select * from {$wpdb->prefix}wpsd_custom_field");
        return $sql_custom_field;
    }
}

if( ! function_exists('wpsd_pagination')) {
    function wpsd_pagination($page_numb, $max_page) {
        echo '<div class="wpsd-pagination">';
        echo paginate_links(array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'current' => $page_numb,
            'total' => $max_page,
            'type' => 'list',
        ));
        echo '</div>';
    }
}


if( ! function_exists('wpsd_options')) {
    function wpsd_options($option_name) {
        $wpsd_options = get_option('wpsd_settings_option');
        if ( array_key_exists($option_name, $wpsd_options))
            return $wpsd_options[$option_name];
        return null;
    }
}

if( ! function_exists('wpsd_parse_md')) {
    function wpsd_parse_md($content) {
        if ( ! class_exists('WPSDMDParsedown')){
            include WPSD_PLUGIN_DIR.'includes/class.wpsd_md_parsedown.php';
        }
        $Parsedown = new WPSDMDParsedown();
        return $Parsedown->text($content);
    }
}


if( ! function_exists('wpsd_get_icon')) {
    function wpsd_get_icon( $ext , $attachment_url , $attachment ){
        $html = '';

        $ext = trim( strtolower( $ext ) );
        $ext = str_replace(' ', '', $ext);

        switch ( $ext ) {

            // Image
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $html .= '<a href="' . $attachment_url . '"><img width="40" src="'.$attachment_url.'" /></a>';
                break;

            // Audio
            case 'mp3':
            case 'm4a':
            case 'ogg':
            case 'wav':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-volume-up"></i></a>';
                break;

            // Video
            case 'mp4':
            case 'm4v':
            case 'mov':
            case 'wmv':
            case 'avi':
            case 'mpg':
            case 'ogv':
            case '3gp':
            case '3g2':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-video-camera"></i></a>';
                break;

            // PDF
            case 'pdf':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-file-pdf-o"></i></a>';
                break;
            
            // Documents
            case 'doc':
            case 'docx':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-file-word-o"></i></a>';
                break;
            
            // Keynote
            case 'key':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-sticky-note-o"></i></a>';
                break;
            
            // Powerpoint
            case 'ppt':
            case 'pptx':
            case 'pps':
            case 'ppsx':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-file-powerpoint-o"></i></i></a>';
                break;
            
            // Open Document
            case 'odt':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-sticky-note-o"></i></a>';
                break;
            
            // Excel
            case 'xls':
            case 'xlsx':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-file-excel-o"></i></a>';
                break;

            // Zip
            case  'zip':
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-file-archive-o"></i></a>';
                break;

            default:
                $html .= '<a href="' . $attachment_url . '"><i class="fa fa-file-code-o"></i></a>';
                break;
        }
        return $html;
    }
}