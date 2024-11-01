/**
 * Jquery script for WP Support Desk Plugin
 */

jQuery(document).ready(function($){
    /**
     * Initialized Validator Plugin
     */
    if ($.validate()){
        $.validate();
    }

    //Add Added
    // Select
    $(document).on('click', function(e) {
        var selector = $('.wpsd-select');
        if (!selector.is(e.target) && selector.has(e.target).length === 0) {
            selector.find('ul').slideUp();
        }
    });



    $('.wpsd-select-dropdown').each(function(event) {
        $(this).hide();
        var $self = $(this);
        var spselect  = '<div class="wpsd-select">';
        spselect += '<div class="wpsd-select-result">';
        spselect += '<span class="wpsd-select-text">' + $self.find('option:selected').text() + '</span>';
        spselect += ' <i class="fa fa-sort"></i>';
        spselect += '</div>';
        spselect += '<ul class="wpsd-select-dropdown">';

        $self.children().each(function(event) {
            if($self.val() == $(this).val()) {
                spselect += '<li class="active" data-val="'+ $(this).val() +'">' + $(this).text() + '</li>';
            } else {
                spselect += '<li data-val="'+ $(this).val() +'">' + $(this).text() + '</li>';
            }
        });

        spselect += '</ul>';
        spselect += '</div>';
        $(this).after($(spselect));
    });

    function wpsd_convart_select_menu_to_modern_select(){
        $('.wpsd-select-menu').each(function(event) {
            $(this).hide();
            var $self = $(this);
            var spselect  = '<div class="wpsd-select">';
            spselect += '<div class="wpsd-select-result">';
            spselect += '<span class="wpsd-select-text">' + $self.find('option:selected').text() + '</span>';
            spselect += ' <i class="fa fa-sort"></i>';
            spselect += '</div>';
            spselect += '<ul class="wpsd-select-dropdown">';

            $self.children().each(function(event) {
                if($self.val() == $(this).val()) {
                    spselect += '<li class="active" data-val="'+ $(this).val() +'">' + $(this).text() + '</li>';
                } else {
                    spselect += '<li data-val="'+ $(this).val() +'">' + $(this).text() + '</li>';
                }
            });

            spselect += '</ul>';
            spselect += '</div>';
            $(this).after($(spselect));
        });
    }
    wpsd_convart_select_menu_to_modern_select();

    $(document).on('click', '.wpsd-select', function(event) {
        $('.wpsd-select').not(this).find('ul').slideUp();
        $(this).find('ul').slideToggle();
    });

    $(document).on('click', '.wpsd-select ul li', function(event) {
        var $select = $(this).closest('.wpsd-select').prev('select');
        $(this).parent().prev('.wpsd-select-result').find('span').html($(this).text());
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
        $select.val($(this).data('val'));
        $select.change();
    });
// End Select


// Search Field
    $('.wpsd-search-button').on('click', function(){
        $('.wpsd-search').toggleClass('active');
        $('.wpsd-search input').focus();

    });
    $(document).on('click', function(e) {
        var selector = $('.wpsd-search');
        if (!selector.is(e.target) && selector.has(e.target).length === 0) {
            $('.wpsd-search').removeClass('active');
        }
    });
    //End Added


    /**
     * Create Ticket Form from Frontend page
     */
    $('body').on('submit','#wpsd-create-ticket-form',function(e){
        e.preventDefault();
        //var form_data = $(this).serialize()+'&action=wpsd_create_ticket';
        var form_data = new FormData(this);
        form_data.append('action', 'wpsd_create_ticket');
        form_data.append('creating_from', 'wpsd_frontend');

        $('#wpsd_ticket_submit_btn').text('Submitting...');
        $('#wpsd_ticket_submit_btn').attr('disabled', 'disabled');
        $.ajax({
            type : 'POST',
            url : wpsd_ajax_object.ajax_url,
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
                    $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_load_home_page' }, function( data ) {
                        $('.wpsd_content_wrap').html(data);
                    });
                }else {
                    $('#wpsd_ticket_submit_btn').text('Submit Ticket');
                    $('#wpsd_ticket_submit_btn').removeAttr('disabled');

                    $('.wpsd_response_msg').html("<p class='wpsd-alert-warning'>"+success_data.msg+" </p>");
                    $('html, body').animate({'scrollTop' : $(".wpsd_response_msg").position().top});
                }
            }
        })
    });

    $('body').on('submit','#wpsd_track_ticket_guest',function(e){
        e.preventDefault();
        var form_data = $(this).serialize()+'&action=wpsd_track_ticket_guest';
        $.ajax({
            type : 'POST',
            url : wpsd_ajax_object.ajax_url,
            data : form_data,
            success : function(data){
                var success_data = JSON.parse(data);
                var wpsd_ticket_id = success_data.ticket_id;

                if (success_data.success == 1){
                    $.post( wpsd_ajax_object.ajax_url, { ticket_id: wpsd_ticket_id, action : 'wpsd_ticket_view_frontend' }, function( data ) {
                        $('.wpsd_content_wrap').html(data);
                    });
                }
                
            }
        })
    });

/*
    $('body').on('click', '.wpsd_ajax_load_department', function(e){
        e.preventDefault();
        var wpsd_load_url = $(this).attr('href');
        $('.wpsd_content_wrap').load(wpsd_load_url);
    });
*/

    $('body').on('click', '.wpsd_ajax_load_page', function(e){
        e.preventDefault();
        var wpsd_load_url = $(this).attr('href');
        $('.wpsd_content_wrap').load(wpsd_load_url);
    });

    $('body').on('click', '.wpsd_ajax_ticket_view', function(e){
        e.preventDefault();
        var wpsd_ticket_id = $(this).data('ticket-id');
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, { ticket_id: wpsd_ticket_id, action : 'wpsd_ticket_view_frontend' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_ticket_replay_content"), showIcons: ["code", "table"] });
            $('div.wpsd-loading').hide();
        });
    });


    $('body').on('submit','#wpsd_post_ticket_reply_from_admin',function(e){
        e.preventDefault();
        //var wpsd_reply_form_field = $(this).serialize()+'&action=save_reply_from_frontend_ticket_form';
        var formData = new FormData($(this)[0]);
        formData.append('action', 'save_reply_from_frontend_ticket_form');

        $.ajax({
            type: 'POST',
            url: wpsd_ajax_object.ajax_url,
            data: formData,
            contentType: false,
            processData: false,
            success : function (data) {
                $('.wpsd_content_wrap').html(data);
                var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_ticket_replay_content"), showIcons: ["code", "table"] });
            }
        });
    });

    $('body').on('click', '.wpsd_create_new_ticket', function(e){
        e.preventDefault();
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, { action : 'wpsd_create_new_ticket' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $.validate();
            wpsd_convart_select_menu_to_modern_select();
            var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_description"), showIcons: ["code", "table"] });
            $('div.wpsd-loading').hide();

            if(typeof grecaptcha != 'undefined') {
                //Initiate reCaptcha
                var recaptcha_site_key = $('#wpsd-g-recaptcha').data('sitekey');
                var captchaWidgetId = grecaptcha.render('wpsd-g-recaptcha', {
                    'sitekey': recaptcha_site_key
                });
            }
        });
        //Re-initiate validator
    });

    $('body').on('click', '.wpsd_load_home', function(e){
        e.preventDefault();
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_load_home_page' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $.validate();
            $('div.wpsd-loading').hide();
        });
    });
    $(document).on('click', '.wpsd_load_waiting_for_reply', function(e){
        e.preventDefault();
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_load_waiting_for_reply' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $('div.wpsd-loading').hide();
        });
    });
    $(document).on('click', '.wpsd_load_in_discussion', function(e){
        e.preventDefault();
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_load_in_discussion' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $('div.wpsd-loading').hide();
        });
    });
    $(document).on('click', '.wpsd_load_in_completed_tickets', function(e){
        e.preventDefault();
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_load_in_completed_tickets' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $('div.wpsd-loading').hide();
        });
    });

    $('body').on('click', '.wpsd-pagination ul li a', function(e){
        e.preventDefault();
        var wpsd_load_url = $(this).attr('href');
        var current_page = parseInt($(this).attr('href').replace(wpsd_ajax_object.ajax_url+'?paged=', '') );
        if ( ! current_page){
            current_page = parseInt($(this).text());
        }
        $.post( wpsd_ajax_object.ajax_url, {current_page : current_page, action : 'wpsd_load_home_page' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
        });
    });

    $('body').on('click', '.wpsd_ticket_status_change_btn', function(e){
        e.preventDefault();
        var ticket_status = $('#wpsd_ticket_status_change').val();
        var ticket_id = $('[name="ticket_id"]').val();

        $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_change_ticket_status', ticket_id : ticket_id, wpsd_ticket_status : ticket_status  }, function( data ) {
            $('.wpsd_content_wrap').html(data);
        });
    });

    $('body').on('click', '.load-knowledgebase', function(e){
        e.preventDefault();
        $.post( wpsd_ajax_object.ajax_url, {action : 'wpsd_load_knowledgebase' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
        });
    });

    $('body').on('click', '.wpsd_load_kb_category', function(e){
        e.preventDefault();
        var kb_category_id = $(this).data('kb-category-id');
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, { kb_category_id: kb_category_id, action : 'wpsd_open_kb_category' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $('div.wpsd-loading').hide();
        });
    });

    $('body').on('click', '.wpsd-open-kb', function(e){
        e.preventDefault();
        var kb_category_id = $(this).data('kb-category-id');
        var kb_id = $(this).data('kb-id');
        $('div.wpsd-loading').show();
        $.post( wpsd_ajax_object.ajax_url, { kb_id: kb_id, kb_category_id: kb_category_id, action : 'wpsd_open_kb_article' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
            $('div.wpsd-loading').hide();
        });
    });

    //Search by clicking button
    $('body').on('click', '.wpsd_kb_search_btn', function(e){
        e.preventDefault();
        var wpsd_kb_search_term = $('#wpsd_kb_search').val();
        if (wpsd_kb_search_term.length > 0){
            $.post( wpsd_ajax_object.ajax_url, { wpsd_kb_search_term: wpsd_kb_search_term, action : 'wpsd_search_kb_article' }, function( data ) {
                $('.wpsd_content_wrap').html(data);
            });
        }
    });

    //Search by submitting form
    $('#wpsd_article_search_form').submit(function(e){
        e.preventDefault();
        var wpsd_kb_search_term = $('#wpsd_kb_search').val();
        if (wpsd_kb_search_term.length > 0){
            $('div.wpsd-loading').show();
            $.post( wpsd_ajax_object.ajax_url, { wpsd_kb_search_term: wpsd_kb_search_term, action : 'wpsd_search_kb_article' }, function( data ) {
                $('.wpsd_content_wrap').html(data);
                $('div.wpsd-loading').hide();
            });
        }
    });

    $(document).on('submit', '#wpsd-login',function(e){
        e.preventDefault();
        var formData = $(this).serialize()+'&action=wpsd_login_action';
        $.ajax({
            type: 'POST',
            url: wpsd_ajax_object.ajax_url,
            data: formData,
            success : function (data) {
                var success_data = JSON.parse(data);
                if (success_data.success == 1) {
                    window.location.reload(true);
                }else{
                    $('#wpsd_login_status_msg').html("<p class='wpsd-alert-warning'>"+success_data.msg+" </p>");
                }
            }
        }); 
    });

    $(document).on('click', '.wpsdBackToLoginFormLink',function(e) {
        $.post( wpsd_ajax_object.ajax_url, { action : 'wpsd_load_login_form' }, function( data ) {
            $('.wpsd-container').html(data);

            if(typeof grecaptcha != 'undefined') {
                //Initiate reCaptcha
                var recaptcha_site_key = $('#wpsd-g-recaptcha').data('sitekey');
                var captchaWidgetId = grecaptcha.render('wpsd-g-recaptcha', {
                    'sitekey': recaptcha_site_key
                });
            }
        });
    });
    $(document).on('click', '.wpsdCreateAccountLink',function(e) {
        $.post( wpsd_ajax_object.ajax_url, { action : 'wpsd_load_user_registration_form' }, function( data ) {
            $('.wpsd-container').html(data);

            if(typeof grecaptcha != 'undefined') {
                //Initiate reCaptcha
                var recaptcha_site_key = $('#wpsd-g-recaptcha').data('sitekey');
                var captchaWidgetId = grecaptcha.render('wpsd-g-recaptcha', {
                    'sitekey': recaptcha_site_key
                });
            }
        });
    });

    $(document).on('submit', '#wpsd-register',function(e) {
        e.preventDefault();
        var form_data = $(this).closest('form').serialize()+'&action=wpsd_user_register';
        $.post( wpsd_ajax_object.ajax_url, form_data, function( data ) {
            var success_data = JSON.parse(data);
            if (success_data.success == 1) {
                window.location.reload(true);
            }else{
                $('#wpsd_login_status_msg').html("<p class='wpsd-alert-warning'>"+success_data.msg+" </p>");
            }
        });
    });
    
    $(document).on('click', '#wpsd-ticket-checked-all', function(){
        $('.wpsd-tickets-checkbox').not(this).prop('checked', this.checked);
    });

    $(document).on('click', '.wpsd-ticket-batch-delete-btn', function(){
        var checkedValues = $('.wpsd-tickets-checkbox:checked').map(function() {
            return this.value;
        }).get();
        if (checkedValues.length == 0){
            return false;
        }
        $.post( wpsd_ajax_object.ajax_url, { checkedValues: checkedValues, action : 'wpsd_batch_delete_ticket' }, function( data ) {
            $('.wpsd_content_wrap').html(data);
        });
    });

    $(document).on('change', '#wpsd-author-menu', function(){
        if ($(this).val() == 'logout'){
            $.post( wpsd_ajax_object.ajax_url, { action : 'wpsd_logout' }, function( data ) {
                window.location.reload(true);
            });
        }
    });

    $(document).on('click', '.wpsd-nav ul li a', function(){
        $('.wpsd-nav ul li').removeClass('active');
        $(this).closest('li').addClass('active');
    });

    $(document).on('click', 'a.wpsd-vote-btn', function(){
        var article_id = $(this).data('article-id');
        var vote_value = $(this).data('value');

        $.post( wpsd_ajax_object.ajax_url, { action : 'wpsd_article_vote', article_id:article_id, vote_value:vote_value }, function( data ) {
            var success_data = JSON.parse(data);
            $('.wpsd-kb-vote-btn-wrap').html(success_data.msg);
        });
    });

    $(document).on('blur change paste keyup ','#wpsd_subject', function (e) {
        var subject_term = $(this).val();

        if (subject_term.length >= 3){
            $.ajax({
                type: 'POST',
                url: wpsd_ajax_object.ajax_url,
                data: {subject_term:subject_term, action: 'wpsd_suggestions_kb'},
                success : function (data) {
                    $('#wpsd-kb-suggestion').html(data);
                }
            });
        }else {
            $('#wpsd-kb-suggestion').html('');
        }
    });

    /**
     * Initiate MarkDown Editor
     */
    var simplemde = new SimpleMDE({ element: document.getElementById("wpsd_ticket_replay_content"), showIcons: ["code", "table"] });
    $('div.wpsd-loading').hide();

});
