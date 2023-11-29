"use strict";

$(document).ready(function() {

    /**--------------------------------------------------------------------------------------
     * [SESSION MESSAGES]
     * @blade : layout/automationjs.blade.php
     * @description: show session set noty messages
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-session-message").length) {
        var session_message = $("#js-trigger-session-message").attr('data-message');
        var message_type = $("#js-trigger-session-message").attr('data-type');
        NX.notification({
            'type': message_type,
            'duration': 7000,
            'message': session_message,
        });
    }

    /**--------------------------------------------------------------------------------------
     * [FORCE PASSWORD CHANGE]
     * @blade : layout/automationjs.blade.php
     * @description: force password change for new users
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-force-password-change").length) {
        //close any open modals
        $('.modal').modal('hide');
        //show password reset popup
        $("#js-trigger-force-password-change").trigger('click');
    }

    /**--------------------------------------------------------------------------------------
     * [ADMIN UPDATES - RUN ONCE]
     * @blade : layout/automationjs.blade.php
     * @description: force password change for new users
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-update-action").length) {
        //close any open modals
        $('.modal').modal('hide');
        //show password reset popup
        $("#js-trigger-update-action").trigger('click');
    }



    /**--------------------------------------------------------------------------------------
     * [LOAD DYNAMIC CONTENT]
     * @blade : layout/automationjs.blade.php
     * @description: force password change for new users
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-dynamic-modal").length) {
        var trigger_id = $("#js-trigger-dynamic-modal").attr('data-payload');
        //show password reset popup
        $(trigger_id).trigger('click');
    }



    /**--------------------------------------------------------------------------------------
     * [POLLING - GENERAL]
     * @blade : layout/foo.blade.php
     * @description: polling timers
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-general-polling").length) {
        function nxTimerPolling() {
            nxAjaxUxRequest($("#js-trigger-general-polling"));
            setTimeout(nxTimerPolling, 15000);
        };
        nxTimerPolling();
    }



    /**--------------------------------------------------------------------------------------
     * [POLLING - TIMERS]
     * @blade : layout/foo.blade.php
     * @description: polling genereal
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-general-timers").length) {
        function nxGeneralPolling() {
            nxAjaxUxRequest($("#js-trigger-general-timers"));
            setTimeout(nxGeneralPolling, 15000);
        };
        nxGeneralPolling();
    }


    /**--------------------------------------------------------------------------------------
     * [POLLING - TOP NAV TIMER]
     * @blade : layout/foo.blade.php
     * @description: polling genereal
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-topnav-timer").length) {
        function nxGeneralPolling() {
            nxAjaxUxRequest($("#js-trigger-topnav-timer"));
            setTimeout(nxGeneralPolling, 15000);
        };
        nxGeneralPolling();
    }



    /**--------------------------------------------------------------------------------------
     * [SESSION MESSAGES]
     * @blade : layout/automationjs.blade.php
     * @description: show session set noty messages
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-session-message").length) {
        var session_message = $("#js-trigger-session-message").attr('data-message');
        var message_type = $("#js-trigger-session-message").attr('data-type');
        NX.notification({
            'type': message_type,
            'duration': 7000,
            'message': session_message,
        });
    }

    /** ---------------------------------------------------
     *  Project tabs
     *  - basic actions buttons
     *  - no auto close
     * -------------------------------------------------- */
    $(document).find(".js-card-settings-button-static").each(function() {
        $(this).popover({
            html: true,
            sanitize: false, //The HTML is NOT user generated
            placement: 'bottom',
            offset: '-65,0',
            template: NX.basic_popover_template,
            title: $(this).attr('data-title'),
            content: function() {
                //popover elemenet
                return $('#' + $(this).attr('data-popover-content')).html();
            }
        });
    });

    $('html').click(function(e) {
        if (!$(e.target).hasClass('js-card-settings-button-static') && !$(e.target).parents().hasClass('popover') && !$(e.target).hasClass('mdi')) {
            $('.js-card-settings-button-static').popover('hide');
        }
    });

    /** -------------------------------------------
     * PROJECT ACTIONS
     *--------------------------------------------*/
    if ($("#project_details").length) {
        /**-------------------------------------------------------------
         * UPDATING ASSIGNED USERS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-projects-update-assigned').on('click', '#card-projects-update-assigned', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //add loading class
            $("#project-assigned-container").html('');
            $("#project-assigned-container").addClass('loading-placeholder');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });
    }

});




/**--------------------------------------------------------------------------------------
 * [MAIN MENU SCROLL BAR]
 * @description: show scroll bar
 * -------------------------------------------------------------------------------------*/
function nxSettingsLeftMenuScroll() {
    const navLeftScroll = new PerfectScrollbar('#settings-scroll-sidebar', {
        wheelSpeed: 2,
        wheelPropagation: true,
        minScrollbarLength: 20
    });
}
$(document).ready(function() {
    if ($("#settings-scroll-sidebar").length) {
        //add special class to menu items that have submenu
        $(".sidenav-menu-item").has("ul").addClass('has-submenu');

        //special sub menu highlighting for ajax link
        $(".js-submenu-ajax").on('click', function() {
            $(".js-submenu-ajax").removeClass('active');
            $("a.active").removeClass('active');
            $(this).addClass('active');
        });
        //autoscroll menu
        nxSettingsLeftMenuScroll();
    }
});


/**--------------------------------------------------------------------------------------
 * [CATEGORIES]
 * @page : settings/categories
 * @description: javascript for the categories section (in settings)
 * -------------------------------------------------------------------------------------*/
function NXBootCategories() {

    if ($("#categories-table-wrapper").length) {

        //variables
        var page_section = $("#categories-table-wrapper").attr('data-payload');


        //activate left menu, specifically for ticket departments
        if (page_section == 'ticket') {
            var current_state = $("#settings-menu-tickets").attr('aria-expanded');
            if (current_state == 'false') {
                $("#settings-menu-tickets").trigger('click');
                $("#settings-menu-tickets-departments").addClass('active');
            }
            //exit
            return;
        }
    }

    //activate left menu item
    var current_state = $("#settings-menu-categories").attr('aria-expanded');
    if (current_state == 'false') {
        $("#settings-menu-categories").trigger('click');
        $("#settings-menu-categories-" + page_section).addClass('active');
    }


    //[lead]
    if (page_section == 'lead') {
        if ($("#settings-menu-leads").attr('aria-expanded') == 'false') {
            $("#settings-menu-leads").trigger('click');
            $("#settings-menu-categories-lead").addClass('active');
        }
    }

    //[inventory]
    if (page_section == 'inventory') {
        if ($("#settings-menu-inventory").attr('aria-expanded') == 'false') {
            $("#settings-menu-inventory").trigger('click');
            $("#settings-menu-categories-inventory").addClass('active');
        }
    }

}



/**--------------------------------------------------------------------------------------
 * [CATEGORIES - CREATE AND EDIT]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXCategoriesCreate() {
    //add category - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            category_name: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}

/**--------------------------------------------------------------------------------------
 * [USER PROFILE - UPLOAD DUCUMENT]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXProfileDocument() {


    //file upload
    $("#fileupload_user_document").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        // acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
    //add category - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            document_title: "document_title",
            document_type: "document_type",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#uploadDocumentButton"));
        }
    });

}

/**--------------------------------------------------------------------------------------
 * [PROJECT - MAKE TEMPLATE]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXMakeTemplate() {

    //add template - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            template_title: "template_title",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}

/**--------------------------------------------------------------------------------------
 * [LEAVES - CREATE AND EDIT]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXApplyUserLeaveClaimMC() {


    //file upload
    $("#fileupload_single_image").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 10,
        maxFilesize: 5, // MB
        // acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
    //add category - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            date_start: "required",
            date_end: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}

/**--------------------------------------------------------------------------------------
 * [LEAVES - CREATE AND EDIT]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXCreateUpdatePayslip() {

    //file upload
    $("#fileupload_single_image").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
    //add category - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}

/**--------------------------------------------------------------------------------------
 * [CARDS - BOOT JAVASCRIPT FOR ALL ACTIONS ON THE CARD
 * @blade : task\modal.blade.php
 * @description: all he jsactionson cards
 * -------------------------------------------------------------------------------------*/
function NXBootCards() {

    //comments
    if ($("#cardModal").length) {

        //focus on the editor
        $(document).on('click', '#card-coment-placeholder-input', function() {
            tinymce.get('card-comment-tinmyce').setContent('');
            $("#card-coment-placeholder-input-container").hide();
            $("#card-comment-tinmyce-container").show();
            tinymce.execCommand('mceFocus', true, 'card-comment-tinmyce');
        });

        //close editor
        $(document).on('click', '#card-comment-close-button', function() {
            $("#card-comment-tinmyce-container").hide();
            $("#card-coment-placeholder-input-container").show();
        });
        //post comment
        $(document).off('click', '#card-comment-post-button').on('click', '#card-comment-post-button', function(e) {
            $("#card-comment-tinmyce-container").hide();
            $("#card-coment-placeholder-input-container").show();
            nxAjaxUxRequest($(this));
        });
    }

    //EDIT DESCRIPTION
    if ($("#cardModal").length) {
        NX.card_description = $("#card-description-container");
        NX.card_descrition_selector = '#card-description-container';
        NX.card_description_submit = $("#card-description-submit");
        NX.card_description_edit = $("#card-description-edit");
        NX.card_description_input = $("#card-description-input");

        //edit button clicked
        $(document).on('click', '#card-description-button-edit', function() {
            tinymce.remove("#card-description-container");
            NX.card_description_original_text = NX.card_description.html();
            NX.card_description_height = NX.card_description.outerHeight();
            NX.card_description.addClass('card-tinymce-textarea');
            NX.card_description_edit.hide();
            NX.card_description_submit.show();
            nxTinyMCEBasic(NX.card_description_height, NX.card_descrition_selector);
        });
        //cancel button clicked
        $(document).on('click', '#card-description-button-cancel', function() {
            NX.card_description.removeClass('card-tinymce-textarea');
            NX.card_description_submit.hide();
            NX.card_description_edit.show();
            tinymce.remove("#card-description-container");
            NX.card_description.html(NX.card_description_original_text);
        });
        $(document).off('click', '.js-description-save').on('click', '.js-description-save', function() {
            NX.card_description_input.val(NX.card_description.html());
            NX.card_description.removeClass('card-tinymce-textarea');
            NX.card_description_submit.hide();
            NX.card_description_edit.show();
            tinymce.remove("#card-description-container");
            $("#card-description-container").html('<div class="loading w-px-150 h-px-30"></div>');
            nxAjaxUxRequest($(this));
        });
    }

    //CARD TITLE
    if ($("#cardModal").length) {
        NX.card_title_edit_original = '';
        //start
        $(document).on('click', '#card-title-editable', function() {
            NX.card_title_edit_original = $(this).html();
            $(this).hide();
            $(".card-title-input").val(NX.card_title_edit_original);
            $("#card-title-edit").show();
        });
        //cancel
        $(document).on('click', '#card-title-button-cancel', function() {
            $("#card-title-editable").html(NX.card_title_edit_original);
            $("#card-title-edit").hide();
            $("#card-title-editable").show();
        });
        //show loading annimation
        $(document).on('click', '#card-title-button-save', function() {
            $("#card-title-editable").html('<div class="loading w-px-150 h-px-30"></div>');
            $("#card-title-edit").hide();
            $("#card-title-editable").show();
            nxAjaxUxRequest($(this));
        });
    }


    /** -------------------------------------------
     * COMMENTS TEXT EDITOR
     *--------------------------------------------*/
    if ($("#cardModal").length) {

        tinymce.execCommand('mceRemoveEditor', true, 'card-comment-tinmyce');
        //initialize
        tinymce.init({
            selector: '#card-comment-tinmyce',
            mode: 'exact',
            theme: "modern",
            skin: 'light',
            branding: false,
            menubar: false,
            statusbar: false,
            plugins: [
                "advlist autolink lists link image preview",
                "paste autoresize"
            ],
            height: 200,
            toolbar: 'bold link bullist numlist blockquote',
            //autosave/update text area
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    }

    /** -------------------------------------------
     * OTHER ACTIONS
     *--------------------------------------------*/
    if ($("#cardModal").length) {
        //default date pickers
        $(document).find(".card-pickadate").datepicker({
            format: NX.date_picker_format,
            language: "lang",
            autoclose: true,
            class: "datepicker-default",
            todayHighlight: false
        });

        //reset date
        $('.card-pickadate').datepicker('clearDates');
        //ajax request
        $('.card-pickadate').on('changeDate', function(e) {
            //update form date
            $("#" + $(this).attr('data-hidden-field')).val(moment(e.date).format('YYYY-MM-DD'));
            //update value
            $("#" + $(this).attr('data-container')).html(moment(e.date).format(NX.date_moment_format));
            //send request
            nxAjaxUxRequest($(this));
        });


        /** -------------------------------------------------------------------------------
         *  Something in the left panel or datepicker or other popover has been clicked
         *  - close any static popover windows
         * ------------------------------------------------------------------------------- */
        $(document).on('click', '#card-left-panel, .js-card-settings-button, .card-pickadate', function() {
            $('.js-card-settings-button-static').popover('hide');
        });

        $(document).on('click', '#card-leads-left-panel, .js-card-settings-button, .card-pickadate', function() {
            $('.js-card-settings-button-static').popover('hide');
        });

        /** ---------------------------------------------------
         *  Right panel settings buttons
         *  - basic actions buttons
         *  - auto close
         * -------------------------------------------------- */
        $(document).find(".js-card-settings-button").each(function() {
            $(this).popover({
                html: true,
                sanitize: false, //The HTML is NOT user generated
                placement: 'bottom',
                offset: '-65,0',
                trigger: 'focus',
                template: NX.basic_popover_template,
                title: $(this).attr('data-title'),
                content: function() {
                    //popover elemenet
                    return $('#' + $(this).attr('data-popover-content')).html();
                }
            });
        });

        /** ---------------------------------------------------
         *  Right panel settings buttons
         *  - basic actions buttons
         *  - no auto close
         * -------------------------------------------------- */
        $(document).find(".js-card-settings-button-static").each(function() {
            $(this).popover({
                html: true,
                sanitize: false, //The HTML is NOT user generated
                placement: 'bottom',
                offset: '-65,0',
                template: NX.basic_popover_template,
                title: $(this).attr('data-title'),
                content: function() {
                    //popover elemenet
                    return $('#' + $(this).attr('data-popover-content')).html();
                }
            });
        });


        /** ------------------------------------------------------------------------------
         *  - close any other static popover windows
         * ------------------------------------------------------------------------------- */
        $(document).on('click', '.js-card-settings-button-static', function() {
            $('.js-card-settings-button-static').not(this).popover('hide');
        });



        /**-------------------------------------------------------------
         * EDITING TASK STATUS
         * ------------------------------------------------------------*/
        $(document).off('click', '.card-tasks-update-status-link').on('click', '.card-tasks-update-status-link', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //cart display element
            var $card_display_element = $("#card-task-status-text");
            //set current value text
            $(".popover-body").find("#current_task_status_text").val($card_display_element.html());
            //add loading
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //set hidden form
            $(this).closest('.popover').find("#task_status").val($(this).attr('data-value'));
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING TASK PRIORITy
         * ------------------------------------------------------------*/
        $(document).off('click', '.card-tasks-update-priority-link').on('click', '.card-tasks-update-priority-link', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //cart display element
            var $card_display_element = $("#card-task-priority-text");
            //set current value text
            $(".popover-body").find("#current_task_priority_text").val($card_display_element.html());
            //add loading
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //set hidden form
            $(this).closest('.popover').find("#task_priority").val($(this).attr('data-value'));
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING TASK VISIBILITY
         * ------------------------------------------------------------*/
        $(document).off('click', '.card-tasks-update-visibility-link').on('click', '.card-tasks-update-visibility-link', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //cart display element
            var $card_display_element = $("#card-task-client-visibility-text");
            //set current value text
            $(".popover-body").find("#current_task_client_visibility_text").val($card_display_element.html());
            //add loading
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //set hidden form
            $(this).closest('.popover').find("#task_client_visibility").val($(this).attr('data-value'));
            $(this).closest('.popover').find("#current_task_client_visibility_text").val($(this).attr('data-text'));
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING TASK MILESTONE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-tasks-update-milestone-button').on('click', '#card-tasks-update-milestone-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //get selected text
            var $select = $(".popover-body").find("#task_milestoneid");
            var selected_text = $select.find('option:selected').text();
            $("#card-task-milestone-title").html(selected_text);
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD NAME
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-name').on('click', '#card-lead-name', function() {
            //update names in the form
            $(".popover-body").find("#lead_firstname").val($(document).find("#card-lead-firstname-containter").html());
            $(".popover-body").find("#lead_lastname").val($(document).find("#card-lead-lastname-containter").html());
        });
        $(document).off('click', '#card-leads-update-name-button').on('click', '#card-leads-update-name-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset current name
            $("#card-lead-firstname-containter").html('');
            $("#card-lead-lastname-containter").html('');
            //add loading
            $("#card-lead-element-container-name").addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });

        /**-------------------------------------------------------------
         * EDITING LEAD VALUE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-value').on('click', '#card-lead-value', function() {
            $(".popover-body").find("#lead_value").val($(this).attr('data-value'));
        });
        $(document).off('click', '#card-leads-update-value-button').on('click', '#card-leads-update-value-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-value");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });



        /**-------------------------------------------------------------
         * EDITING LEAD STATUS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-status-button').on('click', '#card-leads-update-status-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-status-text");
            //current text value
            $(".popover-body").find("#current_lead_status_text").val($card_display_element.html());
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');

            if (typeof $(this).attr('data-form-id') != 'undefined' && $(this).attr('data-form-id') != '') {
                var form = $("#" + $(this).attr('data-form-id'));
            } else {
                //assume the parent form of this button
                var form = $(this).parents('form:first');
            }
            var sl = form.find("#lead_status");
            var convert_to_project = $(this).attr('data-porject-convert');
            var convert_to_project_status_id = $(this).attr('data-convert-project-status-id');
            var status_id = sl.val();

            if (convert_to_project == 'enable' && convert_to_project_status_id == status_id) {
                //

                // $(this).attr('data-toggle', "modal");
                // $(this).attr({
                //     'data-target': "#commonModal",
                //     'data-loading-target': "commonModalBody",
                //     'data-action-url': $(this).attr('data-url'),
                //     'data-action-ajax-loading-target': "commonModalBody",
                // });
                // $("#commonModal").modal('show');
                var this_lead_id = $(this).attr('data-lead');
                var container = document.getElementById("convert-to-project");
                NXCreateElement({
                    tag: 'button',
                    type: 'button',
                    id: 'card-ststus-data-button-' + this_lead_id,
                    'data-url': $(this).attr('data-url') + '?lead_status=' + status_id + '&current_lead_status_text=' + form.find("#current_lead_status_text").val(),
                    'data-ajax-type': "POST",
                    class: ['hidden', 'edit-add-modal-button', 'js-ajax-ux-request', 'reset-target-modal-form'],
                    'data-toggle': "modal",
                    'data-target': "#commonModal",
                    'data-loading-target': "commonModalBody",
                    'data-modal-title': $(this).attr('data-modal-title'),
                    'data-action-url': $(this).attr('data-url'),
                    'data-action-method': "POST",
                    'data-action-ajax-loading-target': "commonModalBody",
                }, container);

                var button = document.getElementById("card-ststus-data-button-" + this_lead_id);
                NXSimulate(button, "click");
            } else {
                //send request
                nxAjaxUxRequest($(this));
            }

        });


        /**-------------------------------------------------------------
         * EDITING LEAD CATEGORY
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-category-button').on('click', '#card-leads-update-category-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-category-text");
            //current text value
            $(".popover-body").find("#current_lead_category_text").val($card_display_element.html());
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD PHONE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-phone').on('click', '#card-lead-phone', function() {
            var current_value = ($(this).html() == '---') ? '' : $(this).html();
            $(".popover-body").find("#lead_phone").val(current_value);
        });
        $(document).off('click', '#card-leads-update-phone-button').on('click', '#card-leads-update-phone-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-phone");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD EMAIL
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-email').on('click', '#card-lead-email', function() {
            var current_value = ($(this).html() == '---') ? '' : $(this).html();
            $(".popover-body").find("#lead_email").val(current_value);
        });
        $(document).off('click', '#card-leads-update-email-button').on('click', '#card-leads-update-email-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-email");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });



        /**-------------------------------------------------------------
         * EDITING LEAD SOURCE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-source-button').on('click', '#card-leads-update-source-button', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-source-text");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });



        /**-------------------------------------------------------------
         * UPDATING ASSIGNED USERS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-tasks-update-assigned').on('click', '#card-tasks-update-assigned', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //add loading class
            $("#task-assigned-container").html('');
            $("#task-assigned-container").addClass('loading-placeholder');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });

        /**-------------------------------------------------------------
         * UPDATING ASSIGNED USERS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-assigned').on('click', '#card-leads-update-assigned', function() {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //add loading class
            $("#lead-assigned-container").html('');
            $("#lead-assigned-container").addClass('loading-placeholder');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });

    }
    /**-------------------------------------------------------------
     * FILE UPLOAD TOGGLE
     * ------------------------------------------------------------*/
    $(document).off('click', '#js-card-toggle-fileupload').on('click', '#js-card-toggle-fileupload', function() {
        $(document).find("#card-fileupload-container").toggle();
    });




    /**-------------------------------------------------------------
     * FILE UPLOAD
     * ------------------------------------------------------------*/
    if ($("#card-attachments").length) {
        //get the url
        var upload_url = $("#card-attachments").attr('data-upload-url');
        //attache the files
        if ($("#card_fileupload").length) {
            $("#card_fileupload").dropzone({
                url: upload_url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                init: function() {
                    this.on("error", function(file, message, xhr) {
                        //is there a message from backend [abort() response]
                        if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                            var error = $.parseJSON(xhr.response);
                            if (typeof error === 'object' && typeof error.notification != 'undefined') {
                                var message = error.notification.value;
                            } else {
                                var message = NXLANG.generic_error;
                            }
                        }

                        //any other message
                        var message = (typeof message == 'undefined' || message == '' ||
                            typeof message == 'object') ? NXLANG.generic_error : message;

                        //error message
                        NX.notification({
                            type: 'error',
                            message: message
                        });
                        //remove the file
                        this.removeFile(file);
                    });
                },
                success: function(file, response) {
                    $("#card-fileupload-container").hide();
                    //get the priview box dom elemen
                    var $preview = $(file.previewElement);
                    //add to the list
                    $("#card-attachments-container").prepend(response.attachment);
                    //remove the file
                    this.removeFile(file);
                }
            });
        }
    }


    /**-------------------------------------------------------------
     * POLL TASK TIMER
     * ------------------------------------------------------------*/
    if ($("#cardModal").length) {
        function nxTimerTaskPolling() {
            if ($("#timerTaskPollingTrigger").length) {
                nxAjaxUxRequest($("#timerTaskPollingTrigger"));
            }
            setTimeout(nxTimerTaskPolling, 45000);
        };
        nxTimerTaskPolling();
    }
};


/**--------------------------------------------------------------------------------------
 * [AUTHENTICATON]
 * @blade : task\modal.blade.php
 * @description: all login, signup, forgot password js
 * -------------------------------------------------------------------------------------*/
function NXAuthentication() {
    /*----------------------------------------------------------------
     * login  - form validation
     *--------------------------------------------------------------*/
    if ($("#reloginForm").length) {
        $("#reloginForm").validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                nxAjaxUxRequest($("#reloginModalButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * signup - form validation
     *--------------------------------------------------------------*/
    if ($("#signUpForm").length) {
        $("#signUpForm").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                client_company_name: "required",
                email: "required",
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            submitHandler: function(form) {
                nxAjaxUxRequest($("#signupButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * reset password - form validation
     *---------------------------------------------------------------*/
    if ($("#resetPasswordForm").length) {
        $("#resetPasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            submitHandler: function(form) {
                nxAjaxUxRequest($("#resetPasswordSubminButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * login - form validation
     *--------------------------------------------------------------*/
    if ($("#loginForm").length) {
        $("#loginForm").validate({
            rules: {
                email: "required",
                password: "required",
            },
            submitHandler: function(form) {
                nxAjaxUxRequest($("#loginSubmitButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * forgot password -  form validation
     *---------------------------------------------------------------*/
    if ($("#forgotPasswordForm").length) {
        $("#forgotPasswordForm").validate({
            rules: {
                email: "required",
            },
            submitHandler: function(form) {
                nxAjaxUxRequest($("#forgotSubmitButton"));
            }
        });
    }

    /*----------------------------------------------------------------
     * relogin - form validation
     *--------------------------------------------------------------*/
    if ($("#reloginForm").length) {
        $("#reloginForm").validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                nxAjaxUxRequest($("#reloginModalButton"));
            }
        });
    }
};
NXAuthentication();


/**--------------------------------------------------------------------------------------
 * [COMMENTS]
 * @blade : comments\wrapper.blade.php
 * @description: show tinymce comment box
 * -------------------------------------------------------------------------------------*/
function NXPostGeneralComment() {

    //variable
    var unique_comment_id = $("#js-trigger-comments").attr('data-payload');

    //remove existing
    tinymce.remove('#editor-' + unique_comment_id);
    //initialize
    tinymce.init({
        selector: '#editor-' + unique_comment_id,
        mode: 'exact',
        theme: "modern",
        skin: 'light',
        branding: false,
        menubar: false,
        statusbar: false,
        plugins: [
            "advlist autolink lists link image preview code",
            "table paste autoresize"
        ],
        height: 200,
        toolbar: 'undo redo bold image link bullist numlist blockquote code',
        //autosave/update text area
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
    //focus on the editor
    $(document).on('click', '#placeholder-container-' + unique_comment_id, function() {
        tinymce.get('editor-' + unique_comment_id).setContent('');
        tinymce.execCommand('mceFocus', true, 'editor-' + unique_comment_id);
    });
}


/**--------------------------------------------------------------------------------------
 * [COMMENTS]
 * @blade : contacts\mdals\add-edit.blade.php
 * @description: validation for the add contact form
 * -------------------------------------------------------------------------------------*/
function NXContacts() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            clientid: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [ESTIMATE]
 * @blade : estimates\components\modals\add-edit-inc.blade.php
 * @description: validation for add/edit estimates form
 * -------------------------------------------------------------------------------------*/
function NXEstimates() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_date_add_edit: "required",
            bill_clientid: "required",
            bill_status: "required",
            bill_categoryid: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [EXPENSES]
 * @blade : expenses\components\modals\add-edit-inc.blade.php
 * @description: validation for add/edit expenses form
 * -------------------------------------------------------------------------------------*/
function NXEstimates() {
    //variable
    var expense_modal_trigger_clients_project_list = $("#js-trigger-expenses").attr('data-payload');
    var expense_client_id = $("#js-trigger-expenses").attr('data-client-id');


    //file upload
    $("#fileupload_expense_receipt").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });


    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            expense_description: "required",
            expense_date: "required",
            expense_amount: "required",
            expense_categoryid: "required"
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });



    //create expense
    if (expense_modal_trigger_clients_project_list == 'show') {
        //client select element
        var $dropdown = $("#expense_clientid");

        //selected client
        var client_id = expense_client_id;

        // Construct my data to select
        var data = {
            "id": client_id
        };

        // Set the value
        $dropdown.val(client_id);

        // Change the select2 control to update it visually
        $dropdown.trigger("change");

        // Manually fire the event with my data
        $dropdown.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    }
}

/**--------------------------------------------------------------------------------------
 * [CLIENT - UPLOAD LOGO]
 * @blade : clients\components\modals\update-logo.blade.php
 * @description: logo uploading inside client dashboard
 * -------------------------------------------------------------------------------------*/
function NXEmployeeUploadAvatar() {

    if ($("#js-trigger-clients-modal-upload-logo").length) {

        var client_id = $("#js-trigger-clients-modal-upload-logo").attr('data-payload');

        //upload client logo
        $("#fileupload_single_image").dropzone({
            url: NX.base_url + "/avatarupload",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            maxFiles: 1,
            maxFilesize: 2, // MB
            acceptedFiles: 'image/jpeg, image/png',
            thumbnailWidth: null,
            thumbnailHeight: null,
            init: function() {
                this.on("error", function(file, message, xhr) {

                    //is there a message from backend [abort() response]
                    if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                        var error = $.parseJSON(xhr.response);
                        var message = error.notification.value;
                    }

                    //any other message
                    var message = (typeof message == 'undefined' || message == '' ||
                        typeof message == 'object') ? NXLANG.generic_error : message;

                    //error message
                    NX.notification({
                        type: 'error',
                        message: message
                    });
                    //remove the file
                    this.removeFile(file);
                });
            },
            success: function(file, response) {
                //get the priview box dom elemen
                var $preview = $(file.previewElement);
                //create a hidden form field for this file
                $preview.append('<input type="hidden" name="avatar_filename"  value="' + response
                    .filename + '">');
                $preview.append('<input type="hidden" name="avatar_directory"  value="' + response
                    .uniqueid + '">');
                $preview.append('<input type="hidden" name="user_id"  value="' + client_id +
                    '">');
            }
        });

        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {},
            submitHandler: function(form) {
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}


/**--------------------------------------------------------------------------------------
 * [FILES]
 * @blade : files\components\modals\add-edit-inc.blade.php
 * @description: validation for add/edit expenses form
 * -------------------------------------------------------------------------------------*/
function NXFiles() {

    //uplaod files
    $("#fileupload_files").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    if (typeof error === 'object' && typeof error.notification != 'undefined') {
                        var message = error.notification.value;
                    } else {
                        var message = NXLANG.generic_error;
                    }
                }

                //system generated errors (e.g. apache)
                if (typeof xhr != 'undefined' && typeof xhr.statusText != 'undefined') {
                    //file too large (php.ini settings)
                    if (xhr.statusText == 'Payload Too Large') {
                        var message = NXLANG.file_too_big;
                    }
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });



    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [HOME PAGE - ADMIN]
 * @blade : home\admin\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeAdmin() {

    //projects list scroll
    if ($("#dashboard-admin-events").length) {
        const ps = new PerfectScrollbar('#dashboard-admin-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }

    //leads chart
    if ($("#leadsWidget").length) {
        var chart = c3.generate({
            bindto: '#leadsWidget',
            data: {
                columns: NX.admin_home_c3_leads_data,
                type: 'donut',
                onclick: function(d, i) {},
                onmouseover: function(d, i) {},
                onmouseout: function(d, i) {}
            },
            donut: {
                label: {
                    show: false
                },
                title: NX.admin_home_c3_leads_title,
                width: 20,

            },

            legend: {
                hide: true
            },
            color: {
                pattern: NX.admin_home_c3_leads_colors
            }
        });
    }



    //income vs expenses
    if ($("#admin-dhasboard-income-vs-expenses").length) {
        var chart = new Chartist.Line('.incomeexpenses', {
            labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            series: [
                NX.admin_home_chart_income,
                NX.admin_home_chart_expenses
            ]
        }, {
            lineSmooth: Chartist.Interpolation.simple({
                divisor: 2
            }),
            showArea: true,
            low: 0,
            fullWidth: true,
            plugins: [
                Chartist.plugins.tooltip()
            ],

        });

        chart.on('draw', function(ctx) {
            if (ctx.type === 'area') {
                ctx.element.attr({
                    x1: ctx.x1 + 0.001
                });
            }
        });
        chart.on('created', function(ctx) {
            var defs = ctx.svg.elem('defs');
            defs.elem('linearGradient', {
                id: 'gradient',
                x1: 0,
                y1: 1,
                x2: 0,
                y2: 0
            }).elem('stop', {
                offset: 0,
                'stop-color': 'rgba(255, 255, 255, 1)'
            }).parent().elem('stop', {
                offset: 1,
                'stop-color': 'rgba(36, 210, 181, 1)'
            });
        });
        var chart = [chart];
    }
}
if ($("#js-trigger-home-admin-wrapper").length) {
    NXHomeAdmin();
}


/**--------------------------------------------------------------------------------------
 * [HOME PAGE - TEAM]
 * @blade : home\team\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeTeam() {
    //perfect scroll
    if ($("#dashboard-client-projects").length) {
        const ps2 = new PerfectScrollbar('#dashboard-client-projects', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }


    //perfect scroll
    if ($("#dashboard-client-events").length) {
        const ps = new PerfectScrollbar('#dashboard-client-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}
if ($("#js-trigger-home-team-wrapper").length) {
    NXHomeTeam();
}


/**--------------------------------------------------------------------------------------
 * [HOME PAGE - CLIENT]
 * @blade : home\team\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeTeam() {
    //perfect scroll
    if ($("#dashboard-client-projects").length) {
        const ps2 = new PerfectScrollbar('#dashboard-client-projects', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }


    //perfect scroll
    if ($("#dashboard-client-events").length) {
        const ps = new PerfectScrollbar('#dashboard-client-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}
if ($("#js-trigger-home-team-wrapper").length) {
    NXHomeTeam();
}



/**--------------------------------------------------------------------------------------
 * [HOME PAGE - CLIENT]
 * @blade : home\client\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeClient() {
    //perfect scroll
    if ($("#dashboard-client-projects").length) {
        const ps2 = new PerfectScrollbar('#dashboard-client-projects', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }


    //perfect scroll
    if ($("#dashboard-client-events").length) {
        const ps = new PerfectScrollbar('#dashboard-client-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}
if ($("#js-trigger-home-team-wrapper").length) {
    NXHomeClient();
}



/**--------------------------------------------------------------------------------------
 * [INVOICE - CLONE]
 * @blade : invoices\components\modals\clone.blade.php
 * @description: validation for cloning an invoice
 * -------------------------------------------------------------------------------------*/
function NXInvoiceClone() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_clientid: "required",
            bill_due_date: "required",
            bill_categoryid: "required",
            bill_date: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [ESTIMATE - CLONE]
 * @blade : estimates\components\modals\clone.blade.php
 * @description: validation for cloning an invoice
 * -------------------------------------------------------------------------------------*/
function NXEstimateClone() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_clientid: "required",
            bill_categoryid: "required",
            bill_date: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [INVOICE - RECURRING]
 * @blade : invoices\components\modals\recurring-settings.blade.php
 * @description: validation for recurring an invoice
 * -------------------------------------------------------------------------------------*/
function NXInvoiceRecurring() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_recurring_next: "required",
            bill_recurring_duration: "required",
            bill_recurring_period: "required",
            bill_recurring_cycles: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [INVOICE - ADD]
 * @blade : invoices\components\modals\add-edit-inc.blade.php
 * @description: validation for creating an invoice
 * -------------------------------------------------------------------------------------*/
function NXInvoiceCreate() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_due_date_add_edit: "required",
            bill_categoryid: "required",
            bill_date_add_edit: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [ITEMS - ADD - EDIT]
 * @blade : items\components\modals\add-edit-inc.blade.php
 * @description: validation for creating a product
 * -------------------------------------------------------------------------------------*/
function NXItemCreate() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            item_description: "required",
            item_categoryid: "required",
            item_unit: "required",
            item_rate: "required"
        },
        submitHandler: function(form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [KB ARTICLE - CREATE]
 * @description: validation for creating an article
 * -------------------------------------------------------------------------------------*/
function NXArticleCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            knowledgebase_title: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [LEADS - CONVERT]
 * @description: validation for converting a lead
 * -------------------------------------------------------------------------------------*/
function NXLeadConvert() {
    $("#convertLeadForm").validate({
        rules: {
            first_name: 'required',
            last_name: 'required',
            client_company_name: 'required',
            email: 'required'
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#createCustomerButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [LEADS - CREATE]
 * @description: validation for creating a lead
 * -------------------------------------------------------------------------------------*/
function NXLeadCreate() {

    //clean up lead value field - strip symbols and none numric
    $(document).on('click', '#commonModalSubmitButton', function() {
        if ($('#lead_value').length) {
            var lead_value = $("#lead_value").val();
            $("#lead_value").val(lead_value.replace(/[^0-9.]/g, ""));
        }
    });

    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            lead_title: "required",
            lead_firstname: "required",
            lead_lastname: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [LEADS - KANBAN]
 * @description: bootstrap the kanban board
 * -------------------------------------------------------------------------------------*/
function NXLeadsKanban() {

    if ($(".js-trigger-leads-kanban-board").length) {

        //variable
        var lead_position = $("#leads-view-wrapper").attr('data-position');
        var convert_to_project = $("#leads-view-wrapper").attr('data-porject-convert');
        var convert_to_project_status_id = $("#leads-view-wrapper").attr('data-convert-project-status-id');

        //dga and drop
        var cardsDraggable = dragula({
                isContainer: function(el) {
                    return el.classList.contains('kanban-content');
                }
            }).on('drag', function(card) {
                // add 'is-moving' class to element being dragged
                card.classList.add('is-moving');
            })
            .on('dragend', function(card) {
                // remove 'is-moving' class from element after dragging has stopped
                card.classList.remove('is-moving');
                // add the 'is-moved' class for 600ms then remove it
                window.setTimeout(function() {
                    card.classList.add('is-moved');
                    window.setTimeout(function() {
                        card.classList.remove('is-moved');
                    }, 600);
                }, 100);

                //this card id
                var this_lead_id = $(card).attr('data-lead-id');

                //previous card's lead  id
                var previous_list = $(card).prevAll()
                var previous_lead_id = '';
                previous_list.each(function() {
                    if ($(this).hasClass('kanban-card')) {
                        previous_lead_id = $(this).attr('data-lead-id');
                        return false;
                    }
                });

                //next card's lead id
                var next_lead_id = $(card).next('.kanban-card').attr('data-lead-id');
                if (typeof next_lead_id == 'undefined') {
                    next_lead_id = '';
                }

                //board
                var board_name = $(card).parent('.kanban-content').attr('data-board-name');

                //ajax update request

                if (convert_to_project == 'enable' && convert_to_project_status_id == board_name) {

                    var data_modal_title = $("#leads-view-wrapper").attr('data-modal-title');
                    var data_action_url = $("#leads-view-wrapper").attr('data-action-url');

                    var payload_data = 'lead_id=' + this_lead_id + '&previous_lead_id=' + previous_lead_id +
                        '&next_lead_id=' + next_lead_id + '&status=' + board_name;
                    var card = document.getElementById('js-ajax-data-' + this_lead_id);
                    //
                    NXCreateElement({
                        tag: 'button',
                        type: 'button',
                        id: 'card-ststus-data-button-' + this_lead_id,
                        'data-url': lead_position + '?' + payload_data,
                        'data-ajax-type': "POST",
                        class: ['hidden', 'edit-add-modal-button', 'js-ajax-ux-request', 'reset-target-modal-form'],
                        'data-toggle': "modal",
                        'data-target': "#commonModal",
                        'data-loading-target': "commonModalBody",
                        'data-modal-title': data_modal_title,
                        'data-action-url': data_action_url,
                        'data-action-method': "POST",
                        'data-action-ajax-loading-target': "commonModalBody",
                    }, card);

                    var button = document.getElementById("card-ststus-data-button-" + this_lead_id);
                    NXSimulate(button, "click");
                } else {
                    var update_position = $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        url: lead_position,
                        dataType: 'json',
                        data: 'lead_id=' + this_lead_id + '&previous_lead_id=' + previous_lead_id +
                            '&next_lead_id=' + next_lead_id + '&status=' + board_name,
                    });
                }
            });
    }
}
if ($(".js-trigger-leads-kanban-board").length) {
    NXLeadsKanban();
}


/**--------------------------------------------------------------------------------------
 * [LEADS - CREATE]
 * @description: validation for creating a lead
 * -------------------------------------------------------------------------------------*/
function NXTaskCreate() {

    //task type selector
    $(".task_type_selector").on('click', function() {
        $("#task_type").val($(this).val());
    });

    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            task_title: "required",
            task_projectid: "required",
            task_priority: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [LEADS - KANBAN]
 * @description: bootstrap the kanban board
 * -------------------------------------------------------------------------------------*/
function NXTasksKanban() {

    if ($("#js-tasks-kanban-wrapper").length) {

        //position
        var task_position = $("#js-tasks-kanban-wrapper").attr('data-position');

        //gragable
        var cardsDraggable = dragula({
                isContainer: function(el) {
                    return el.classList.contains('kanban-content');
                }
            }).on('drag', function(card) {
                // add 'is-moving' class to element being dragged
                card.classList.add('is-moving');
            })
            .on('dragend', function(card) {
                // remove 'is-moving' class from element after dragging has stopped
                card.classList.remove('is-moving');
                // add the 'is-moved' class for 600ms then remove it
                window.setTimeout(function() {
                    card.classList.add('is-moved');
                    window.setTimeout(function() {
                        card.classList.remove('is-moved');
                    }, 600);
                }, 100);

                //this card id
                var this_task_id = $(card).attr('data-task-id');

                //previous card's task  id
                var previous_list = $(card).prevAll()
                var previous_task_id = '';
                previous_list.each(function() {
                    if ($(this).hasClass('kanban-card')) {
                        previous_task_id = $(this).attr('data-task-id');
                        return false;
                    }
                });

                //next card's task id
                var next_task_id = $(card).next('.kanban-card').attr('data-task-id');
                if (typeof next_task_id == 'undefined') {
                    next_task_id = '';
                }

                //board
                var board_name = $(card).parent('.kanban-content').attr('data-board-name');

                //ajax update request
                var update_position = $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: task_position,
                    dataType: 'json',
                    data: 'task_id=' + this_task_id + '&previous_task_id=' + previous_task_id +
                        '&next_task_id=' + next_task_id + '&status=' + board_name,
                });
            });
    }
}
if ($("#js-tasks-kanban-wrapper").length) {
    NXTasksKanban();
}

/**--------------------------------------------------------------------------------------
 * [LEADS - SHOW]
 * @description: js on lead card
 * -------------------------------------------------------------------------------------*/
function NXLeadAttachFiles() {

    $("#fileupload_lead").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [MILESTONE PAGE]
 * @description: drag and drop table rows
 * -------------------------------------------------------------------------------------*/
function NXMilestonesDragDrop() {

    if ($("#js-trigger-milestones-sorting").length) {
        /*----------------------------------------------------------------
         * drag and drop milestone positions
         *--------------------------------------------------------------*/
        var container = document.getElementById('milestones-td-container');

        var stagesDraggable = dragula([container]);

        //make every board dragable area
        stagesDraggable.on('drag', function(stage) {
            stage.classList.add('is-moving');
        });
        stagesDraggable.on('dragend', function(stage) {
            stage.classList.remove('is-moving');
            window.setTimeout(function() {
                stage.classList.add('is-moved');
                window.setTimeout(function() {
                    stage.classList.remove('is-moved');
                }, 600);
            }, 100);

            //update the list
            nxAjaxUxRequest($("#milestones-table"));
        });
    }
}
if ($("#js-trigger-milestones-sorting").length) {
    NXMilestones();
}


/**--------------------------------------------------------------------------------------
 * [MILESTONE MODAL]
 * @description: validation on creating milestone
 * -------------------------------------------------------------------------------------*/
function NXMilestonesCreate() {

    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            milestone_title: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [NOTES MODAL]
 * @description: validation on creating notes
 * -------------------------------------------------------------------------------------*/
function NXNotesCreate() {

    nxTinyMCEBasic();

    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            note_title: "required",
            note_description: "required"
        },
        submitHandler: function(form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [LEFT MENU - CLIENTS]
 * @blade : nav\leftmenu-client.blade.php
 * @description: js for the left menu
 * -------------------------------------------------------------------------------------*/
function NXANavCLient() {
    $(".sidenav-menu-item").has("ul").addClass('has-submenu');
}
if ($("#js-trigger-nav-client").length) {
    NXHomeClient();
}



/**--------------------------------------------------------------------------------------
 * [LEFT MENU - CLIENTS]
 * @blade : nav\leftmenu-team.blade.php
 * @description: js for the left menu
 * -------------------------------------------------------------------------------------*/
function NXANavTeam() {
    /*----------------------------------------------------------------
     * add special class to menu items that have submenu
     *--------------------------------------------------------------*/
    $(".sidenav-menu-item").has("ul").addClass('has-submenu');


    /*----------------------------------------------------------------
     * left menu scroll
     *---------------------------------------------------------------*/
    $(document).on('click', '.sidebartoggler ', function() {
        //scroll mini menu back to top
        document.getElementById('main-scroll-sidebar').scrollTop = 0;
    });



    /*----------------------------------------------------------------
     * left menu toltip
     *---------------------------------------------------------------*/
    if ($('body').hasClass('mini-sidebar')) {
        $(".menu-with-tooltip").addClass('menu-tooltip');
    } else {
        $(".menu-with-tooltip").removeClass('menu-tooltip');
    }
    $('.menu-tooltip').tooltip({
        trigger: 'hover',
        placement: 'right',
        template: '<div class="tooltip menu-tooltips" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
    });
    $(document).on('click', '.menu-tooltip', function() {
        $('.menu-tooltip menu-with-tooltip').tooltip("hide");
    });
}
if ($("#js-trigger-nav-team").length) {
    NXANavTeam();
    nxMainLeftMenuScroll();
}



/**--------------------------------------------------------------------------------------
 * [STRIPE BUTTON]
 * @description: geneate button shown on invoice page
 * -------------------------------------------------------------------------------------*/
function NXStripePaymentButton() {

    //get api keys
    var stripe_api_key = $("#js-pay-stripe").attr('data-key');
    var payment_session = $("#js-pay-stripe").attr('data-session');

    //redirect user to checkout page
    $(document).on('click', '#invoice-stripe-payment-button', function() {
        //set stripe public ket
        var stripe = Stripe(stripe_api_key);
        //redirect to stripe checkout page
        stripe.redirectToCheckout({
            sessionId: payment_session
        }).then(function(result) {
            //an error occured and stripe could not redirect
        });
    });
}


/**--------------------------------------------------------------------------------------
 * [STRIPE BUTTON]
 * @description: geneate button shown on invoice page
 * -------------------------------------------------------------------------------------*/
function NXRazorpayPaymentButton() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $('body').on('click', '#invoice-razorpay-payment-button', function(e) {
            //get vars
            var keyid = $(this).attr("data-key");
            var amount = $(this).attr("data-amount");
            var currency = $(this).attr("data-currency");
            var company_name = $(this).attr("data-company-name");
            var description = $(this).attr("data-description");
            var image = $(this).attr("data-image");
            var thankyou_url = $(this).attr("data-thankyou-url");
            var client_name = $(this).attr("data-client-name");
            var client_email = $(this).attr("data-client-email");
            var order_id = $(this).attr("data-order-id");
            //initiate
            var options = {
                "key": keyid,
                "amount": amount,
                "currency": currency,
                "name": company_name,
                "description": description,
                "image": image,
                "order_id": order_id,
                "callback_url": thankyou_url,
                "prefill": {
                    "name": client_name,
                    "email": client_email,
                },
                "theme": {
                    "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        });
    });
}


/**--------------------------------------------------------------------------------------
 * [PAYMENT MODAL]
 * @description: validation for creating a new payment
 * -------------------------------------------------------------------------------------*/
function NXPayementCreate() {
    //validate
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            payment_invoiceid: "required",
            payment_amount: "required",
            payment_date: "required",
            payment_gateway: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [PROJECT DETAILS]
 * @description: editing project details on project page
 * -------------------------------------------------------------------------------------*/
function NXProjectDetails() {

    //editor variables
    NX.project_description = $("#project-description");
    NX.project_description_original_text = $("#project-description").html();
    NX.project_descrition_selector = '#project-description';
    NX.project_description_submit = $("#project-description-submit")
    NX.project_description_edit = $("#project-description-edit")
    NX.project_description_height = $("#project-description").outerHeight();
    NX.project_details_tags_edit = $("#project-details-edit-tags")
    NX.project_details_tags = $("#project-details-tags")

    //edit button clicked
    $(document).on('click', '#project-description-button-edit', function() {
        NX.project_description_edit.hide();
        NX.project_description.addClass('tinymce-textarea');
        NX.project_details_tags.hide();
        NX.project_details_tags_edit.show();
        NX.project_description_submit.show();
        nxTinyMCEExtended(NX.project_description_height, NX.project_descrition_selector);
    });

    //cancel button clicked
    $(document).on('click', '#project-description-button-cancel', function() {
        NX.project_description.removeClass('tinymce-textarea');
        NX.project_description_submit.hide();
        NX.project_description_edit.show();
        NX.project_details_tags.show();
        NX.project_details_tags_edit.hide();
        NX.project_description.html(NX.project_description_original_text);
        tinymce.remove();
    });
    //save button clicked
    $(document).off('click', '#project-description-button-save').on('click', '#project-description-button-save', function() {
        try {
            $("#description").val(tinymce.activeEditor.getContent());
        } catch (err) {}
        //unbind events
        $(this).off("click");
        $("#project-description-button-edit").off("click");
        $("#project-description-button-cancel").off("click");
        //make request
        nxAjaxUxRequest($(this));
        tinymce.remove();
    });
}


/**--------------------------------------------------------------------------------------
 * [PROJECT - DYNAMIC]
 * @description: show dynamic project pages
 * -------------------------------------------------------------------------------------*/
if ($("#dynamic-project-content").length) {
    nxAjaxUxRequest($("#dynamic-project-content"));
}


/**--------------------------------------------------------------------------------------
 * [LEFT MENU - CLIENTS]
 * @blade : nav\leftmenu-client.blade.php
 * @description: js for the left menu
 * -------------------------------------------------------------------------------------*/
function NXANavCLient() {
    $(".sidenav-menu-item").has("ul").addClass('has-submenu');
}
if ($("#js-trigger-nav-client").length) {
    NXHomeClient();
}


/**--------------------------------------------------------------------------------------
 * [PROJECT - CHARTS]
 * @description: render charts of project page
 * -------------------------------------------------------------------------------------*/
if ($("#project_details").length) {
    var progress = $("#project_details").attr('data-progress');
    //set color
    if (progress != null) {
        var chart = c3.generate({
            bindto: '#project_progress_chart',
            data: {
                columns: [
                    ['data', progress]
                ],
                type: 'gauge'
            },
            color: {
                pattern: ['#24d2b5']
            },
            gauge: {
                width: 22,
            },
            size: {
                height: 90,
                width: 150
            }
        });
    }
}


/**--------------------------------------------------------------------------------------
 * PROJECT - MODAL]
 * @description: edit projects modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditProject() {

    // //page section
    var page_section = $("#js-projects-modal-add-edit").attr('data-section');


    //reset editor
    nxTinyMCEBasic();

    /** ----------------------------------------------------------
     * create project - form validation
     * ---------------------------------------------------------*/
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                project_title: "required"
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    /** ----------------------------------------------------------
     * edit project - form validation
     * ---------------------------------------------------------*/
    if (page_section == 'edit') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                project_title: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    /** ----------------------------------------------------------
     * sanity - ensure views are checked also
     * ---------------------------------------------------------*/
    $("#clientperm_tasks_create, #clientperm_tasks_collaborate").on("change", function() {
        if ($(this).is(":checked")) {
            $("#clientperm_tasks_view").prop('checked', true);
        }
    });
    $("#clientperm_tasks_view").on("change", function() {
        if (!$(this).is(":checked")) {
            $("#clientperm_tasks_collaborate").prop('checked', false);
            $("#clientperm_tasks_create").prop('checked', false);
        }
    });


    /** ----------------------------------------------------------
     * clean up lead value field - strip symbols and none numric
     * ---------------------------------------------------------*/
    $(document).on('click', '#commonModalSubmitButton', function() {
        if ($('#project_billing_rate').length) {
            var project_billing_rate = $("#project_billing_rate").val();
            $("#project_billing_rate").val(project_billing_rate.replace(/[^0-9.]/g, ""));
        }
    });


    /** ----------------------------------------------------------
     * select2 firefox fix
     * ---------------------------------------------------------*/
    $('#project_clientid').select2({
        dropdownParent: $('#commonModal')
    });


    //client list has been reset or cleared
    $(document).on("select2:unselecting", "#project_template_selector", function(e) {
        //set new data
        $("#project_title").val('');

        $("#project_categoryid").val(1);
        $("#project_categoryid").trigger("change");

        $("#assignedperm_tasks_collaborate").prop('checked', false);
        $("#clientperm_tasks_view").prop('checked', false);
        $("#clientperm_tasks_collaborate").prop('checked', false);
        $("#clientperm_tasks_create").prop('checked', false);
        $("#clientperm_timesheets_view").prop('checked', false);
        $("#clientperm_expenses_view").prop('checked', false);
        $("#project_billing_rate").val('');
        $("#project_billing_type").val('hourly');
        $("#project_billing_type").trigger("change");
        $("#project_billing_estimated_hours").val('');
        $("#project_billing_costs_estimate").val('');

        $("#project_custom_field_1").val('');
        $("#project_custom_field_2").val('');
        $("#project_custom_field_3").val('');
        $("#project_custom_field_4").val('');
        $("#project_custom_field_5").val('');
        $("#project_custom_field_6").val('');
        $("#project_custom_field_7").val('');
        $("#project_custom_field_8").val('');
        $("#project_custom_field_9").val('');
        $("#project_custom_field_10").val('');

        //reset tiny mce
        tinymce.get('project_description').setContent('');
        $("#project_description").html('');
        $("#project_description").val('');


    });


    //client list has been reset or cleared
    $(document).off("select2:select", "#project_template_selector").on("select2:select", "#project_template_selector", function(e) {

        //set new data
        $("#project_title").val($(this).find(':selected').attr('data-title'));

        $("#project_categoryid").val($(this).find(':selected').attr('data-category'));
        $("#project_categoryid").trigger("change");

        $("#assignedperm_tasks_collaborate").prop('checked', ($(this).find(':selected').attr('data-client-task-collaborate')) == 'yes' ? true : false);
        $("#clientperm_tasks_view").prop('checked', ($(this).find(':selected').attr('data-client-task-view') == 'yes') ? true : false);
        $("#clientperm_tasks_collaborate").prop('checked', ($(this).find(':selected').attr('data-client-task-collaborate') == 'yes') ? true : false);
        $("#clientperm_tasks_create").prop('checked', ($(this).find(':selected').attr('data-client-task-create') == 'yes') ? true : false);
        $("#clientperm_timesheets_view").prop('checked', ($(this).find(':selected').attr('data-client-view-timesheets') == 'yes') ? true : false);
        $("#clientperm_expenses_view").prop('checked', ($(this).find(':selected').attr('data-client-view-expenses') == 'yes') ? true : false);
        $("#project_billing_rate").val($(this).find(':selected').attr('data-billing-rate'));
        $("#project_billing_type").val($(this).find(':selected').attr('data-billing-type'));
        $("#project_billing_type").trigger("change");

        $("#project_billing_estimated_hours").val($(this).find(':selected').attr('data-billing-estimated-hours'));
        $("#project_billing_costs_estimate").val($(this).find(':selected').attr('data-billing-estimated-cost'));

        $("#project_custom_field_1").val($(this).find(':selected').attr('data-custom-1'));
        $("#project_custom_field_2").val($(this).find(':selected').attr('data-custom-2'));
        $("#project_custom_field_3").val($(this).find(':selected').attr('data-custom-3'));
        $("#project_custom_field_4").val($(this).find(':selected').attr('data-custom-4'));
        $("#project_custom_field_5").val($(this).find(':selected').attr('data-custom-5'));
        $("#project_custom_field_6").val($(this).find(':selected').attr('data-custom-6'));
        $("#project_custom_field_7").val($(this).find(':selected').attr('data-custom-7'));
        $("#project_custom_field_8").val($(this).find(':selected').attr('data-custom-8'));
        $("#project_custom_field_9").val($(this).find(':selected').attr('data-custom-9'));
        $("#project_custom_field_10").val($(this).find(':selected').attr('data-custom-10'));

        //ajax request to update text editor
        nxAjaxUxRequest($(this).find(':selected'));

    });
}
/**--------------------------------------------------------------------------------------
 * PROJECT - Project Inventory]
 * @description: update projects inventory items
 * -------------------------------------------------------------------------------------*/
function NXUpdateProjectInventory() {

    // //page section
    var page_section = $("#js-projects-modal-update-inventory").attr('data-section');

    /**-------------------------------------------------------------
     * FILE UPLOAD TOGGLE
     * ------------------------------------------------------------*/
    $(document).off('click', '#js-card-toggle-fileupload').on('click', '#js-card-toggle-fileupload', function() {
        $(document).find("#card-fileupload-container").toggle();
    });

    /**-------------------------------------------------------------
     * FILE UPLOAD
     * ------------------------------------------------------------*/
    if ($("#card-attachments").length) {
        //get the url
        var upload_url = $("#card-attachments").attr('data-upload-url');
        //attache the files
        if ($("#card_fileupload").length) {
            $("#card_fileupload").dropzone({
                url: upload_url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                init: function() {
                    this.on("error", function(file, message, xhr) {
                        //is there a message from backend [abort() response]
                        if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                            var error = $.parseJSON(xhr.response);
                            if (typeof error === 'object' && typeof error.notification != 'undefined') {
                                var message = error.notification.value;
                            } else {
                                var message = NXLANG.generic_error;
                            }
                        }

                        //any other message
                        var message = (typeof message == 'undefined' || message == '' ||
                            typeof message == 'object') ? NXLANG.generic_error : message;

                        //error message
                        NX.notification({
                            type: 'error',
                            message: message
                        });
                        //remove the file
                        this.removeFile(file);
                    });
                },
                success: function(file, response) {
                    $("#card-fileupload-container").hide();
                    //get the priview box dom elemen
                    var $preview = $(file.previewElement);
                    //add to the list
                    $("#card-attachments-container").prepend(response.attachment);
                    //remove the file
                    this.removeFile(file);
                }
            });
        }
    }


    /**-------------------------------------------------------------
     * SINGLE FILE UPLOAD
     * ------------------------------------------------------------*/
    $("#fileupload_single_image").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });

    /** ----------------------------------------------------------
     * create project - form validation
     * ---------------------------------------------------------*/
    if (page_section == 'update_inventory_item') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {},
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

/**--------------------------------------------------------------------------------------
 * INVENTORY - MODAL]
 * @description: edit inventory modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditFaultyItem() {

    //file upload
    $("#fileupload_single_image").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });

    //page
    var page_section = $("#js-trigger-faulty-equipment-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {

            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit inventory
    if (page_section == 'edit') {
        //show address section by default
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {

            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

/**--------------------------------------------------------------------------------------
 * TRANSPORT - MODAL]
 * @description: edit transport modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditTransportBooking() {

    //file upload
    $("#fileupload_single_image").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });

    //page
    var page_section = $("#js-trigger-faulty-equipment-modal-add-edit").attr('data-payload');

    //show address section by default
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {

        },
        submitHandler: function(form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * INVENTORY - MODAL]
 * @description: edit inventory modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditInventory() {
    //page
    var page_section = $("#js-trigger-inventory-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                name: "required",
                quantity: "required",
                qty_country: 'qty_country[]'
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit inventory
    if (page_section == 'edit') {
        //show address section by default
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                name: "required",
                quantity: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}


/**--------------------------------------------------------------------------------------
 * CLIENT - MODAL]
 * @description: edit client modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditClients() {
    //page
    var page_section = $("#js-trigger-clients-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                client_company_name: "required",
                first_name: "required",
                last_name: "required",
                email: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit client
    if (page_section == 'edit') {
        //show address section by default
        $("#add_client_address_section").show();
        $("#add_client_other_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                client_company_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}


/**--------------------------------------------------------------------------------------
 * [SETUP WIZARD]
 * @description: admin details section - form validation
 * -------------------------------------------------------------------------------------*/
function NXSetupAdmin() {
    $("#setupForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            password: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#continueButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [SETUP WIZARD]
 * @description: database details section - form validation
 * -------------------------------------------------------------------------------------*/
function NXSetupDatabase() {
    $("#setupForm").validate({
        rules: {
            database_host: "required",
            database_port: "required",
            database_name: "required",
            database_username: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#continueButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETUP WIZARD]
 * @description: database details section - form validation
 * -------------------------------------------------------------------------------------*/
function NXSetupSettings() {
    $("#setupForm").validate({
        rules: {
            settings_company_name: "required",
            settings_system_timezone: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#continueButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TAGS MODAL]
 * @description: add tags form validation
 * -------------------------------------------------------------------------------------*/
function NXTagsCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            tag_title: "required",
            tagresource_type: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TAGS MENU]
 * @description: trigger fortags page
 * -------------------------------------------------------------------------------------*/
function NXTagsMenu() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            tag_title: "required",
            tagresource_type: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TAGS MENU]
 * @description: trigger fortags page
 * -------------------------------------------------------------------------------------*/
function NXTeamCreate() {
    $("#password, #password_confirmation").val('');
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            first_name: 'required',
            last_name: 'required',
            email: 'required',
            role_id: 'required'
        },
        submitHandler: function(form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TICKET COMPOSE]
 * @description: create new ticket
 * -------------------------------------------------------------------------------------*/
if ($("#ticket-compose-form").length) {

    //variable
    var user_type = $("#ticket-compose-form").attr('data-user-type');


    //validate
    if (user_type == 'client') {
        $("#ticket-compose-form").validate({
            rules: {
                ticket_categoryid: "required",
                ticket_subject: "required",
                ticket_message: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#ticket-compose-form-button"));
            }
        });
    }

    if (user_type == 'team') {
        $("#ticket-compose-form").validate({
            rules: {
                ticket_categoryid: "required",
                ticket_subject: "required",
                ticket_message: "required",
                ticket_clientid: "required",
                ticket_priority: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#ticket-compose-form-button"));
            }
        });
    }

    //fileupload
    $("#fileupload_ticket").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TICKETS EDIT]
 * @description: validation for tickets page
 * -------------------------------------------------------------------------------------*/
function NXTicketEdit() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            ticket_categoryid: "required",
            ticket_subject: "required",
            ticket_message: "required",
            ticket_clientid: "required",
            ticket_priority: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TICKETS EDIT]
 * @description: validation for tickets page
 * -------------------------------------------------------------------------------------*/
if ($("#ticket-editor").length) {
    $("#editTicketMessage").validate({
        rules: {

        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#editTicketMessageButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [CLIENT DYNAMIC]
 * @description: load dynamic client page contact
 * -------------------------------------------------------------------------------------*/
if ($("#dynamic-client-content").length) {
    nxAjaxUxRequest($("#dynamic-client-content"));
}

/**--------------------------------------------------------------------------------------
 * [TICKETS REPLY]
 * @description: validation for tickets reply modal
 * -------------------------------------------------------------------------------------*/
function NXTicketReplay() {
    //file upload
    $("#fileupload_ticket_reply").dropzone({
        url: NX.base_url + "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });

    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            ticketreply_text: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}




/**--------------------------------------------------------------------------------------
 * [USER - UPDATE AVATAR]
 * @description: validation for user avatar modal
 * -------------------------------------------------------------------------------------*/
function NXUserUpdateAvatar() {

    //upload avatar
    $("#fileupload_avatar").dropzone({
        url: NX.base_url + "/avatarupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON($.parseJSON(xhr.response).message);
                    var message = error.notification.value;
                }
                //any other message
                message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="avatar_filename"  value="' + response.filename + '">');
            $preview.append('<input type="hidden" name="avatar_directory"  value="' + response.uniqueid + '">');
        }
    });


    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}


/**--------------------------------------------------------------------------------------
 * [USER - UPDATE PASSWORD]
 * @description: validation for user update passwod
 * -------------------------------------------------------------------------------------*/
function NXUserUpdatePassword() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            password: {
                minlength: 6,
                required: true,
            },
            password_confirmation: {
                equalTo: "#password"
            },
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS DYNAMIC]
 * @description: settings dynamic content
 * -------------------------------------------------------------------------------------*/
if ($("#dynamic-settings-content").length) {
    nxAjaxUxRequest($("#dynamic-settings-content"));
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - KNOWLDGEBASE]
 * @description: validation for KNOWLEDGEBASE
 * -------------------------------------------------------------------------------------*/
function NXSettingsKnowledgebase() {


    //formvalidation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            kbcategory_title: "required"
        },
        submitHandler: function(form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });



    //icon selection
    $(".js-icon-selector").on('click', function() {
        var value = $(this).attr('data-val');
        //reset active
        $(".js-icon-selector").removeClass('active');
        $(this).addClass('active');
        //set input value
        $("#kbcategory_icon").val(value);
        //change display
        $("#icon-selector-display").removeClass();
        $("#icon-selector-display").addClass(value);
        //toggle whole section
        $("#category_display_icons_section").toggle();
    });


    //toggle icons
    $(".js-switch-toggle-icons").on('click', function() {
        $("#category_display_icons_section").toggle();
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - KNOWLDGEBASE]
 * @description: drag and drop for categories
 * -------------------------------------------------------------------------------------*/
function NXSettingsKnowledgebaseCategories() {

    //replace action button
    $(".parent-page-actions").html('');
    $("#list-page-actions").prependTo(".parent-page-actions");


    //drag and drop categories
    var container = document.getElementById('categories-td-container');

    var stagesDraggable = dragula([container]);


    //make every board dragable area
    stagesDraggable.on('drag', function(stage) {
        // add 'is-moving' class to element being dragged
        stage.classList.add('is-moving');
    });
    stagesDraggable.on('dragend', function(stage) {
        // remove 'is-moving' class from element after dragging has stopped
        stage.classList.remove('is-moving');
        // add the 'is-moved' class for 600ms then remove it
        window.setTimeout(function() {
            stage.classList.add('is-moved');
            window.setTimeout(function() {
                stage.classList.remove('is-moved');
            }, 600);
        }, 100);

        //update the list
        nxAjaxUxRequest($("#knowledgebase-categories"));

    });
}


/**--------------------------------------------------------------------------------------
 * [SELECT ASSIGNED USER]
 * @description: preselect assigned users when the add icon is clicked
 * -------------------------------------------------------------------------------------*/
function NXCardsAssignedSelect() {
    //lop through all users in the assigned users (icons) list
    $(document).find(".card-assigned-listed-user").each(function() {
        //get the assigned users id
        var user_id = $(this).attr('data-user-id');
        //now check this user in the popover list
        var $user = $(".assigned_user_" + user_id);
        $user.prop('checked', true);
    });
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - LEAD STATUS]
 * @description: form aldation for lead status
 * -------------------------------------------------------------------------------------*/
function NXSettingsLeadStatus() {


    //change lead color - update form field
    $(document).on('change', '.leadstatus_colors', function() {
        if (this.checked) {
            $("#leadstatus_color").val($(this).val());
        }
    });


    //create status - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            leadstatus_title: "required"
        },
        submitHandler: function(form) {
            //set selector color
            $(".leadstatus_colors").each(function() {
                if (this.checked) {
                    $("#leadstatus_color").val($(this).val());
                }
            });
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - LEAD DRAG & DROP]
 * @description: drag and drop for leads
 * -------------------------------------------------------------------------------------*/
function NXSettingsLeadDragDrop() {

    //replace action buttons
    $(".parent-page-actions").html('');
    $("#list-page-actions").prependTo(".parent-page-actions");

    //drag and drop lead positions
    var container = document.getElementById('status-td-container');
    var stagesDraggable = dragula([container]);

    //make every board dragable area
    stagesDraggable.on('drag', function(stage) {
        // add 'is-moving' class to element being dragged
        stage.classList.add('is-moving');
    });
    stagesDraggable.on('dragend', function(stage) {
        // remove 'is-moving' class from element after dragging has stopped
        stage.classList.remove('is-moving');
        // add the 'is-moved' class for 600ms then remove it
        window.setTimeout(function() {
            stage.classList.add('is-moved');
            window.setTimeout(function() {
                stage.classList.remove('is-moved');
            }, 600);
        }, 100);

        //update the list
        nxAjaxUxRequest($("#lead-stages"));

    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - UPLOAD LOGO]
 * @description: apload app logo
 * -------------------------------------------------------------------------------------*/
function NXSettingsLogo() {

    //set variables and payload
    var logo_size = $("#js-settings-logos-modal").attr('data-size');

    //upload logo
    $("#fileupload_single_image").dropzone({
        url: NX.base_url + "/upload-app-logo?logo_size=" + logo_size,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="logo_filename"  value="' + response
                .filename + '">');
            $preview.append('<input type="hidden" name="logo_directory"  value="' + response
                .uniqueid + '">');
            $preview.append('<input type="hidden" name="logo_size"  value="' + response
                .logo_size + '">');
        }
    });


    //upload logo - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - MILESTONES]
 * @description: Add edit
 * -------------------------------------------------------------------------------------*/
function NXSettingsMilestones() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            milestonecategory_title: "required"
        },
        submitHandler: function(form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - DROP & DROP]
 * @description: Add edit
 * -------------------------------------------------------------------------------------*/
function NXSettingsMilestonesDragDrop() {
    //replace action buttons
    $(".parent-page-actions").html('');
    $("#list-page-actions").prependTo(".parent-page-actions");


    //drag and drop milstone positions
    var container = document.getElementById('milestones-td-container');

    var stagesDraggable = dragula([container]);

    //make every board dragable area
    stagesDraggable.on('drag', function(stage) {
        // add 'is-moving' class to element being dragged
        stage.classList.add('is-moving');
    });
    stagesDraggable.on('dragend', function(stage) {
        // remove 'is-moving' class from element after dragging has stopped
        stage.classList.remove('is-moving');
        // add the 'is-moved' class for 600ms then remove it
        window.setTimeout(function() {
            stage.classList.add('is-moved');
            window.setTimeout(function() {
                stage.classList.remove('is-moved');
            }, 600);
        }, 100);

        //update the list
        nxAjaxUxRequest($("#milestone-stages"));
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - CLIENTS]
 * @description: Add edit
 * -------------------------------------------------------------------------------------*/
function NXSettingsProjectsClients() {

    //sanity - ensure views are checked also
    $("#settings_projects_clientperm_tasks_create, #settings_projects_clientperm_tasks_collaborate").on(
        "change",
        function() {
            if ($(this).is(":checked")) {
                $("#settings_projects_clientperm_tasks_view").prop('checked', true).prop('disabled',
                    true);
            } else {
                if (!$("#settings_projects_clientperm_tasks_create").is(":checked") && !$(
                        "#settings_projects_clientperm_tasks_collaborate")
                    .is(":checked")) {
                    $("#settings_projects_clientperm_tasks_view").prop('disabled', false);
                }
            }
        });
    $("#settings_projects_clientperm_tasks_view").on("change", function() {
        if (!$(this).is(":checked")) {
            $("#settings_projects_clientperm_tasks_collaborate").prop('checked', false);
            $("#settings_projects_clientperm_tasks_create").prop('checked', false);
        }
    });

}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - ROLES]
 * @description: Add edit roles
 * -------------------------------------------------------------------------------------*/
function NXSettingsRolesPermissions() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            name: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - ROLES]
 * @description: Add edit roles
 * -------------------------------------------------------------------------------------*/
function NXSettingsRolesPermissionsTable() {
    var $actions = $("#list-page-actions");
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - SYSTEM]
 * @description: Add edit system setting
 * -------------------------------------------------------------------------------------*/
function NXSettingsSystem() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            company_name: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}
/**--------------------------------------------------------------------------------------
 * [SETTINGS - SYSTEM]
 * @description: Add edit system setting
 * -------------------------------------------------------------------------------------*/
function NXSettingsSystemTable() {
    var $actions = $("#list-page-actions");
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - SOURCES]
 * @description: list sources
 * -------------------------------------------------------------------------------------*/
function NXSettingsSources() {
    var $actions = $("#list-page-actions");
    //replace action buttons
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - SOURCES]
 * @description: Add edit sources
 * -------------------------------------------------------------------------------------*/
function NXSettingsSourcesCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            leadsources_title: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - TAXES]
 * @description: list taxes
 * -------------------------------------------------------------------------------------*/
function NXSettingsTaxes() {
    var $actions = $("#list-page-actions");
    //replace action buttons
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - TAXES]
 * @description: Add edit taxes
 * -------------------------------------------------------------------------------------*/
function NXSettingsTaxesCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            taxrate_name: "required",
            taxrate_value: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}




/**--------------------------------------------------------------------------------------
 * [SETTINGS - UPDATES]
 * @description: check for updates
 * -------------------------------------------------------------------------------------*/
function NXSettingsUpdate() {
    nxAjaxUxRequest($("#updates-checking"));
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - EMAIL TEMPLATES]
 * @description: load email templates editor
 * -------------------------------------------------------------------------------------*/
function NXSettingsEmailTemplates() {

    //text editor
    nxTinyMCEAdvanced(500, '#emailtemplate_body', 'fullpage spellchecker', '');
    setTimeout(function() {
        $("#emailEditWrapper").removeClass('loading');
        $("#emailEditContainer").show();
    }, 1000);


    //fix for validator
    $("#fix-form-email-templates").validate({});
}




/**--------------------------------------------------------------------------------------
 * [SETTINGS - GENERAL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsGeneral() {
    $("#settingsFormGeneral").validate({
        rules: {
            settings_system_timezone: "required",
            settings_system_date_format: "required",
            settings_system_datepicker_format: "required",
            settings_system_default_leftmenu: "required",
            settings_system_default_statspanel: "required",
            settings_system_pagination_limits: "required",
            settings_system_kanban_pagination_limits: "required",
            settings_system_close_modals_body_click: "required",
            settings_system_language_default: "required",
            settings_system_language_allow_users_to_change: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - CURRENCY]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsCurrency() {
    $("#settingsFormCurrency").validate({
        rules: {
            settings_system_currency_code: "required",
            settings_system_currency_symbol: "required",
            settings_system_decimal_separator: "required",
            settings_system_thousand_separator: "required",
            settings_system_currency_position: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - COMPANY]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsCompany() {
    $("#settingsFormCompany").validate({
        rules: {
            settings_company_name: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - FOO]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsProjectsGeneral() {
    $("#settingsFormProjects").validate({
        rules: {
            settings_projects_default_hourly_rate: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - INVOICES]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsInvoices() {
    $("#settingsFormInvoices").validate({
        rules: {
            settings_invoices_recurring_grace_period: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - PAYPAL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsPaypal() {
    $("#settingsFormPaypal").validate({
        rules: {
            settings_paypal_email: "required",
            settings_paypal_currency: "required",
            settings_paypal_display_name: "required",
            settings_stripe_ipn_url: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - BANK]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsBank() {
    $("#settingsFormBank").validate({
        rules: {
            settings_bank_display_name: "required",
            settings_bank_status: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - STRIPE]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsStripe() {
    $("#settingsFormStripe").validate({
        rules: {
            settings_stripe_public_key: "required",
            settings_stripe_secret_key: "required",
            settings_stripe_webhooks_key: "required",
            settings_stripe_currency: "required",
            settings_stripe_display_name: "required",
            settings_stripe_ipn_url: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - EMAIL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsEmailGeneral() {
    $("#settingsFormEmailGeneral").validate({
        rules: {
            settings_email_from_address: "required",
            settings_email_from_name: "required",
            settings_email_server_type: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - EMAIL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsEmailSMTP() {
    $("#settingsFormEmailSMTP").validate({
        rules: {
            settings_email_smtp_host: "required",
            settings_email_smtp_port: "required",
            settings_email_smtp_username: "required",
            settings_email_smtp_password: "required",
            settings_email_smtp_encryption: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - SEND TEST EMAIL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXEmailSettingTest() {
    //add category - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            email: "required"
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - SUBSCRIPTIONS]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsSubscriptions() {
    $("#settingsFormSubscriptions").validate({
        rules: {
            settings_subscriptions_prefix: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * Form validation for converting an estimate to an invoice
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXConvertEstimetToInvoice() {
    //create status - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_date_edit: "required",
            bill_due_date_edit: "required",
        },
        submitHandler: function(form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * Form validation for converting an estimate to an invoice
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXRecordMyTmeModal() {

    //disable the submit button and form fields
    NX.recordTaskTimeToggle('disable');

    //create status - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            manual_time_hours: "required",
            manual_time_minutes: "required",
            timer_created_edit: "required",
        },
        submitHandler: function(form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * This is an extenstion of NXRecordMyTmeModal() we use this on the task list page
 * to enable buttons and fields that were disabled by NXRecordMyTmeModal()
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXRecordMyTmeModalExtra() {

    //disable the submit button and form fields
    NX.recordTaskTimeToggle('enable');
}




/**--------------------------------------------------------------------------------------
 * [IMPORT FILES]
 * file upload for the first step of the importing process
 * -------------------------------------------------------------------------------------*/
function NXImportingFileUpload() {

    //upload avatar
    $(".import-files-upload").dropzone({
        url: NX.base_url + "/import/uploadfiles",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 10, // MB
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function() {
            this.on("error", function(file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function(file, response) {
            //hide files
            $("#importing-step-1").hide();

            //show next step
            $("#importing-step-2").show();

            //show submit button
            $("#commonModalFooter").show();

            //save info about the files
            $("#importing-file-name").val(response.filename);
            $("#importing-file-uniqueid").val(response.uniqueid);

            //show splash
            $("#import-payload-preview-" + response.extension).show();
            $("#import-payload-preview-text").show();

            //change display file name
            $("#import-payload-preview-filename").html(response.filename);

            //change display file size
            $("#import-payload-preview-meta").html(response.filesize);
        }
    });

    //final formvalidation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}




/**--------------------------------------------------------------------------------------
 * Form validation for cloning a project
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXProjectClone() {
    //create status - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            project_clientid: "required",
            project_title: "required",
            project_date_start: "required",
        },
        submitHandler: function(form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}
//task options
$(document).ready(function() {

    $(document).on('change', '#copy_tasks', function() {
        if ($(this).is(':checked')) {
            //show task options
            $("#clone_project_task_options").show();
            //check milestones and disable
            $("#copy_milestones").prop('checked', true);
            $("#copy_milestones").prop('disabled', true);
        } else {
            $("#clone_project_task_options").hide();
            $("#copy_milestones").prop('disabled', false);
            $("#copy_milestones").prop('checked', false);
            $("#copy_tasks_files").prop('checked', false);
            $("#copy_tasks_checklist").prop('checked', false);
        }
    });
});




/**--------------------------------------------------------------------------------------
 * PROJECT - MODAL]
 * @description: edit projects modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditProjectTemplate() {


    //page section
    var page_section = $("#js-templates-modal-add-edit").attr('data-section');


    //reset editor
    nxTinyMCEBasic();


    /** ----------------------------------------------------------
     * form validation
     * ---------------------------------------------------------*/
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            template_title: "required",
            template_categoryid: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });


    /** ----------------------------------------------------------
     * sanity - ensure views are checked also
     * ---------------------------------------------------------*/
    $("#clientperm_tasks_create, #clientperm_tasks_collaborate").on("change", function() {
        if ($(this).is(":checked")) {
            $("#clientperm_tasks_view").prop('checked', true);
        }
    });
    $("#clientperm_tasks_view").on("change", function() {
        if (!$(this).is(":checked")) {
            $("#clientperm_tasks_collaborate").prop('checked', false);
            $("#clientperm_tasks_create").prop('checked', false);
        }
    });


    /** ----------------------------------------------------------
     * clean up lead value field - strip symbols and none numric
     * ---------------------------------------------------------*/
    $(document).on('click', '#commonModalSubmitButton', function() {
        if ($('#template_billing_rate').length) {
            var template_billing_rate = $("#template_billing_rate").val();
            $("#template_billing_rate").val(template_billing_rate.replace(/[^0-9.]/g, ""));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * VENDOR - MODAL]
 * @description: edit vendor modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditVendors() {
    //page
    var page_section = $("#js-trigger-vendors-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                vendor_company_name: "required",
                first_name: "required",
                last_name: "required",
                // email: "required",
                mobile_number: "required",
                // email: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        $("#add_vendor_address_section").show();
        $("#add_vendor_other_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                vendor_company_name: "required",
                first_name: "required",
                last_name: "required",
                // email: "required",
                mobile_number: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

/**--------------------------------------------------------------------------------------
 * [PURCHASE ORDER - ADD]
 * @blade : purchaseorders\components\modals\add-edit-inc.blade.php
 * @description: validation for creating a purchase order
 * -------------------------------------------------------------------------------------*/
function NXPurchaseOrderCreate() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            po_vendorid: "required",
            vendor_company_name: "required",
            first_name: "required",
            last_name: "required",
            mobile_number: "required",
            po_date_add_edit: "required",
            po_refno_add_edit: "required",
        },
        submitHandler: function(form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * WAREHOUSE - MODAL]
 * @description: edit warehouse modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditWarehouses() {
    //page
    var page_section = $("#js-trigger-warehouses-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                warehouse_name: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        // $("#add_warehouse_address_section").show();
        // $("#add_warehouse_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                warehouse_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

/**--------------------------------------------------------------------------------------
 * RACK - MODAL]
 * @description: edit rack modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditRacks() {
    //page
    var page_section = $("#js-trigger-racks-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        // $("#add_rack_option_additional_information_section").show();
        // $("#add_rack_option_dimensions_section").show();
        // $("#add_rack_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

function NXAddEditStocks() {
    //page
    var page_section = $("#js-trigger-stocks-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        // $("#add_rack_option_additional_information_section").show();
        // $("#add_rack_option_dimensions_section").show();
        // $("#add_rack_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}


function NXAddEditVendorFiles() {
    //page
    var page_section = $("#js-trigger-vendorfiles-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();

        $("#commonModalForm").on("submit", function(e) {
            e.preventDefault();
            // var vendorFile = $('#vendorFile')[0];
            // var formData = new FormData();
            // formData.append('vendorFile', vendorFile);

            var data = new FormData();
            jQuery.each(jQuery('#vendorFile')[0].files, function(i, file) {
                data.append('vendorFile', file);
            });
            data.append('filename', $('#fileName').val());

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: $('#commonModalForm').attr('action'),
                contentType: false,
                data: data,
                processData: false,
                success: function(data) {
                    NX.notification({
                        type: "success",
                        message: 'Vendor File Added',
                    });
                    NProgress.set(1);
                }
            });
            $("#commonModalForm").off('submit');

        });

        $("#commonModalForm").validate({
            rules: {
                // vendor_company_name: "required",
                // fileName: "required",
                // last_name: "required",
                // email: "required",
                // mobile_number: "required",
                // email: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                // form.preventDefault();

                // NProgress.set(0.5);
                // $.ajax({
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     },
                //     type: 'post',
                //     url: $('#commonModalForm').attr('action'),
                //     dataType: 'json',
                //     data: formData,
                //     success: function (data) {
                //         NX.notification({
                //             type: "success",
                //             message: 'Updated Vendor Info',
                //         });
                //         NProgress.set(1);
                //         $('#update-vendor-info').hide();
                //         $('#edit-vendor-info').show();
                //         $('#vendor_payment_info').text($('#payment_info').val());
                //     }
                // });
                // alert('ajax');
                // alert($('#commonModalForm').attr('action'));
                // nxAjaxUxRequest('');
                // nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

function NXAddEditFixedAssets() {
    //page
    var page_section = $("#js-trigger-fixedassets-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        $("#add_rack_option_additional_information_section").show();
        $("#add_rack_option_dimensions_section").show();
        $("#add_rack_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

function NXAddEditExchanges() {
    //page
    var page_section = $("#js-trigger-exchanges-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        $("#add_rack_option_additional_information_section").show();
        $("#add_rack_option_dimensions_section").show();
        $("#add_rack_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

function NXAddEditBounds() {
    //page
    var page_section = $("#js-trigger-bounds-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit vendor
    if (page_section == 'edit') {
        //show address section by default
        $("#add_rack_option_additional_information_section").show();
        $("#add_rack_option_dimensions_section").show();
        $("#add_rack_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                rack_warehouse: "required",
                rack_name: "required",
            },
            submitHandler: function(form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}

function NXUpdateInvoiceStatus(e, invoiceId) {
    var status = $(e).val();
    NProgress.set(0.5);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        url: '/invoices/update-status',
        dataType: 'json',
        data: {
            id: invoiceId,
            status: status
        },
        success: function(data) {
            NX.notification({
                type: "success",
                message: 'Updated Invoice Status',
            });
            if (status == 'paid')
                $(e).parent().html('<span class="label label-outline-success">Paid</span>');
            NProgress.set(1);
            // $('#update-vendor-info').hide();
            // $('#edit-vendor-info').show();
            // $('#vendor_payment_info').text($('#payment_info').val());
        }
    });
    // console.log();
    // console.log(invoiceId);
    // NX.notification({
    //     type: "success",
    //     message: 'Updated Invoice Status to ' + capitalize($(e).val()),
    // });
}

function capitalize(s) {
    return s[0].toUpperCase() + s.slice(1);
}

function updateBoundStatus() {
    $("#bound_status").empty();
    if ($('#bound_type').val() == 'inbound') {
        $('#bound_ref_prefix').html('PO-');
        $('#bound_status').append(`<option value="purchaseordersent" selected>Purchase Order Sent</option>`);
        $('#bound_status').append(`<option value="ordershippedsupllier">Order Shipped by Supplier</option>`);
        $('#bound_status').append(`<option value="orderrecievedshinko">Order Received by Shinko</option>`);
        $('#bound_status').append(`<option value="warehousingupdated">Warehousing Updated</option>`);
        $('#bound_status').append(`<option value="others">Others</option>`);
    }
    if ($('#bound_type').val() == 'outbound') {
        $('#bound_ref_prefix').html('INV-');
        $('#bound_status').append(`<option value="saleinvoicereceived" selected>Sales Invoice Received</option>`);
        $('#bound_status').append(`<option value="ordershippedclient">Order Shipped to Client</option>`);
        $('#bound_status').append(`<option value="orderreceivedclient">Order Received by Client</option>`);
        $('#bound_status').append(`<option value="warehousingupdated">Warehousing Updated</option>`);
        $('#bound_status').append(`<option value="others">Others</option>`);
    }
}

function removeVendorRow(e) {
    $(e).parent().parent().parent().remove();
}


$(document).on('chnage focusout keyup #js-trigger-inventory-modal-add-edit input[name^="qty_country"], #js-trigger-inventory-modal-add-edit input[name="sold"], #js-trigger-inventory-modal-add-edit input[name="spoiled"]', function() {
    var total = 0;
    total += parseFloat($('#js-trigger-inventory-modal-add-edit input[name="sold"]').val());
    total += parseFloat($('#js-trigger-inventory-modal-add-edit input[name="spoiled"]').val());
    $('input[name^="qty_country"]').each(function() {
        if ($.isNumeric($(this).val())) {
            total += parseFloat($(this).val());
        }
    });
    if ($.isNumeric(total)) {
        $('#js-trigger-inventory-modal-add-edit input[name="total"]').val(total);
    }

    $('#js-trigger-inventory-modal-add-edit input[name="diffrence"]').val(parseFloat($('input[name="quantity"]').val()) - parseFloat($('input[name="total"]').val()));

});



/**--------------------------------------------------------------------------------------
 * PROJECT - INVENTORY MODAL]
 * @description: edit projects inventory
 * -------------------------------------------------------------------------------------*/
function NXInventroyMultiItem() {
    NX.NXInventroyMultiItem();
}

/**--------------------------------------------------------------------------------------
 * SERVICE ORDER ]
 * @description: edit projects inventory
 * -------------------------------------------------------------------------------------*/
function NXServiceOrdersMultiItem() {
    NX.NXServiceOrdersMultiItem();
}

/**--------------------------------------------------------------------------------------
 * EMPLOYEE - PROFILE]
 * @description: update user profile data
 * -------------------------------------------------------------------------------------*/
// function NXProfileBasicInfo() {

//     $("#employeeProfileBasicInfo").validate().destroy();
//     $("#employeeProfileBasicInfo").validate({
//         rules: {
//             first_name: "required",
//             last_name: "required",
//             role : 'required'
//         },
//         submitHandler: function (form) {
//             //ajax form, so initiate ajax request here
//             nxAjaxUxRequest($("#commonModalSubmitButton"));
//         }
//     });
// }

/**--------------------------------------------------------------------------------------
 * PROFILE - UPDATE CHILD QUALIFICATION INDEX]
 * @description: edit projects inventory
 * -------------------------------------------------------------------------------------*/
function NXRemoveChildQualification() {
    $(".child_sn").each(function(index) {
        $(this).text(parseInt(index + 1));
    });
    $(".edu_sn").each(function(index) {
        $(this).text(parseInt(index + 1));
    });
}

/**--------------------------------------------------------------------------------------
 * [DOM - ELEMENT]
 * @description: create and add element
 * -------------------------------------------------------------------------------------*/
function NXCreateElement(configItems, elParent) {
    var newElements = [];

    if (!Array.isArray(configItems)) {
        // if configItems is not an array, and therefore a
        // single config object or string, turn it into
        // a single element array
        configItems = [configItems];
    }

    // If elParent is a string assume it is
    // the id of an element in the page and select it
    if (typeof elParent === 'string') {
        elParent = document.getElementById(elParent);
    }

    configItems.forEach(function(config) {
        var option,
            elChild;
        // if a string is passed in, assume it is
        // the tagName and create a default config object
        if (typeof config === 'string') {
            config = { tag: config };
        }


        elChild = document.createElement(config.tag);

        for (option in config) {
            if (config.hasOwnProperty(option)) {
                switch (option) {
                    case 'tag':
                        // do nothing, already used tag to create new element
                        break;
                    case 'html':
                        // just a shortcut so we don't have to use
                        // innerHTML in our config object
                        elChild.innerHTML = config.html;
                        break;
                    case 'text':
                        // another shortcut
                        elChild.textContent = config.text;
                        break;
                    case 'class':
                        // if we are passed an array convert it to a space delimited string
                        elChild.className = Array.isArray(config.class) ?
                            config.class.join(' ') : config.class;
                        break;
                    default:
                        // if we haven't already handled it, assume it is
                        // an attribute to add to the element
                        elChild.setAttribute(option, config[option]);
                }
            }
        }

        // default text if none was specified
        if (elChild.innerHTML === '') {
            elChild.innerHTML = 'new element';
        }

        newElements.push(elChild);
        elParent.appendChild(elChild);
    });

    // return a reference to the new element(s)
    // in case you want to do something else with it
    // after it was inserted into the document
    // returns a single item or an array depending on how many
    // items you passed it in configItems
    return newElements.length === 1 ? newElements[0] : newElements;
};

function NXSimulate(element, eventName) {
    var options = extend(defaultOptions, arguments[2] || {});
    var oEvent, eventType = null;

    for (var name in eventMatchers) {
        if (eventMatchers[name].test(eventName)) { eventType = name; break; }
    }

    if (!eventType)
        throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

    if (document.createEvent) {
        oEvent = document.createEvent(eventType);
        if (eventType == 'HTMLEvents') {
            oEvent.initEvent(eventName, options.bubbles, options.cancelable);
        } else {
            oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
                options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
                options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
        }
        element.dispatchEvent(oEvent);
    } else {
        options.clientX = options.pointerX;
        options.clientY = options.pointerY;
        var evt = document.createEventObject();
        oEvent = extend(evt, options);
        element.fireEvent('on' + eventName, oEvent);
    }
    return element;
}

function extend(destination, source) {
    for (var property in source)
        destination[property] = source[property];
    return destination;
}

var eventMatchers = {
    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
    'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
}
var defaultOptions = {
    pointerX: 0,
    pointerY: 0,
    button: 0,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    bubbles: true,
    cancelable: true
}

/**--------------------------------------------------------------------------------------
 * [CHAT - FROM REST]
 * @description: reset chat form after send message
 * -------------------------------------------------------------------------------------*/
function NXResetChatForm() {
    $(document).find('#chat-form').trigger("reset");
}

async function NXChatFetch() {
    const chatContainer = document.querySelector('.chat-container');
    // When you have new chat content available or after an AJAX request is completed
    chatContainer.classList.add('fade-out');
    await nxAjaxUxRequest($('#chatReloadButton'));
    // When you have new chat content available or after an AJAX request is completed
    chatContainer.classList.remove('fade-out');
    chatContainer.classList.add('fade-in');
}

/**-------------------------------------------
 * ABS Edging Events
 *
 *--------------------------------------------/


/**--------------------------------------------------------------------------------------
 * [NXcommonSubmit]
 * @description: submit form ajax
 * -------------------------------------------------------------------------------------*/
function NXcommonSubmit() {
$("#commonModalForm").validate().destroy();
$("#commonModalForm").validate({
    rules: {},
    submitHandler: function(form) {
        nxAjaxUxRequest($("#commonModalSubmitButton"));
    }
});
}


/**--------------------------------------------------------------------------------------
 * [User update create]
 * @description: submit form ajax
 * -------------------------------------------------------------------------------------*/
function NXCreateUpdateUser() {
$("#commonModalForm").validate().destroy();
$("#commonModalForm").validate({
    rules: {
        username: "required",
        name: "required",
        email: "required",
        role: "required",
        phone: "required",
        status: "required",
    },
    submitHandler: function(form) {
        nxAjaxUxRequest($("#commonModalSubmitButton"));
    }
});
}

function NXCreateUpdateClient() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            client_name: "required",
            client_email: "required",
            poc_name: "required",
            poc_contact: "required",
            client_address: "required",
            payment_terms: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


function NXCreateUpdateNote() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            client: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

function NXDatatableReload() {
    $('.dataTable').DataTable().ajax.reload();
}

$(document).on("click", '#pageSubmitButton', function() {
    nxAjaxUxRequest($("#pageSubmitButton"));
});


function NXCreateUpdateMachine() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            machine_name: "required",
            brand_name: "required",
            model: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

function NXCreateUpdateProduct() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            name: "required",
            sku_code: "required",
            category_id: "required",
            color_code: "required",
            "price[]": { required: true, number: true },
            "price[acc]": { required: true, number: true },
            "price[hc]": { required: true, number: true },
            "price[tp]": { required: true, number: true },
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * FILTER YAJRA TABLE]
 * @description: filter by datatable column
 * -------------------------------------------------------------------------------------*/
function NXFilterColumn(){
    $("#actionsModalForm").validate().destroy();
    $("#actionsModalForm").validate({
        rules: {
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var table = form.elements['table-id'].value;

            if(typeof window.LaravelDataTables !== 'undefined' && window.LaravelDataTables !== null){
                if (table in window.LaravelDataTables) {
                    var dataTable = window.LaravelDataTables[table];
                }else{
                    var dataTable =  NX.DATATABLE[table]
                }
            }else{
                var dataTable = NX.DATATABLE[table]
            }

            if (dataTable && dataTable.search && dataTable.draw) {
                var filterValues = {};
                var filterInputs = form.elements;

                for (var i = 0; i < filterInputs.length; i++) {
                    var filterInput = filterInputs[i];

                    if (filterInput.classList.contains('filter-input')) {
                        var columnIndex = filterInput.dataset.columnIndex;
                        var filterValue = filterInput.value;

                        if (filterValue) {
                            if (filterValues[columnIndex]) {
                                filterValues[columnIndex] += '|' + filterValue;
                            } else {
                                filterValues[columnIndex] = filterValue;
                            }
                        }
                    }
                }

                var url = document.getElementById(form.elements['filter-button'].value).dataset.url;
                url = url.split('?')[0];
                var filterValuesJson = JSON.stringify(filterValues);
                var encodedfilterValues = encodeURIComponent(filterValuesJson);
                var url = url+'?' + 'filter=' + encodedfilterValues;
                document.getElementById(form.elements['filter-button'].value).dataset.url = url;

                for (var columnIndex in filterValues) {
                    var filterValue = filterValues[columnIndex];
                    dataTable.columns(columnIndex).search(filterValue, true, false);
                }
                dataTable.draw();
            } else {
                console.error('DataTable not found or invalid.');
            }

            $('#actionsModal').modal('hide');
        }
    });
}

/**--------------------------------------------------------------------------------------
 * FILTER YAJRA TABLE]
 * @description: filter by custom column create
 * -------------------------------------------------------------------------------------*/
function NXFilter(){
    $("#actionsModalForm").validate().destroy();
    $("#actionsModalForm").validate({
        rules: {
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var table = form.elements['table-id'].value;

            if(typeof window.LaravelDataTables !== 'undefined' && window.LaravelDataTables !== null){
                if (table in window.LaravelDataTables) {
                    var dataTable = window.LaravelDataTables[table];
                }else{
                    var dataTable =  NX.DATATABLE[table]
                }
            }else{
                var dataTable = NX.DATATABLE[table]
            }

            if (dataTable && dataTable.search && dataTable.draw) {
                var filterInputs = form.elements;
                var filterValues = {};
                var currentURL = dataTable.ajax.url();
                var filter_str = '';
                for (var i = 0; i < filterInputs.length; i++) {
                    if(filterInputs[i].classList.contains('yajra-filter-input')){
                        var columnIndex = filterInputs[i].name;
                        var filterValue = filterInputs[i].value;
                        filterValues[columnIndex] = filterValue;
                        filter_str +=columnIndex+'='+filterValue+'&'
                    }
                }
                if (currentURL.indexOf('?') !== -1) {
                    var op = '&';
                } else {
                    var op = '?';
                }

                //draw table
                dataTable.ajax.url(currentURL+op+filter_str).load();

                //put filter value in open modal url
                var url = document.getElementById(form.elements['filter-button'].value).dataset.url;
                url = url.split('?')[0];
                var filterValuesJson = JSON.stringify(filterValues);
                var encodedfilterValues = encodeURIComponent(filterValuesJson);
                var url = url+'?' + 'filter=' + encodedfilterValues;
                document.getElementById(form.elements['filter-button'].value).dataset.url = url;
            } else {
                console.error('DataTable not found or invalid.');
            }

            $('#actionsModal').modal('hide');
        }
    });
}

function NXCreateUpdateService() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [User update create]
 * @description: submit form ajax
 * -------------------------------------------------------------------------------------*/
function NXCreateUpdateOrder() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            client: "required",
            remarks: "required",
            total_pieces: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

function NXSyncServiceOrderItems() { console.log('Run:')
    var selectedValues = [];
    $('select[name^="selected_item"]').each(function() {
        var $select = $(this);
        var selectedValue = $select.val();
        // $select.empty();

        // $('select[name^="selected_item"]').not($select).find('option').clone().appendTo($select);

        if (selectedValue) {
            selectedValues.push(selectedValue);
        }
    });

    $('select[name^="selected_item"]').each(function() {
        var $select = $(this);

        if (selectedValues.length > 0) {
            $select.find('option').each(function() {
                $(this).prop('disabled', false);
                $select.select2();
                var optionValue = $(this).val();

                if (selectedValues.includes(optionValue) && $select.find(':selected').val() != optionValue ) {
                    $(this).prop('disabled', true);
                }
            });
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [User update create]
 * @description: submit form ajax
 * -------------------------------------------------------------------------------------*/
function NXMarkOrderDeliver() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [Expense update create]
 * @description: submit form ajax
 * -------------------------------------------------------------------------------------*/
function NXCreateUpdateExpense() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            expense_name: "required",
            amount: "required",
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [Expense update create]
 * @description: submit form ajax
 * -------------------------------------------------------------------------------------*/
function NXGenerateAccountStatement() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
        },
        submitHandler: function(form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


