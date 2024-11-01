<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Create department from admin panel
 */
function wpneo_wpsd_insert_department(){
    if (!empty($_POST['wpneo_wpsd_department_save_btn'])) {
        if (wp_verify_nonce(sanitize_text_field(wpsd_post('wpsd_settings_page_nonce_field')), 'wpsd_settings_page_action')) {
            $department_name        = sanitize_text_field(wpsd_post('department_name'));
            $department_description = sanitize_text_field(wpsd_post('department_description'));
            $department_edit        = sanitize_text_field(wpsd_post('department_edit'));
            $department_post_data = array( // Create department post object
                'post_title'    => $department_name,
                'post_content'  => $department_description,
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_type'     => 'wpsd_department'
            );
            // Insert the post into the database
            if( isset( $_POST['department_edit'] ) ){
                $department_post_data['ID'] = $department_edit;
                $insert_department = wp_update_post( $department_post_data );
            }else{
                $insert_department = wp_insert_post( $department_post_data );
            }
            wp_redirect(admin_url('admin.php?page=wp-support-desk-settings&tab=departments'));
        }
    }
}
add_action('admin_init', 'wpneo_wpsd_insert_department');

/**
 * Save New Support Staff
 */
if ( ! empty($_POST['wpneo_wpsd_support_staff_save_btn'])){
    if (wp_verify_nonce( sanitize_text_field(wpsd_post('wpsd_settings_page_nonce_field')), 'wpsd_settings_page_action' ) ){
        global $wpdb;
        $user_id            = sanitize_text_field(wpsd_post('user_id'));
        if (empty($user_id)){
            wpsd_flash( __('No user has been selected', 'wp-support-desk'), 'warning');
            wpsd_redirect_back();
        }
        $department         = wpsd_post('department');
        $department         = serialize($department);
        $support_user_type  = sanitize_text_field(wpsd_post('support_user_type'));
        $delete_tickets     = sanitize_text_field(wpsd_post('delete_tickets'));

        //Get data ready for insert into database
        $data = array(
            'user_ID'           => $user_id,
            'departments'       => $department,
            'role'              => $support_user_type,
            'delete_tickets'    => $delete_tickets,
        );
        $insaert_row = $wpdb->insert("{$wpdb->prefix}wpsd_ticket_users", $data);
        if ($insaert_row){

            $get_saved_user = get_user_by('ID', $user_id); //Give permission to this user to access wpsd menu
            switch ($support_user_type){
                case 'support_user':
                    if ( ! $get_saved_user->has_cap('manage_wpsd_support_user_options')) {
                        $get_saved_user->add_cap('manage_wpsd_support_user_options');
                    }
                    break;
                case 'manager':
                    if ( ! $get_saved_user->has_cap('manage_wpsd_manager_options')){
                        $get_saved_user->add_cap('manage_wpsd_support_user_options');
                        $get_saved_user->add_cap('manage_wpsd_team_lead_options');
                        $get_saved_user->add_cap('manage_wpsd_manager_options');

                        $get_saved_user->add_cap( 'read' );
                        $get_saved_user->add_cap( 'read_wpsd_knowledgebase');
                        $get_saved_user->add_cap( 'read_private_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'edit_wpsd_knowledgebase' );
                        $get_saved_user->add_cap( 'edit_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'edit_others_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'edit_published_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'publish_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'delete_others_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'delete_private_wpsd_knowledgebases' );
                        $get_saved_user->add_cap( 'delete_published_wpsd_knowledgebases' );
                        $get_saved_user->add_cap('manage_kb_categories');
                        $get_saved_user->add_cap('manage_wpsd_priority');
                    }
                    break;
            }

        }

        wp_redirect(admin_url('admin.php?page=wp-support-desk-settings&tab=support_staff'));
    }
}


/**
 * Update Staff
 */
if ( ! empty($_POST['wpneo_wpsd_support_staff_update_btn'])){
    if (wp_verify_nonce( sanitize_text_field(wpsd_post('wpsd_settings_page_nonce_field')), 'wpsd_settings_page_action' ) ){
        global $wpdb;
        $user_id = sanitize_text_field(wpsd_post('user_id'));
        if (empty($user_id)){
            wpsd_flash( __('No user has been to be update', 'wp-support-desk'), 'warning');
        }
        $department         = wpsd_post('department');
        $department         = serialize($department);
        $support_user_type  = sanitize_text_field(wpsd_post('support_user_type'));
        $delete_tickets     = sanitize_text_field(wpsd_post('delete_tickets'));
        //Get data ready for insert into database
        $data = array(
            'user_ID'           => $user_id,
            'departments'       => $department,
            'role'              => $support_user_type,
            'delete_tickets'    => $delete_tickets,
        );
        $insaert_row = $wpdb->update("{$wpdb->prefix}wpsd_ticket_users", $data, array( 'user_ID' => $user_id ));

        wpsd_flash( __('User has been updated', 'wp-support-desk'), 'success');
    }
}


/**
 * Add ticket from frontend action
 */

add_action('wp_ajax_wpsd_create_ticket', 'wpneo_wpsd_create_ticket_action');
function wpneo_wpsd_create_ticket_action(){
    do_action('wpsd_before_ticket_create_action');
    $priority_id                = (int) sanitize_text_field(wpsd_post('priority_id')); //Get taxonomy value
    $wpsd_subject               = sanitize_text_field(wpsd_post('wpsd_subject'));
    $wpsd_description           = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", wpsd_post('wpsd_description'))));
    $wpsd_success_redirect_url  = sanitize_text_field(wpsd_post('wpsd_success_redirect_url'));
    $user_id                    = get_current_user_id();
    $creating_from              = sanitize_text_field(wpsd_post('creating_from')); //Determine is ticket creating from backend or frontend
    if ($creating_from == 'wpsd_admin'){
        $on_behalf_of = sanitize_text_field(wpsd_post('user_id'));
        if ($on_behalf_of){
            $user_id = $on_behalf_of;
        }
    }
    // Create department post object
    $my_post = array(
        'post_title'    => $wpsd_subject,
        'post_content'  => $wpsd_description,
        'post_status'   => 'publish',
        'post_author'   => $user_id,
        'post_type'     => 'wpsd_tickets'
    );
    // Insert the post into the database
    $ticket_created         = wp_insert_post( $my_post );
    if ($ticket_created) {
        $post_id            = $ticket_created;
        $ticket_random_id   = rand(1000, 9999).$post_id; //Store with a ticket id
        update_post_meta($post_id, 'wpsd_ticket_id', $ticket_random_id);
        $get_custom_field   = wpsd_get_ticket_custom_field(); //Get custom form field value
        $custom_field_form_value = array();
        if ($get_custom_field) {
            foreach ($get_custom_field as $cfield) {
                if (array_key_exists($cfield->field_name, $_POST)) {
                    if (is_array($_POST[$cfield->field_name])){
                        $custom_field_form_value[$cfield->title] = implode(',', $_POST[$cfield->field_name]);
                    }else {
                        $custom_field_form_value[$cfield->title] = sanitize_text_field($_POST[$cfield->field_name]);
                    }
                }
            }
        }
        if (count($custom_field_form_value) > 0){
            update_post_meta($post_id, 'wpsd_ticket_custom_form_field', $custom_field_form_value);
        }
        update_post_meta($post_id, 'wpsd_ticket_status', 'pending'); //Set ticket status to pending
        //Set Department
        $department_id = sanitize_text_field(wpsd_post('department_id'));
        if ($department_id){
            update_post_meta($post_id, 'wpsd_department_id', $department_id);
        }

        //Set name and email for guest user
        if( ! is_user_logged_in()) {
            $wpsd_name      = sanitize_text_field(wpsd_post('wpsd_name'));
            $wpsd_email     = sanitize_text_field(wpsd_post('wpsd_email'));
            update_post_meta($post_id, 'wpsd_guest_info', array('wpsd_name' => $wpsd_name, 'wpsd_email' => $wpsd_email));
            update_post_meta($post_id, 'wpsd_ticket_email', $wpsd_email);
        }
        // Upload attachment
        if( ! empty($_FILES['wpsd_upload_reply_attachment'])){
            $files = $_FILES['wpsd_upload_reply_attachment'];
            wpsd_upload_attachment_to_ticket($files, $post_id);
        }
        wp_set_object_terms($post_id, $priority_id, 'wpsd_priority'); //Set taxonomy with this ticket
        do_action('wpsd_after_ticket_create', $post_id); //Add hook
        die(json_encode(array('success' => 1, 'msg' => __('Ticket has been created', 'wp-support-desk'),'wpsd_success_redirect_url' => $wpsd_success_redirect_url )));
    }else{
        die(json_encode(array('success' => 0, 'msg' => __('Something went wrong at this moment, please try again later', 'wp-support-desk'))));
    }
}

/**
 * @return bool
 *
 * Remove title and editor support for ticket edit page in admin backend
 */
function wpneo_wpsd_remove_tickets_editor() {
    global $pagenow;
    if (!is_admin()) return false;
    if ( $pagenow == 'post.php' ){
        remove_post_type_support('wpsd_tickets', 'editor');
        remove_post_type_support('wpsd_tickets', 'title');
    }
}
add_action('admin_init', 'wpneo_wpsd_remove_tickets_editor');

function wpneo_wpsd_remove_custom_taxonomy() {
    global $pagenow;
    if (!is_admin()) return false;
    if ( $pagenow == 'post.php' ){
        remove_meta_box( 'tagsdiv-wpsd_category', 'wpsd_tickets', 'side' );
        remove_meta_box( 'tagsdiv-wpsd_priority', 'wpsd_tickets', 'side' );
    }
}
add_action( 'admin_menu', 'wpneo_wpsd_remove_custom_taxonomy' );

/**
 * Add Meta box for ticket details
 */

add_action( 'load-post.php', 'wpneo_wpsd_tickets_post_meta_boxes_setup' );
function wpneo_wpsd_tickets_post_meta_boxes_setup(){
    add_action( 'add_meta_boxes', 'wpneo_wpsd_tickets_post_add_meta_boxes' );
}

function wpneo_wpsd_tickets_post_add_meta_boxes(){
    add_meta_box('wpsd_ticket_details', 'Wp SD Ticket Details', 'wpsd_ticket_view_meta_box', 'wpsd_tickets');
}
function wpsd_ticket_view_meta_box(){
    include WPSD_PLUGIN_DIR.'admin/view/ticket/ticket-details.php';
}


/**
 * Register meta box for assign ticket to another user.
 */
function wpdocs_register_meta_boxes() {
    add_meta_box( 'wpsd-assign-ticket-to-another', __( 'Assign Ticket to Another', 'wpsd-support-desk' ), 'wpsd_ticket_assign_to_another_staff', 'wpsd_tickets', 'side' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

function wpsd_ticket_assign_to_another_staff(){
    include WPSD_PLUGIN_DIR.'admin/view/ticket/ticket-assign-metabox.php';
}

/**
 * @param $post_id
 * @param $post
 * @param $update
 *
 * Save reply content in ticket
 */

function wpneo_wpsd_save_reply_in_ticket( $post_id, $post, $update ) {

    $post_type      = get_post_type($post_id);
    if ( "wpsd_tickets" != $post_type ) return; // If this isn't a 'book' post, don't update it.

    if (! empty($_POST['wpsd_ticket_replay_content'])){
        $my_post = array(
            'post_content'  => $_POST['wpsd_ticket_replay_content'],
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_parent'   => $post_id,
            'post_type'     => 'wpsd_tickets'
        );
        $reply_id = wp_insert_post( $my_post ); // Insert the post into the database
        //Check if admin, so ticket status will be chagned
        if (is_admin()){
            if ($reply_id){
                update_post_meta($post_id, 'wpsd_ticket_status', 'open'); //Set ticket status to pending
            }
        }
    }
}



/**
 * Reply save from frontend and backend (admin) both
 */
if ( ! function_exists('save_reply_from_frontend_ticket_form')){
    function save_reply_from_frontend_ticket_form(){
        if ( ! empty($_POST['wpsd_frontend_ticket_reply_form'])){

            $replied_by_user_id = get_current_user_id();
            $ticket_id = sanitize_text_field($_POST['ticket_id']);
            if (! empty($_POST['wpsd_ticket_replay_content'])){
                $my_post = array(
                    'post_content'  => $_POST['wpsd_ticket_replay_content'],
                    'post_status'   => 'publish',
                    'post_author'   => get_current_user_id(),
                    'post_parent'   => $ticket_id,
                    'post_type'     => 'wpsd_tickets'
                );
                $inserted_post_id = wp_insert_post( $my_post );

                if ($inserted_post_id){
                    $is_ticket_open = get_post_meta($ticket_id, 'wpsd_ticket_status', true);
                    if ($is_ticket_open != 'open'){
                        //Open this ticket as discussion
                        $parent_ticket = get_post($ticket_id);
                        if ($parent_ticket->post_author != $replied_by_user_id){
                            update_post_meta($ticket_id, 'wpsd_ticket_status', 'open');
                        }
                    }
                    do_action('wpsd_after_ticket_replied', $inserted_post_id);
                }
            }

        }
    }
}

/**
 * Get ticket status change, closed, or reopen
 */
function wpsd_ticket_status_change(){

    if ( ! empty($_POST['ticket_id'])){
        $ticket_id          = sanitize_text_field($_POST['ticket_id']);
        $wpsd_ticket_status = sanitize_text_field($_POST['wpsd_ticket_status']);
        $get_ticket         = get_post($ticket_id);
        $ticket_owner       = $get_ticket->post_author;
        $user_id            = get_current_user_id();

        if ($ticket_owner == $user_id){
            update_post_meta($ticket_id, 'wpsd_ticket_status', $wpsd_ticket_status);

            $flash_status = '';
            switch ($wpsd_ticket_status){
                case  'closed':
                    $flash_status = 'closed';
                    break;
                case  're-open':
                    $flash_status = 're-opened';
                    break;
            }
            wpsd_flash('success', __('Ticket has been '.$flash_status, 'wp-support-desk'), 'success');
        }else{
            wpsd_flash('success', __('You are not authorised to do this action', 'wp-support-desk'), 'warning');
        }
    }
}


/**
 * Assigning ticket to users
 *
 */
add_action( 'wp_ajax_wpsd_assign_ticket_to_user', 'wpsd_assign_ticket_to_user_callback' );
function wpsd_assign_ticket_to_user_callback() {

    global $wpdb; // this is how you get access to the database
    $assigned_by        = get_current_user_id();
    $current_datetime   = date("Y-m-d H:i:s");
    $post_id            = (int) sanitize_text_field(wpsd_post('post_id'));

    if ( ! empty($_POST['assigned_users'])){
        $assigned_user = $_POST['assigned_users']; //array

        //Read each user by one
        foreach($assigned_user as $user_id){
            //Keep unique entry, that why we have checked it
            $query_if_have_previously = $wpdb->get_row("SELECT * from {$wpdb->prefix}wpsd_assigned_tickets_users where post_id = {$post_id} and author_id = {$user_id}");

            if (! $query_if_have_previously) {
                $user_id = intval(sanitize_text_field($user_id)); //Assign user to ticket :)

                $is_insert_assigned = $wpdb->insert(
                    $wpdb->prefix . 'wpsd_assigned_tickets_users',
                    array(
                        'post_id'       => $post_id,
                        'author_id'     => $user_id,
                        'assigned_by'   => $assigned_by,
                        'assigned_at'   => $current_datetime,
                    ),
                    array(
                        '%d',
                        '%d',
                        '%d',
                        '%s'
                    )
                );

                if ($is_insert_assigned){
                    do_action('wpsd_during_single_user_assign', $post_id, $user_id, $assigned_by);
                }
            }
        }
        die(json_encode(array('success' => 1, 'msg' => __('Ticket has been assigned to the selected users', 'wp-support-desk') )));
    }
    die(json_encode(array('success' => 0, 'msg' => __('There are no user to do this action', 'wp-support-desk') )));
}

/**
 * Remove from assigning ticket
 *
 * todo: there have to be add activities
 */
add_action( 'wp_ajax_wpsd_user_remove_from_assigned_ticket', 'wpsd_user_remove_from_assigned_ticket_callback' );
function wpsd_user_remove_from_assigned_ticket_callback(){
    global $wpdb;
    $post_id        = (int) sanitize_text_field(wpsd_post('post_id'));
    $assigned_user  = (int) sanitize_text_field(wpsd_post('assigned_user'));
    if ($post_id) {
        $deleted_assigned_user = $wpdb->query("DELETE from {$wpdb->prefix}wpsd_assigned_tickets_users where post_id = {$post_id} and author_id = {$assigned_user}");
        if ($deleted_assigned_user){
            die(json_encode(array('success' => 1, 'msg' => __('User has been removed from assigned list', 'wp-support-desk') )));
        }
    }
    die(json_encode(array('success' => 0, 'msg' => __('Something went wrong, please try again', 'wp-support-desk') )));
}


/**
 * Delete Ratting by Admin
 */
add_action( 'wp_ajax_wpsd_rating_delete', 'wpsd_rating_delete_callback' );
function wpsd_rating_delete_callback(){
    global $wpdb;
    $rating_id        = (int) sanitize_text_field(wpsd_post('rating'));
    if ($rating_id) {
        $deleted_rating = $wpdb->query("DELETE FROM {$wpdb->prefix}wpsd_staff_rating WHERE ID = {$rating_id}");
        if ($deleted_rating){
            die(json_encode(array('success' => 1, 'msg' => __('Rating has been removed.', 'wp-support-desk') )));
        }
    }
    die(json_encode(array('success' => 0, 'msg' => __('Something went wrong, please try again.', 'wp-support-desk') )));
}

/**
 * Delete Ticket by Admin
 */
add_action( 'wp_ajax_wpsd_ticket_delete', 'wpsd_ticket_delete_callback' );
function wpsd_ticket_delete_callback(){
    global $wpdb;
    $ticket_id          = (int) sanitize_text_field(wpsd_post('ticket'));
    if ($ticket_id) {
        $deleted_ticket = wp_delete_post( $ticket_id,true );
        if ( $deleted_ticket ){
            die(json_encode(array('success' => 1, 'msg' => __('Ticket has been removed.', 'wp-support-desk') )));
        }
    }
    die(json_encode(array('success' => 0, 'msg' => __('Something went wrong, please try again.', 'wp-support-desk') )));
}


/**
 * Delete Department by Admin
 */
add_action( 'wp_ajax_wpsd_department_delete', 'wpsd_department_delete_callback' );
function wpsd_department_delete_callback(){
    global $wpdb;
    $department_id = (int) sanitize_text_field(wpsd_post('department'));
    if ($department_id){
        $deleted_department = wp_delete_post( $department_id,true );
        if ($deleted_department){
            die(json_encode(array('success' => 1, 'msg' => __('Department has been removed.', 'wp-support-desk') )));
        }
    }
    die(json_encode(array('success' => 0, 'msg' => __('Something went wrong, please try again.', 'wp-support-desk') )));
}



/**
 * Delete Support Staff by Admin
 */
add_action( 'wp_ajax_wpsd_support_staff_delete', 'wpsd_support_staff_delete_callback' );
function wpsd_support_staff_delete_callback(){
    global $wpdb;
    $support_staff_id = (int) sanitize_text_field(wpsd_post('support_staff'));
    if ($support_staff_id){
        $deleted_support_staff = $wpdb->query("DELETE from {$wpdb->prefix}wpsd_ticket_users where user_ID = {$support_staff_id}");
        if ($deleted_support_staff){
            die(json_encode(array('success' => 1, 'msg' => __('Support staff has been removed.', 'wp-support-desk') )));
        }
    }
    die(json_encode(array('success' => 0, 'msg' => __('Something went wrong, please try again.', 'wp-support-desk') )));
}



/**
 * Delete Custom Field by Admin
 */
add_action( 'wp_ajax_wpsd_custom_field_delete', 'wpsd_custom_field_delete_callback' );
function wpsd_custom_field_delete_callback(){
    global $wpdb;
    $custom_id = (int) sanitize_text_field(wpsd_post('custom'));
    if ($custom_id){
        $deleted_custom_field = $wpdb->query("DELETE from {$wpdb->prefix}wpsd_custom_field where id = {$custom_id}");
        if ($deleted_custom_field){
            die(json_encode(array('success' => 1, 'msg' => __('Support custom field has been removed.', 'wp-support-desk') )));
        }
    }
    die(json_encode(array('success' => 0, 'msg' => __('Something went wrong, please try again.', 'wp-support-desk') )));
}




add_action( 'wp_ajax_wpsd_get_user_from_assigned_ticket', 'wpsd_get_user_from_assigned_ticket_callback' );
function wpsd_get_user_from_assigned_ticket_callback(){
    $post_id        = (int) sanitize_text_field(wpsd_post('post_id'));
    $assigned_user_ids_to_this_ticket = get_wpsd_assigned_user_ids($post_id);

    $html = '';
    ob_start();
    if ($assigned_user_ids_to_this_ticket) {
        ?>
        <table class="table table-striped assigned_user_list_ticket_backend">
            <?php
            foreach ($assigned_user_ids_to_this_ticket as $assigned_uid) {
                ?>
                <tr>
                    <td class="column-username">
                        <?php
                        echo get_avatar($assigned_uid, 30);
                        echo "<strong>".get_the_author_meta( 'display_name', $assigned_uid )."</strong>";
                        ?>
                    </td>
                    <td>
                        <a href="javascript:;" class="btn btn-danger btn-xs removeAssignedUser" data-id="<?php echo $assigned_uid; ?>"><i class="glyphicon glyphicon-trash"></i> </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    $html = ob_get_clean();
    echo $html;
    die();
}

/**
 * Filter ticket post in list table only parent, so it will be query only main ticket
 */
add_filter( 'parse_query', 'filter_only_parent_ticket_in_admin_list' );
function  filter_only_parent_ticket_in_admin_list($query) {

    global $pagenow;
    $current_page       = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
    if ( is_admin() && 'wpsd_tickets' == $current_page && 'edit.php' == $pagenow ) {

        $query->query_vars['post_parent'] = 0; //Get only parent ticket, not reply
        global $wpdb; //Get ticket author and assigned ticket
        $authors_in     = array();
        $current_user   = get_current_user_id();
        $get_user_info  = get_wpsd_ticket_user_row($current_user);

        if ($get_user_info) {
            if ($get_user_info->role != 'manager') { //Check if current user are not manager

                $depament_ids        = $get_user_info->departments; //Get department and categories id
                $is_serialize        = @unserialize($depament_ids); //Determine if department ids is a serialize string, if yes, then unserialize it first
                if ($depament_ids === 'b:0;' || $is_serialize !== false) {
                    $depament_ids    = $is_serialize;
                }
                $depament_ids        = implode(",", $depament_ids);
                $categories          = unserialize($get_user_info->categories); //Get categories ids and imploted it to string
                $imploted_categories = implode(',', $categories);

                //Get post ids by default from accessible department and categories
                $default_post_ids_query = $wpdb->get_col("SELECT 
                                                            {$wpdb->posts}.ID 
                                                        FROM {$wpdb->posts}  
                                                        INNER JOIN 
                                                            {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id) 
                                                        INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) 
                                                        WHERE 
                                                            {$wpdb->posts}.post_parent = 0  
                                                        AND ({$wpdb->term_relationships}.term_taxonomy_id IN ({$imploted_categories})) 
                                                        AND (( {$wpdb->postmeta}.meta_key = 'wpsd_department_id' 
                                                        AND {$wpdb->postmeta}.meta_value IN ({$depament_ids}))) 
                                                        AND {$wpdb->posts}.post_type = 'wpsd_tickets' 
                                                        AND ({$wpdb->posts}.post_status = 'publish' 
                                                        OR {$wpdb->posts}.post_status = 'private')");
                //Get assigned post id by others
                $assigned_post_ids  = $wpdb->get_col("SELECT post_id FROM {$wpdb->prefix}wpsd_assigned_tickets_users WHERE author_id = {$current_user}");
                $all_post_ids       = array_merge($assigned_post_ids, $default_post_ids_query); //Merge them both result together
                $all_post_ids       = array_unique($all_post_ids);
                if (!$all_post_ids){
                    $all_post_ids   = array(1);
                }
                $query->query_vars['post__in'] = $all_post_ids; //Send them to master query
            }
        }else{
            $query->query_vars['post__in'] = array(1);
        }
    }
    return $query;
}




if ( ! empty($_POST['wpsd_settings_save_btn'])){

    if (wp_verify_nonce( sanitize_text_field(wpsd_post('wpsd_settings_page_nonce_field')), 'wpsd_settings_page_action' ) ){
        //Grab all post form settings and save them with foreach loop
        if (! empty($_POST['wpsd_settings_option'])){
            $wpsd_settings_option   = $_POST['wpsd_settings_option'];
            foreach ($wpsd_settings_option as $option_name => $option_value){
                $sanitized_value    = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $wpsd_settings_option[$option_name])));
                wpsd_update_settings_option($option_name, $sanitized_value);
            }
        }

        wpsd_flash( __('Settings has been saved', 'wp-support-desk'), 'success');
        wpsd_redirect_back();
    }
}


/**
 * Save custom field
 */
if ( ! empty($_POST['wpsd_save_field_btn'])){
    if (wp_verify_nonce( sanitize_text_field(wpsd_post('wpsd_settings_page_nonce_field')), 'wpsd_settings_page_action' ) ){
        global $wpdb;
        //Grab all post form settings and save them with foreach loop
        $field_type     = sanitize_text_field($_POST['field_type']);
        $field_title    = sanitize_text_field(wpsd_post('field_title'));
        $field_name     = sanitize_text_field(wpsd_post('field_name'));
        $help_text      = sanitize_text_field(wpsd_post('help_text'));
        $value          = sanitize_text_field(wpsd_post('value'));
        $default_value  = sanitize_text_field(wpsd_post('default_value'));
        $size           = sanitize_text_field(wpsd_post('size'));
        $max_length     = sanitize_text_field(wpsd_post('max_length'));
        $field_required = sanitize_text_field(wpsd_post('field_required'));

        $insert_custom_field = $wpdb->insert(
            $wpdb->prefix.'wpsd_custom_field',
            array(
                'title'         => $field_title,
                'field_name'    => $field_name,
                'type'          => $field_type,
                'value'         => $value,
                'size'          => $size,
                'max_length'    => $max_length,
                'help_text'     => $help_text,
                'default_value' => $default_value,
                'field_required'=> $field_required,
            )
        );

        if ($insert_custom_field){
            wpsd_flash( __('Custom field has been added', 'wp-support-desk'), 'success');
            wpsd_redirect_back();
        }

    }
}

// Add Helpful Column in Knowledgebase
add_filter( 'manage_wpsd_knowledgebase_posts_columns', 'set_custom_edit_book_columns' );
add_action( 'manage_wpsd_knowledgebase_posts_custom_column' , 'custom_book_column', 10, 2 );
function set_custom_edit_book_columns($columns) {
    $columns['like_status'] = __( 'Helpful Status', 'wp-support-desk' );
    return $columns;
}
function custom_book_column( $column, $post_id ) {
    switch ( $column ) {
        case 'like_status':
            global $wpdb;
            $get_yes_vote   = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = {$post_id} AND meta_key LIKE 'wpsd_kb_article_voted_user%' AND meta_value = 'yes' " );
            $get_no_vote    = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = {$post_id} AND meta_key LIKE 'wpsd_kb_article_voted_user%' AND meta_value = 'no' " );

            echo __( 'Like ', 'wp-support-desk' ) . $get_yes_vote . ' / ';
            echo __( 'Dislike ', 'wp-support-desk' ) . $get_no_vote;
            break;
    }
}

