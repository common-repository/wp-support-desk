/**
 * Jquery script for WP Support Desk Plugin
 */

jQuery(document).ready(function($){


    // Delete Ticket
    $('body').on('click', '.wpsd-delete-ticket',function(){
        var answer = confirm("Delete Ticket?");
        if (answer){
            var ticket = $(this).data('ticket-id');
            var form_data = { action : 'wpsd_ticket_delete', ticket : ticket };
            var clicked = $(this);
            $.ajax({
                type : 'POST',
                url : ajaxurl,
                data : form_data,
                success : function(data){
                    var success_data = JSON.parse(data);
                    if (success_data.success == 1){
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                        clicked.closest('tr').hide('slow');
                    } else {
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                    }
                }
            });
        }
    });


    // Delete Rating
    $('body').on('click', '.wpsd-delete-ratting',function(){
        var answer = confirm("Delete rating?");
        if (answer){
            var rating = $(this).data('rating-id');
            var form_data = { action : 'wpsd_rating_delete', rating : rating };
            var clicked = $(this);
            $.ajax({
                type : 'POST',
                url : ajaxurl,
                data : form_data,
                success : function(data){
                    var success_data = JSON.parse(data);
                    if (success_data.success == 1){
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                        clicked.closest('tr').hide('slow');
                    } else {
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                    }
                }
            });
        }
    });


    // Delete Departments
    $('body').on('click', '.wpsd-delete-department',function(){
        var answer = confirm("Delete Department?");
        if (answer){
            var department = $(this).data('department-id');
            var form_data = { action : 'wpsd_department_delete', department : department };
            var clicked = $(this);
            $.ajax({
                type : 'POST',
                url : ajaxurl,
                data : form_data,
                success : function(data){
                    var success_data = JSON.parse(data);
                    if (success_data.success == 1){
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                        clicked.closest('tr').hide('slow');
                    } else {
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                    }
                }
            });
        }
    });


    // Delete Support Staff
    $('body').on('click', '.wpsd-delete-support-staff',function(){
        var answer = confirm("Delete Support Staff?");
        if (answer){
            var support_staff = $(this).data('support-staff');
            var form_data = { action : 'wpsd_support_staff_delete', support_staff : support_staff };
            var clicked = $(this);
            $.ajax({
                type : 'POST',
                url : ajaxurl,
                data : form_data,
                success : function(data){
                    var success_data = JSON.parse(data);
                    if (success_data.success == 1){
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                        clicked.closest('tr').hide('slow');
                    } else {
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                    }
                }
            });
        }
    });



    // Delete Custom Fields
    $('body').on('click', '.wpsd-delete-custom-field',function(){
        console.log("This is Working");
        var answer = confirm("Delete Custom Field?");
        if (answer){
            var custom = $(this).data('custom-field');
            var form_data = { action : 'wpsd_custom_field_delete', custom : custom };
            var clicked = $(this);
            $.ajax({
                type : 'POST',
                url : ajaxurl,
                data : form_data,
                success : function(data){
                    var success_data = JSON.parse(data);
                    if (success_data.success == 1){
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                        clicked.closest('tr').hide('slow');
                    } else {
                        $('#assignedStatusMsg').html('<div class="panel panel-danger"><div class="panel-heading">'+success_data.msg+'</div></div>');
                    }
                }
            });
        }
    });




    $('select.wpsd-select2').select2();
    $('[data-toggle="tooltip"]').tooltip();
    
    $('body').on('click', '#assignUserToTicket',function(){
        var assignedUsers = $('#assignUserSelect').val();
        //Get form field into json object
        var post_id = $('#post_ID').val();
        var form_data = { action : 'wpsd_assign_ticket_to_user', post_id : post_id, assigned_users : assignedUsers };

        var clicked_btn = $(this);
        $(clicked_btn).val('Assigning...');
        $(clicked_btn).attr('disabled', 'disabled');
        $.ajax({
            type : 'POST',
            url : ajaxurl,
            data : form_data,
            success : function(data){
                var success_data = JSON.parse(data);
                if (success_data.success == 1){
                    $(clicked_btn).val('Assign');
                    $(clicked_btn).removeAttr('disabled');
                    $('#assignedStatusMsg').html('<p style="color: #008000">'+success_data.msg+'</p>');
                    //location.href = success_data.wpsd_success_redirect_url;

                    /**
                     * Get assigned users immediatly
                     */
                    $.ajax({
                        type : 'POST',
                        url : ajaxurl,
                        data : {action: 'wpsd_get_user_from_assigned_ticket', post_id : post_id,},
                        success : function(data){
                            $('#ticket_details_backend_assigned_user_list_table_wrap').html(data);
                        }
                    });
                    
                }else {
                    $(clicked_btn).val('Assign');
                    $(clicked_btn).removeAttr('disabled');
                    $('#assignedStatusMsg').html('<p style="color: #ff0000">'+success_data.msg+'</p>');
                }
            }
        })
    });

    /**
     * Remove assigned user from ticket
     */
    $('body').on('click', 'a.removeAssignedUser', function(){
        if ( ! confirm('Are you sure?'))
            return;

        var assignedUsers = $(this).data('id');
        var post_id = $('#post_ID').val();
        var form_data = { action : 'wpsd_user_remove_from_assigned_ticket', post_id : post_id, assigned_user : assignedUsers };
        var clicked_btn = $(this);

        $.ajax({
            type : 'POST',
            url : ajaxurl,
            data : form_data,
            success : function(data){
                var success_data = JSON.parse(data);
                if (success_data.success == 1){
                    $('#assignedStatusMsg').html('<p style="color: #008000">'+success_data.msg+'</p>');
                    clicked_btn.closest('tr').hide('slow');
                }
            }
        })
    });

    $('body').on('click', '.wpsd-ticket-view-admin', function(e){
        e.preventDefault();
        var wpsd_ticket_id = $(this).data('ticket-id');

        $.post( ajaxurl, { ticket_id: wpsd_ticket_id, action : 'wpsd_ticket_view_admin' }, function( data ) {
            $('.wpneo-wpsd-admin-ajax-wrap').html(data);
            var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_ticket_replay_content"), showIcons: ["code", "table"] });
            //Initialize It again
            $('select.wpsd-select2').select2();
        });
    });

    $('body').on('click', '.wpsd-load-tickets-home-admin', function(e){
        e.preventDefault();
        $.post( ajaxurl, { action : 'wpsd_load_tickets_home_admin' }, function( data ) {
            $('.wpneo-wpsd-admin-ajax-wrap').html(data);
        });
    });

    $('body').on('click', '.wpsd-ticket-pagination ul li a', function(e){
        e.preventDefault();
        var wpsd_load_url = $(this).attr('href');
        var current_page = parseInt(getParameterByName('paged', $(this).attr('href')));

        $.post(ajaxurl, {current_page : current_page, action : 'wpsd_load_tickets_home_admin' }, function( data ) {
            $('.wpneo-wpsd-admin-ajax-wrap').html(data);
        });

    });
    
    $('body').on('click', '.wpsd_ticket_status_change_btn', function(e){
        e.preventDefault();
        var ticket_status = $('#wpsd_ticket_status_change').val();
        var ticket_id = $('[name="ticket_id"]').val();

        $.post( ajaxurl, {action : 'wpsd_change_ticket_status_admin', ticket_id : ticket_id, wpsd_ticket_status : ticket_status  }, function( data ) {
            $('.wpneo-wpsd-admin-ajax-wrap').html(data);
        });
    });

    $('body').on('submit','#wpsd_post_ticket_reply_from_admin',function(e){
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        formData.append('action', 'save_reply_from_admin_ticket_form');
        $('.wpsd_admin_reply_btn').attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: formData,
            contentType: false,
            processData: false,
            success : function (data) {
                $('.wpneo-wpsd-admin-ajax-wrap').html(data);
                $('.wpsd_admin_reply_btn').removeAttr('disabled');
                var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_ticket_replay_content"), showIcons: ["code", "table"] });
            }
        });
    });

    $('body').on('click', '.wpsd_ticket_search_admin_btn', function(e){
        e.preventDefault();
        var wpsd_ticket_search_term_admin = $('#wpsd_ticket_search_admin').val();
        if (wpsd_ticket_search_term_admin.length > 0){
            $.post( ajaxurl, { wpsd_ticket_search_term_admin: wpsd_ticket_search_term_admin, action : 'wpsd_ticket_search_admin' }, function( data ) {
                $('.wpneo-wpsd-admin-ajax-wrap').html(data);
            });
        }
    });

    $('body').on('click', 'a#wpsd_create_new_ticket_admin', function(e){
        e.preventDefault();
        $.post( ajaxurl, { action : 'wpsd_create_new_ticket_admin' }, function( data ) {
            $('.wpneo-wpsd-admin-ajax-wrap').html(data);
            var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_description"), showIcons: ["code", "table"] });
            $('select.wpsd-select2').select2();
        });
    });

    /**
     * Create ticket on behalf of another user from admin
     */
    $('body').on('submit','#wpsd-create-ticket-form',function(e){
        e.preventDefault();
        //var form_data = $(this).serialize()+'&action=wpsd_create_ticket';
        var form_data = new FormData(this);
        form_data.append('action', 'wpsd_create_ticket');
        form_data.append('creating_from', 'wpsd_admin');

        $('#wpsd_ticket_submit_btn').text('Submitting...');
        $('#wpsd_ticket_submit_btn').attr('disabled', 'disabled');
        $.ajax({
            type : 'POST',
            url : ajaxurl,
            data : form_data,
            cache:false,
            contentType: false,
            processData: false,
            success : function(data){
                var success_data = JSON.parse(data);
                if (success_data.success == 1){
                    $('#wpsd_ticket_submit_btn').text('Submit Ticket');
                    $('#wpsd_ticket_submit_btn').removeAttr('disabled');
                    //location.href = success_data.wpsd_success_redirect_url;
                    $.post( ajaxurl, { action : 'wpsd_load_tickets_home_admin' }, function( data ) {
                        $('.wpneo-wpsd-admin-ajax-wrap').html(data);
                    });
                }
            }
        })
    });


    $('body').on('keyup','#add_field #field_name',function(e){
        $(this).val(wpneo_wpsd_slugify($(this).val()));
    });
    
    function wpneo_wpsd_slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    $('.maincolor').wpColorPicker();

});

