<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('WPNEO_WPSD_Register_Post_Type')){
    class WPNEO_WPSD_Register_Post_Type{
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
            /**
             * Register Ticket Post Type
             */
            add_action( 'init', array($this, 'wpneo_wpsd_register_ticket_post_type') );

            /**
             * Register Taxonomy
             */
            add_action('init', array($this, 'add_taxonomy_to_ticket_post_type'));

            /**
             * Add new category, priority data fields
             */
            add_action( 'wpsd_category_add_form_fields', array($this, 'add_new_custom_fields_in_ticket_category'));
            add_action( 'wpsd_priority_add_form_fields', array($this, 'add_new_custom_fields_in_ticket_category'));

            /**
             * Edit category, priority data fields
             */
            add_action( 'wpsd_category_edit_form_fields', array($this, 'edit_custom_fields_in_ticket_category'));
            add_action( 'wpsd_priority_edit_form_fields', array($this, 'edit_custom_fields_in_ticket_category'));


            /**
             * Save the category data
             */
            add_action( 'edited_wpsd_category', array($this, 'save_wpsd_category_meta') );
            add_action( 'edited_wpsd_priority', array($this, 'save_wpsd_category_meta') );
            add_action( 'create_wpsd_category', array($this, 'save_wpsd_category_meta') );
            add_action( 'create_wpsd_priority', array($this, 'save_wpsd_category_meta') );

            add_filter('post_row_actions',array($this, 'edit_row_action_wpsd_tickets'), 10, 2);

            /**
             * WPSD Category taxonomy custom column
             */

            add_filter( "manage_edit-wpsd_category_columns", array($this, 'wpsd_add_columns_wpsd_category') );
            add_filter( "manage_wpsd_category_custom_column", array($this, 'wpsd_category_column_data'), 10, 3 );


            //Add and manage ticket post type columns to admin post list
            add_action( 'manage_wpsd_tickets_posts_custom_column', array($this, 'wpsd_adding_ticket_subject_columns'), 10, 2 );
            add_filter('manage_wpsd_tickets_posts_columns', array($this, 'wpsd_manage_custom_columns_to_ticket_post_type'));

        }

        public function wpneo_wpsd_register_ticket_post_type() {

            $labels = array(
                'name'               => _x( 'Tickets', 'post type general name', 'wp-support-desk' ),
                'singular_name'      => _x( 'Ticket', 'post type singular name', 'wp-support-desk' ),
                'menu_name'          => _x( 'Tickets', 'admin menu', 'wp-support-desk' ),
                'name_admin_bar'     => _x( 'Ticket', 'add new on admin bar', 'wp-support-desk' ),
                'add_new'            => _x( 'Add New', 'Ticket', 'wp-support-desk' ),
                'add_new_item'       => __( 'Add New Ticket', 'wp-support-desk' ),
                'new_item'           => __( 'New Ticket', 'wp-support-desk' ),
                'edit_item'          => __( 'Edit Ticket', 'wp-support-desk' ),
                'view_item'          => __( 'View Ticket', 'wp-support-desk' ),
                'all_items'          => __( 'All Tickets', 'wp-support-desk' ),
                'search_items'       => __( 'Search Tickets', 'wp-support-desk' ),
                'parent_item_colon'  => __( 'Parent Tickets:', 'wp-support-desk' ),
                'not_found'          => __( 'No Tickets found.', 'wp-support-desk' ),
                'not_found_in_trash' => __( 'No Tickets found in Trash.', 'wp-support-desk' )
            );

            $args = array(
                'public' => true,
                'labels'  => $labels,
                'supports' => array('title','editor'),
                'show_in_menu' => false
            );
            register_post_type( 'wpsd_tickets', $args );




            $labels = array(
                'name'               => _x( 'Knowledgebase', 'post type general name', 'wp-support-desk' ),
                'singular_name'      => _x( 'Knowledgebase', 'post type singular name', 'wp-support-desk' ),
                'menu_name'          => _x( 'Knowledgebase', 'admin menu', 'wp-support-desk' ),
                'name_admin_bar'     => _x( 'Knowledgebase', 'add new on admin bar', 'wp-support-desk' ),
                'add_new'            => _x( 'Add New', 'Knowledgebase', 'wp-support-desk' ),
                'add_new_item'       => __( 'Add New Knowledgebase', 'wp-support-desk' ),
                'new_item'           => __( 'New Knowledgebase', 'wp-support-desk' ),
                'edit_item'          => __( 'Edit Knowledgebase', 'wp-support-desk' ),
                'view_item'          => __( 'View Knowledgebase', 'wp-support-desk' ),
                'all_items'          => __( 'All Knowledgebase', 'wp-support-desk' ),
                'search_items'       => __( 'Search Knowledgebase', 'wp-support-desk' ),
                'parent_item_colon'  => __( 'Parent Knowledgebase:', 'wp-support-desk' ),
                'not_found'          => __( 'No Knowledgebase found.', 'wp-support-desk' ),
                'not_found_in_trash' => __( 'No Knowledgebase found in Trash.', 'wp-support-desk' )
            );

            $args = array(
                'public' => true,
                'labels'  => $labels,
                //'supports' => array('title','editor'),
                'show_in_menu' => true,
                'capability_type'    => array("wpsd_knowledgebase", "wpsd_knowledgebases"),
                'map_meta_cap'       => true,

            );
            register_post_type( 'wpsd_knowledgebase', $args );
        }

        public function add_taxonomy_to_ticket_post_type(){

            $labels = array(
                'name'              => _x( 'Categories', 'Categories', 'wp-support-desk' ),
                'singular_name'     => _x( 'Category', 'Category', 'wp-support-desk' ),
                'popular_items'     => NULL,
                'search_items'      => __( 'Search Categorys', 'wp-support-desk' ),
                'all_items'         => __( 'All Categorys', 'wp-support-desk' ),
                'parent_item'       => __( 'Parent Category', 'wp-support-desk' ),
                'parent_item_colon' => __( 'Parent Category:', 'wp-support-desk' ),
                'edit_item'         => __( 'Edit Category', 'wp-support-desk' ),
                'update_item'       => __( 'Update Category', 'wp-support-desk' ),
                'add_new_item'      => __( 'Add New Category', 'wp-support-desk' ),
                'new_item_name'     => __( 'New Category Name', 'wp-support-desk' ),
                'menu_name'         => __( 'Categories', 'wp-support-desk' ),
            );

            $args = array(
                'hierarchical'          => false,
                'labels'                => $labels,
                'public'                => false,
                'show_ui'               => true,
                'show_admin_column'     => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
            );

            register_taxonomy( 'wpsd_category', 'wpsd_tickets', $args );

            //Priority
            $labels = array(
                'name'              => _x( 'Priorities', 'Priorities', 'wp-support-desk' ),
                'singular_name'     => _x( 'Priority', 'Priority', 'wp-support-desk' ),
                'popular_items'     => NULL,
                'search_items'      => __( 'Search Prioritys', 'wp-support-desk' ),
                'all_items'         => __( 'All Prioritys', 'wp-support-desk' ),
                'parent_item'       => __( 'Parent Priority', 'wp-support-desk' ),
                'parent_item_colon' => __( 'Parent Priority:', 'wp-support-desk' ),
                'edit_item'         => __( 'Edit Priority', 'wp-support-desk' ),
                'update_item'       => __( 'Update Priority', 'wp-support-desk' ),
                'add_new_item'      => __( 'Add New Priority', 'wp-support-desk' ),
                'new_item_name'     => __( 'New Priority Name', 'wp-support-desk' ),
                'menu_name'         => __( 'Priorities', 'wp-support-desk' ),
            );
            $args = array(
                'hierarchical'          => false,
                'labels'                => $labels,
                'public'                => false,
                'capabilities'          => array (
                    'manage_terms'  => 'manage_wpsd_priority', //by default only admin
                    'edit_terms'    => 'manage_wpsd_priority',
                    'delete_terms'  => 'manage_wpsd_priority',
                ),
                'show_ui'               => true,
                'show_admin_column'     => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
            );
            register_taxonomy( 'wpsd_priority', 'wpsd_tickets', $args );

            //Knowledgebase categories
            $labels_kb = array(
                'name'              => _x( 'KB Categories', 'KB Categories', 'wp-support-desk' ),
                'singular_name'     => _x( 'KB Category', 'KB Category', 'wp-support-desk' ),
                'popular_items'     => NULL,
                'search_items'      => __( 'Search KB Categories', 'wp-support-desk' ),
                'all_items'         => __( 'All KB Categories', 'wp-support-desk' ),
                'parent_item'       => __( 'Parent KB Category', 'wp-support-desk' ),
                'parent_item_colon' => __( 'Parent KB Category:', 'wp-support-desk' ),
                'edit_item'         => __( 'Edit KB Category', 'wp-support-desk' ),
                'update_item'       => __( 'Update KB Category', 'wp-support-desk' ),
                'add_new_item'      => __( 'Add New KB Category', 'wp-support-desk' ),
                'new_item_name'     => __( 'New KB Category Name', 'wp-support-desk' ),
                'menu_name'         => __( 'KB Categories', 'wp-support-desk' ),
            );
            $args_kb = array(
                'hierarchical'          => true,
                'labels'                => $labels_kb,
                'public'                => true,
                'capabilities'          => array (
                    'manage_terms'  => 'manage_kb_categories', //by default only admin
                    'edit_terms'    => 'manage_kb_categories',
                    'delete_terms'  => 'manage_kb_categories',
                    'assign_terms'  => 'edit_wpsd_knowledgebases'
                ),
                'show_ui'               => true,
                'show_admin_column'     => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
                'show_in_nav_menus'     => true,
            );
            register_taxonomy( 'wpsd_kb_categories', array('wpsd_knowledgebase'), $args_kb );
        }

        public function add_new_custom_fields_in_ticket_category(){
            //Get department query
            $departments_query = wpsd_get_departments_query();
            ?>
            <div class="form-field">
                <label for="wpneo_wpsd_term_meta[department_id]"><?php _e('Department', 'wp-support-desk'); ?></label>

                <select name="wpneo_wpsd_term_meta[department_id]">
                    <option value=""><?php _e('Select department', 'wp-support-desk'); ?></option>
                    <?php
                    if ($departments_query->have_posts()) {
                        while($departments_query->have_posts()) {
                            $departments_query->the_post();
                            echo "<option value='".get_the_ID()."'>".get_the_title()."</option>";
                        }
                    }
                    ?>
                </select>
                <p class="description"><?php _e('Category will visible under this department', 'wp-support-desk'); ?></p>

            </div>


            <div class="form-field">
                <label for="wpneo_wpsd_term_meta[color]"><?php _e( 'Color', 'wp-support-desk' ); ?></label>
                <input type="text" name="wpneo_wpsd_term_meta[color]" id="wpneo_wpsd_term_meta[color]" class="maincolor" value="">
            </div>
            <?php
        }

        public function edit_custom_fields_in_ticket_category($term){
            $color = get_term_meta($term->term_id, 'color', true);
            $department_id = get_term_meta($term->term_id, 'department_id', true);
            //Get department query
            $departments_query = wpsd_get_departments_query();
            ?>

            <tr class="form-field">
                <th scope="row" valign="top"><label for="wpneo_wpsd_term_meta[department_id]"><?php _e('Department', 'wp-support-desk'); ?></label></th>
                <td>

                    <select name="wpneo_wpsd_term_meta[department_id]">
                        <option value=""><?php _e('Select department', 'wp-support-desk'); ?></option>
                        <?php
                        if ($departments_query->have_posts()) {
                            while($departments_query->have_posts()) {
                                $departments_query->the_post();

                                $selected = ( $department_id == get_the_ID()) ? 'selected' : '';
                                echo "<option value='".get_the_ID()."' {$selected}>".get_the_title()."</option>";
                            }
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Category will visible under this department', 'wp-support-desk'); ?></p>
                </td>
            </tr>


            <tr class="form-field">
                <th scope="row" valign="top"><label for="wpneo_wpsd_term_meta[color]"><?php _e( 'Color', 'wp-support-desk' ); ?></label></th>
                <td>
                    <input type="text" name="wpneo_wpsd_term_meta[color]" id="pneo_wpsd_term_meta[color]" class="maincolor" value="<?php echo $color; ?>">
                </td>
            </tr>

            <?php
        }

        public function save_wpsd_category_meta($term_id){
            if ( ! empty($_POST['wpneo_wpsd_term_meta'])){
                $wpneo_wpsd_term_meta = $_POST['wpneo_wpsd_term_meta'];


                if (count($wpneo_wpsd_term_meta) > 0){
                    foreach($wpneo_wpsd_term_meta as $key => $value){
                        if ( ! empty($value))
                            update_term_meta($term_id, $key, $value);
                    }
                }
            }
        }

        /**
         * @param $new_columns
         * @return array
         *
         * Add column to WPSD Categories admin list
         */
        public function wpsd_add_columns_wpsd_category( $new_columns ) {
            $new_columns['department'] = __('Department', 'wp-support-desk');
            return $new_columns;
        }

        public function wpsd_category_column_data($value, $name, $id ){
            $taxanomy = get_term_meta($id, 'department_id', true);
            return get_the_title($taxanomy);
        }



        /**
         * @param $actions
         * @param $post
         * @return mixed
         *
         * Customized row actions in wpsd_tickets table
         */

        function edit_row_action_wpsd_tickets($actions, $post){
            //check for your post type
            if ($post->post_type =="wpsd_tickets"){
                unset($actions['inline hide-if-no-js']);
                unset($actions['trash']);
                unset($actions['view']);
            }
            return $actions;
        }

        /**
         * @param $column_name
         * @param $post_id
         *
         * add ticket post type admin columns
         */
        function wpsd_adding_ticket_subject_columns( $column_name, $post_id ) {
            global $wpdb;
            $current_user_id = get_current_user_id();

            if ($column_name == 'ticket_subject'){
                $subject_html = '<strong><a class="row-title" href="'.admin_url('post.php?post='.$post_id.'&amp;action=edit').'">'.get_the_title().'</a></strong>';

                $is_assigned_user_id = $wpdb->get_var("select assigned_by from {$wpdb->prefix}wpsd_assigned_tickets_users where post_id = {$post_id} AND author_id = {$current_user_id} limit 1");

                if ($is_assigned_user_id){
                    $assigned_user_name = get_the_author_meta('display_name', $is_assigned_user_id);
                    $subject_html .= "<br /> ";
                    $subject_html .= __('Assigned by: ', 'wp-support-desk')." ".$assigned_user_name;
                }

                echo $subject_html;
            }

        }

        /**
         * @param $defaults
         * @return array
         *
         * Manage ticket post type admin columns
         */
        function wpsd_manage_custom_columns_to_ticket_post_type( $defaults ) {
            unset($defaults['title']);

            //$defaults['ticket_subject']  = 'Ticket Subject';
            $cb = $defaults['cb'];
            unset($defaults['cb']);
            $new_collumns = array_merge(array('ticket_subject' => 'Ticket Subject' ) , $defaults);

            $new_collumns = array_merge(array('cb' => $cb), $new_collumns);

            return $new_collumns;
        }




    }
}

WPNEO_WPSD_Register_Post_Type::instance();