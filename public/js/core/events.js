"use strict";

$(document).ready(function() {
    /** --------------------------------------------------------------------------------------------------
     *  [subscription product - price] - on selecting product, update the price for that product
     *  [notes]
     * --------------------------------------------------------------------------------------------------*/
    //client list has been reset or cleared
    $(document).on("select2:unselecting", ".stripe_product_price", function(e) {
        NX.clientAndProjectsClearToggle($(this));
    });
    //client list has been reset or cleared
    $(document).on("select2:select", ".stripe_product_price", function(e) {
        NX.stripeProductPriceToggle(e, $(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [link on a div]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".click-url", function(e) {
        window.location = $(this).attr("data-url");
    });


    /** --------------------------------------------------------------------------------------------------
     *  [remove preloader]
     * -------------------------------------------------------------------------------------------------*/
    $(".preloader").fadeOut('slow', function() {
        NProgress.done();
    });


    /** --------------------------------------------------------------------------------------------------
     *  prevent events from bubbling down
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-stop-propagation", function(event) {
        event.stopPropagation();
    });


    /** --------------------------------------------------------------------------------------------------
     *  [side filter panel] - toggle
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-toggle-side-panel", function() {
        NX.toggleSidePanel($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [stats widget] - toggle
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-toggle-stats-widget", function() {
        NX.toggleListPagesStatsWidget($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [add user modal] - toggle client options
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-toggle-client-options", function() {
        NX.toggleAddUserClientOptions($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [add item modal button] - reset target form
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".reset-target-modal-form", function() {
        NX.resetTargetModalForm($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [reset filter panel] - resets the form fields
     * ---------------------------------------------------------*/
    $(document).on("click", ".js-reset-filter-side-panel", function() {
        NX.resetFilterPanelFields($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [add clients modal] - toggle address section
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-switch-toggle-hidden-content", function() {
        NX.switchToggleHiddenContent($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [various] - toggle form options
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-toggle-form-options", function() {
        NX.toggleFormOptions($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [pushstate] - change url in address bar
     * -------------------------------------------------------------------------------------------------*/
    $(".project-top-nav").on("click", 'a', function() {
        NX.expandTabbedPage($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [add-edit-project] - add or edit project button clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.add-edit-project-button', function() {
        NX.addEditProjectButton($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [update user preference] - e.g. leftmenu, stats
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.update-user-ux-preferences', function() {
        NX.updateUserUXPreferences($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [apply filter button] - button clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.apply-filter-button', function() {
        NX.applyFilterButton($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [lists main checkbox] - checkbox clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("change", '.listcheckbox-all', function() {
        NX.listCheckboxAll($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [lists main checkbox] - checkbox clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("change", '.listcheckbox', function() {
        NX.listCheckbox($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [clients & projects] - on selecting clients, update the projects dropdown to show clients projects
     *  [notes]
     *       - the clients dropdown must have the following
     *       - class="clients_and_projects_toggle"
     *       - data-projects-dropdown="id-of-the-projects-dropdown"
     *       - data-feed-request-type="filter_tickets" (as checked in feed controller)
     * --------------------------------------------------------------------------------------------------*/
    //client list has been reset or cleared
    $(document).on("select2:unselecting", ".clients_and_projects_toggle", function(e) {
        NX.clientAndProjectsClearToggle($(this));
    });
    //client list has been reset or cleared
    $(document).on("select2:select", ".clients_and_projects_toggle", function(e) {
        NX.clientAndProjectsToggle(e, $(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [project & users] - on selecting project, update users dropdown to show assigned users
     *  [notes]
     *       - the projects dropdown must have the following
     *       - class="projects_assigned_toggle"
     *       - data-assigned-dropdown="id-of-the-users-dropdown"
     * --------------------------------------------------------------------------------------------------*/
    //client list has been reset or cleared
    $(document).on("select2:unselecting", ".projects_assigned_toggle", function(e) {
        NX.projectsAndAssignedClearToggle($(this));
    });
    //client list has been reset or cleared
    $(document).on("select2:select", ".projects_assigned_toggle", function(e) {
        NX.projectAndAssignedCToggle(e, $(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [project & users] - on selecting project, update users dropdown to show assigned users
     *  [notes]
     *       - the projects dropdown must have the following
     *       - class="projects_assigned_toggle"
     *       - data-assigned-dropdown="id-of-the-users-dropdown"
     * --------------------------------------------------------------------------------------------------*/
    //client list has been reset or cleared
    $(document).on("select2:unselecting", ".projects_managers_toggle", function(e) {
        NX.projectsManagersClearToggle($(this));
    });
    //client list has been reset or cleared
    $(document).on("select2:select", ".projects_managers_toggle", function(e) {
        NX.projectManagersCToggle(e, $(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [project & users] - on selecting project, update tasks dropdown to show  tasks
     *  [notes]
     *       - the projects dropdown must have the following
     *       - class="projects_my_tasks_toggle"
     *       - data-task-dropdown="id-of-the-task-dropdown"
     * --------------------------------------------------------------------------------------------------*/
    //project list has been reset or cleared
    $(document).on("select2:unselecting", ".projects_my_tasks_toggle", function(e) {
        //toggle task drop down
        NX.projectsTasksClearToggle($(this));
        //reset fields and button (for manual timer recording)
        NX.recordTaskTimeToggle('disable');
    });
    //projecct list - a project has been selected
    $(document).on("select2:select", ".projects_my_tasks_toggle", function(e) {
        //populate the tasks dropdown
        NX.projectAssignedTasksToggle(e, $(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [toggle ticket editor or view mode]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.ticket-editor-toggle', function() {
        NX.ticketEditorToggle($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [default category icon] clicked - show alert
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".js-system-default-category", function() {
        $.confirm({
            theme: 'modern',
            type: 'blue',
            title: NXLANG.default_category,
            content: NXLANG.system_default_category_cannot_be_deleted,
            buttons: {
                cancel: {
                    text: NXLANG.close,
                    btnClass: ' btn-sm btn-outline-info',
                },
            },
        });
    });

    /** --------------------------------------------------------------------------------------------------
     *  [toggle ticket editor or view mode]
     * -------------------------------------------------------------------------------------------------*/
    $(document).ready(function() {
        $(document).on({
            mouseenter: function() {
                $(this).find('.js-hover-actions-target').show();
            },
            mouseleave: function() {
                $(this).find('.js-hover-actions-target').hide();
            }
        }, ".js-hover-actions");
    });



    /** --------------------------------------------------------------------------------------------------
     *  [general placeholder clicked]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-toggle-placeholder-element', function() {
        NX.togglePlaceHolders($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [general close button clicked]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-toggle-close-button', function() {
        NX.toggleCloseButtonElements($(this));
    });


    /** ----------------------------------------------------------
     *  [jquery.confirm]  
     *     - form fields in confirm dialogue (such as check boxes)
     * -----------------------------------------------------------*/
    //set the form value to the actual form value
    $(document).on('change', '.confirm_action_checkbox', function() {
        //hidden field
        $field = $("#" + $(this).attr('data-field-id'));
        if ($(this).is(':checked')) {
            $field.val('on')
        } else {
            $field.val('')
        }
    });


    /** --------------------------------------------------------------------------------------------------
     *  [task - checklist text clicked]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-card-checklist-toggle', function(e) {
        e.preventDefault();
        //toggle
        NX.toggleEditTaskChecklist($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [task - reset the task form]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.reset-card-modal-form', function(e) {
        NX.resetCardModal($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [task - checklist text clicked]
     * -------------------------------------------------------------------------------------------------*/
    $(document).ready(function() {
        $(document).on({
            mouseenter: function() {
                //hide all
                $('.checklist-item-delete').hide();
                $(this).find('.checklist-item-delete').show();
            },
            mouseleave: function() {
                $('.checklist-item-delete').hide();
            }
        }, ".checklist-item");
    });


    /** --------------------------------------------------------------------------------------------------
     *  [better ux on delete items] - remove item from list whilst the ajax happend in background
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-delete-ux', function() {
        NX.uxDeleteItem($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [toggle task timer buttons]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-timer-button', function(e) {
        NX.toggleTaskTimer($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [toggle settings left menu]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-toggle-settings-menu', function(e) {
        NX.toggleSettingsLeftMenu($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [convert lead to a customer]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", '.js-lead-convert-to-customer', function(e) {
        NX.convertLeadForm($(this), 'show');
    });
    $(document).on("click", '.js-lead-convert-to-customer-close', function(e) {
        NX.convertLeadForm($(this), 'hide');
    });


    /** --------------------------------------------------------------------------------------------------
     *  [top nav events - show]
     * -------------------------------------------------------------------------------------------------*/
    $("#topnav-notification-dropdown").on("show.bs.dropdown", function(e) {
        NX.eventsTopNav($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [top nav events - mark all events as read]
     * -------------------------------------------------------------------------------------------------*/
    $("#topnav-notification-mark-all-read").on('click', function(e) {
        NX.eventsTopNavMarkAllRead($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [top nav events - mark as read]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click',".topnav-notification-mark-as-read", function(e) {
        NX.eventsTopNavMarkAsRead($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [top nav events - mark one event as read]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-notification-mark-read-single', function(e) {
        NX.eventsMarkRead($(this), 'single');
    });

    /** --------------------------------------------------------------------------------------------------
     *  [app][change dynamic urls]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-dynamic-url', function(e) {
        NX.browserPushState($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [settings][change dynamic urls]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.js-dynamic-settings-url', function(e) {
        //set the dynamic url
        var self = $(this);
        var url = self.attr('data-slug');
        var dynamic_url = 'app' + url;
        // var dynamic_url = url;
        self.attr('data-dynamic-url', dynamic_url);
        //update browser address bar
        NX.browserPushState($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [settings][email template selected]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('change', '#selectEmailTemplate', function(e) {
        NX.loadEmailTemplate($(this));
    });



    /** --------------------------------------------------------------------------------------------------
     *  [settings][clear cache]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '#clearSystemCache', function(e) {
        NX.clearSystemCache($(this));
    });

    /** --------------------------------------------------------------------------------------------------
     *  [settings][clear cache]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.toggle-invoice-tax', function(e) {
        NX.toggleInvoiceTaxEditing($(this));
    });


    /** ------------------------------------------------------------------------------
     *  - close any other static popover windows
     * ------------------------------------------------------------------------------- */
    $(document).on('click', '.js-elements-popover-button', function() {
        $('.js-elements-popover-button').not(this).popover('hide');
    });

    /** ---------------------------------------------------
     *  Show a popover with dynamic html content
     *  - html content is set in a hidden div
     *  - button has id of hidden div
     *  <button class="btn btn-info btn-sm js-dynamic-popover-button" tabindex="0" 
     *          data-popover-content="--html entities endoced (php) html here---"
     *          data-placement="top"
     *          data-title="Taxes Rates~"> Tax Rates~ </button>
     * -------------------------------------------------- */
    $(document).find(".js-elements-popover-button").each(function() {
        $(this).popover({
            html: true,
            sanitize: false, //The HTML is NOT user generated
            template: NX.basic_popover_template,
            title: $(this).data('title'),
            content: function() {
                //popover elemenet
                var str = $(this).attr('data-popover-content');
                //decode html entities
                return $("<div/>").html(str).text();
            }
        });
    });

    /** --------------------------------------------------------------------------------------------------
     *  [paypal] payment opion selected
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '#invoice-make-payment-button', function(e) {
        $("#invoice-buttons-container").hide();
        $("#invoice-pay-container").show();
    });



    /** --------------------------------------------------------------------------------------------------
     *  [paynow] cancel payment button clicked
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '#invoice-cancel-payment-button', function(e) {
        $("#invoice-pay-container").hide();
        $(".payment-gateways").hide();
        $("#invoice-buttons-container").show();
    });


    /** --------------------------------------------------------------------------------------------------
     *  [paypal] payment opion selected
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.invoice-pay-gateway-selector', function(e) {
        NX.selectPaymentGateway($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [disable button on click]
     *  disable the button on click
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.disable-on-click', function(e) {
        $(this).prop("disabled", true);
        $(this).addClass('button-loading-annimation');
    });

    /** --------------------------------------------------------------------------------------------------
     *  [disable button on click]
     *  disable a button on click and add the loading annimation. Good for Stripe and Payal buttons etc
     *  [IMPORTANT] do not use this class on ajax buttons. They have their own data-property for this
     *              using this will prevent ajax form submitting
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.disable-on-click-loading', function(e) {

        $(this).prop("disabled", true); //this is stopping form submits
        $(this).addClass('button-loading-annimation');
    });


    /** --------------------------------------------------------------------------------------------------
     *  [disable button on click]
     *  disable a button, change to please wait, add loading annimation
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('click', '.disable-on-click-please-wait', function(e) {
        $(this).html('&nbsp;&nbsp;&nbsp;' + NXLANG.please_wait + '...&nbsp;&nbsp;&nbsp;');
        $(this).prop("disabled", true); //this is stopping form submits
        $(this).addClass('button-loading-annimation');
    });



    /** --------------------------------------------------------------------------------------------------
     *  [shipping address] same as billing address
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", "#same_as_billing_address", function() {
        NX.shippingAddressSameBilling($(this));
    });


    /** --------------------------------------------------------------------------------------------------
     *  [toggle menu clicked] - update the autoscroll bar
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".main-hamburger-menu", function() {
        if (typeof navleft != 'undefined') {
            navLeftScroll.update();
        }
        //reset menu tooltips
        NXleftMenuToolTips();
    });
    $(document).on("click", ".settings-hamburger-menu", function() {
        if (typeof navleft != 'undefined') {
            navLeftScroll.update();
        }
    });




    /** --------------------------------------------------------------------------------------------------
     *  [select2] clear validation.js errors (if any) on selecting drop down
     * -------------------------------------------------------------------------------------------------*/
    $(document).on('select2:select', '.select2-hidden-accessible', function() {
        try {
            if ($(this).valid()) {
                $(this).next('span').removeClass('error').addClass('valid');
            }
        } catch (err) {
            //we are expecting this error for none validated select2 elements. nothing to do here.
        }
    });


    /** --------------------------------------------------------------------------------------------------
     *  toggle target element
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".toggle-collapse", function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        if (target != '') {
            $(target).toggle();
        }
    });



    /*----------------------------------------------------------------
     *  [tax button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#billing-tax-popover-button', function(e) {
        //is the tax button enabled?
        if ($(this).hasClass('disabled')) {
            $(this).popover('hide');
            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.add_lineitem_items_first
            });
            NXINVOICE.log('[invoicing] initialiseTaxPopover() - tax button is disabled');
        } else {
            NXINVOICE.toggleTaxDom($("#bill_tax_type").val());
        }
    });

    /*----------------------------------------------------------------
     *  [tax type] - tax type drop down has been changed
     *---------------------------------------------------------------*/
    $(document).on('change', '#billing-tax-type', function() {
        NXINVOICE.toggleTaxDom($(this).val());
    });


    /*----------------------------------------------------------------
     *  [tax popover - submit button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#billing-tax-popover-update', function(e) {
        //update tax type
        NXINVOICE.updateTax();
    });



    /*----------------------------------------------------------------
     *  [adjustments button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#billing-adjustment-popover-button', function() {
        NXINVOICE.toggleAdjustmentDom();
    });

    /*----------------------------------------------------------------
     *  [adjustments popover - submit button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#billing-adjustment-popover-update', function(e) {
        //update tax type
        NXINVOICE.updateAdjustment();
    });

    /*----------------------------------------------------------------
     *  [adjustments popover - remove button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#billing-adjustment-popover-remove', function(e) {
        //update tax type
        NXINVOICE.removeAdjustment();
    });


    /*----------------------------------------------------------------
     *  [discount button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#billing-discounts-popover-button', function() {
        //is the discounts button enabled?
        if ($(this).hasClass('disabled')) {
            $(this).popover('hide');
            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.add_lineitem_items_first
            });
        } else {
            NXINVOICE.toggleDiscountDom($("#bill_discount_type").val());
        }
    });


    /*----------------------------------------------------------------
     *  [discount type] - tax type drop down has been changed
     * ------------------------------------------------------------*/
    $(document).on('change', '#js-billing-discount-type', function() {
        NXINVOICE.toggleDiscountDom($(this).val());
    });


    /*----------------------------------------------------------------
     *  [discount popover - submit button] - clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#billing-discount-popover-update', function(e) {
        //update tax type
        NXINVOICE.updateDiscount();
    });


    /*----------------------------------------------------------------
     *  [line item] - add new blank line button has been clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#billing-item-actions-blank', function(e) {
        NXINVOICE.DOM.itemNewLine();
    });


    /*----------------------------------------------------------------
     *  [line item] - add new blank line button has been clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#billing-time-actions-blank', function(e) {
        NXINVOICE.DOM.timeNewLine();
    });


    /*----------------------------------------------------------------
     *  [line item] - delete line button has been clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '.js-billing-line-item-delete', function(e) {
        NXINVOICE.DOM.deleteLine($(this));
    });


    /*----------------------------------------------------------------
     *  [bill item] - add selected bill items button clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#itemsModalSelectButton', function(e) {
        NXINVOICE.DOM.addSelectedProductItems($(this));
    });


    /*----------------------------------------------------------------
     *  [epxense item] - add selected expenses button clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#expensesModalSelectButton', function(e) {
        NXINVOICE.DOM.addSelectedExpense($(this));
    });



    /*----------------------------------------------------------------
     *  [epxense item] - add time billing button clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#timebillingModalSelectButton', function(e) {
        NXINVOICE.DOM.addSelectedTimebilling($(this));
    });


    /*----------------------------------------------------------------
     *  [tax type] - tax type drop down has been changed
     * ------------------------------------------------------------*/
    $(document).on('change keyup input paste', '.calculation-element', function() {
        NXINVOICE.CALC.reclaculateBill(self);
    });


    /*----------------------------------------------------------------
     *  [deciamls] keep 2 deciaml places for all number fields
     * -----------------------------------------------------------*/
    $(".decimal-field").blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });


    /*----------------------------------------------------------------
     *  [save changes]
     * ------------------------------------------------------------*/
    $(document).on("click", '#billing-save-button', function() {
        NXINVOICE.CALC.saveBill($(this));
    });


    /*----------------------------------------------------------------
     *  [tax type] - tax type drop down has been changed
     * ------------------------------------------------------------*/
    $(document).on('change keyup input paste', '.js_line_validation_item', function() {
        NXINVOICE.DOM.revalidateItem($(this));
    });



    /** --------------------------------------------------------------------------------------------------
     *  [card assigned user - add button clicked]
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", ".card-task-assigned, .card-lead-assigned", function(e) {
        NXCardsAssignedSelect();
    });

    /*----------------------------------------------------------------
     *  [paynow] button clicked
     * -----------------------------------------------------------*/
    $(document).on('click', '#invoice-make-payment-button', function(e) {
        $("#invoice-buttons-container").hide();
        $("#invoice-pay-container").show();
    });

    //prevent event dropwon from closing on click event
    $(document).on('click', '.top-nav-events', function(e) {
        e.stopPropagation();
    });

    //prevent event dropwon from closing on click event
    $(document).on('click', '.js-do-not-close-on-click', function(e) {
        e.stopPropagation();
    });

    //prevent event dropwon from closing on click of selecte2 on topnav timer
    $(document).on('click', '.js-do-not-close-on-click > .select2-search__field', function(e) {
        e.stopPropagation();
    });



    /*----------------------------------------------------------------
     *  show plan modal window
     * -----------------------------------------------------------*/
    $(document).on('click', '.show-modal-button', function() {
        var title = $(this).attr('data-modal-title');
        //change title (if applicable)
        $("#plainModalTitle").html(title);
        //reset body
        $("#plainModalBody").html('');
        //modal size (modal-lg | modal-sm | modal-xl)
        var modal_size = $(this).attr('data-modal-size');
        if (modal_size == '' || modal_size == null) {
            modal_size = 'modal-lg';
        }
        //set modal size
        $("#plainModalContainer").addClass(modal_size);
    });


    /*----------------------------------------------------------------
     *  show common modal window. This function set the ajax attr
     *  for for the modal window, that has been triggered by a button
     * 
     * -----------------------------------------------------------*/
    $(document).on('click', '.edit-add-modal-button', function() {

        //variables
        var url = $(this).attr('data-url');
        var modal_title = $(this).attr('data-modal-title');
        var action_url = $(this).attr('data-action-url');
        var action_class = $(this).attr('data-action-ajax-class');
        var action_loading_target = $(this).attr('data-action-ajax-loading-target');
        var action_method = $(this).attr('data-action-method');
        var action_type = $(this).attr('data-action-type');
        var action_form_id = $(this).attr('data-action-form-id');
        var add_class = $(this).attr('data-add-class');
        var action_button_text = $(this).attr('data-save-button-text');
        var close_button_text = $(this).attr('data-close-button-text');
        var top_padding = $(this).attr('data-top-padding'); //set to 'none'
        var bg_color = $(this).attr('data-modal-bg-color');


        //modal-lg modal-sm modal-xl modal-xs
        var modal_size = $(this).attr('data-modal-size');
        if (modal_size == '' || modal_size == null) {
            modal_size = 'modal-lg';
        }

        //objects
        var $button = $("#commonModalSubmitButton");
        //objects
        var $closeButton = $("#commonModalCloseButton");

        //enable button - incase it was previously disable by another function
        $button.prop("disabled", false);

        //set modal size
        $("#commonModalContainer").removeClass('modal-lg modal-sm modal-xl modal-xs');
        $("#commonModalContainer").addClass(modal_size);

        //update form style
        var form_style = $(this).attr('data-form-design');
        if (form_style != '') {
            //remove previous styles
            $("#commonModalForm").removeClass('form-material')
            $("#commonModalForm").addClass(form_style)
        }

        //add custom class
        if (add_class != '') {
            $("#commonModalContainer").addClass(add_class);
        }

        //add heading bg_color
        if (typeof bg_color != 'undefined' && bg_color != null) {
            $("#commonModalHeader").css('background-color', bg_color);
            $("#commonModalTitle").css('color', '#fff');
            $("#commonModalCloseIcon").css('color', '#fff');
        }
        //  else {
        //     $("#commonModalHeader").css('background-color', '#fbfcfd');
        //     $("#commonModalTitle").css('color', '#3751FF');
        //     $("#commonModalCloseIcon").css('color', '#000');
        // }

        //change title
        $("#commonModalTitle").html(modal_title);
        //reset body
        $("#commonModalBody").html('');
        //hide footer
        $("#commonModalFooter").hide();
        //change form action
        $("#commonModalForm").attr('action', action_url);


        //[submit button] - reset
        $button.show();
        $button.removeClass('js-ajax-ux-request');
        if (action_class != 'd-none') {
            $button.removeClass('d-none');
        }
        $button.addClass(action_class);

        //defaults
        $("#commonModalHeader").show();
        $("#commonModalFooter").show();
        $("#commonModalCloseButton").show();
        $("#commonModalCloseIcon").show();
        $("#commonModalExtraCloseIcon").hide();

        //hidden elements
        if ($(this).attr('data-header-visibility') == 'hidden') {
            $("#commonModalHeader").hide();
        }
        if ($(this).attr('data-footer-visibility') == 'hidden') {
            $("#commonModalFooter").hide();
        }
        if ($(this).attr('data-close-button-visibility') == 'hidden') {
            $("#commonModalCloseButton").hide();
        }
        if ($(this).attr('data-header-close-icon') == 'hidden') {
            $("#commonModalCloseIcon").hide();
        }
        if ($(this).attr('data-header-extra-close-icon') == 'visible') {
            $("#commonModalExtraCloseIcon").show();
        }

        //remove top padding
        if (top_padding == 'none') {
            $("#commonModalBody").addClass('p-t-0');
        } else {
            $("#commonModalBody").removeClass('p-t-0');
        }

        //[submit button] - update attributes etc (if provided)
        //$button.addClass(action_class);
        $button.attr('data-url', action_url);
        $button.attr('data-loading-target', action_loading_target);
        $button.attr('data-ajax-type', action_method);

        //form post
        if (action_type == "form") {
            $button.attr('data-type', 'form');
            $button.attr('data-form-id', action_form_id);
        }

        //submit button text
        if(action_button_text != ''){
            $button.text(action_button_text);
        }

        //close button text
        if(close_button_text != ''){
            $closeButton.text(close_button_text);
        }
    });


    /*----------------------------------------------------------------
     *  show actions modal window - action modal
     * -----------------------------------------------------------*/
    $(document).on('click', '.actions-modal-button', function() {

        //variables
        var url = $(this).attr('data-url');
        var modal_title = $(this).attr('data-modal-title');
        var action_url = $(this).attr('data-action-url');
        var action_method = $(this).attr('data-action-method');
        var add_body_class = $(this).attr('data-body-class');
        var action_button_text = $(this).attr('data-save-button-text');
        var close_button_text = $(this).attr('data-close-button-text');


        //additional variable
        var action_type = $(this).attr('data-action-type');
        var action_form_id = $(this).attr('data-action-form-id');

        //add class to modal body
        if (add_body_class != '') {
            $("#actionsModalBody").addClass(add_body_class);
        }

        //objects
        var $button = $("#actionsModalButton");
        //objects
        var $closeButton = $("#actionsModalCloseButton");

        //change title
        $("#actionsModalTitle").html(modal_title);
        //reset body
        $("#actionsModalBody").html('');
        //hide footer
        $("#actionsModalFooter").hide();

        //$button.addClass(action_class);
        $button.attr('data-url', action_url);
        $button.attr('data-ajax-type', action_method);
        $button.attr('data-type', action_type);
        $button.attr('data-form-id', action_form_id);
        $button.attr('data-skip-checkboxes-reset', true);
        
        //submit button text
        if(action_button_text != ''){
            $button.text(action_button_text);
        }

        //close button text
        if(close_button_text != ''){
            $closeButton.text(close_button_text);
        }
    });


    /*----------------------------------------------------------------
     * show category hover button
     *--------------------------------------------------------------*/
    $(document).on('mouseover', '.kb-category', function() {
        $(this).find(".kb-hover-icons").show();
    });
    $(document).on('mouseout', '.kb-category', function() {
        $(this).find(".kb-hover-icons").hide();
    });


    /*----------------------------------------------------------------
     * Better ux on teaks checkbox click
     *--------------------------------------------------------------*/
    $(document).on('click', '.toggle_task_status', function() {
        var parentid = $(this).attr('data-container');
        var parent = $("#" + parentid);
        if ($(this).prop("checked") == true) {
            parent.addClass('task-completed');
        } else {
            parent.removeClass('task-completed');
        }
    });


    /** --------------------------------------------------------------------------------------------------
     *  toggle tasks and leads custom fields
     * -------------------------------------------------------------------------------------------------*/
    $(document).on("click", "#custom-fields-panel-edit-button", function(e) {
        e.preventDefault();
        $(".custom-fields-panel-edit").hide();
        $("#custom-fields-panel").show();
    });
    $(document).on("click", "#custom-fields-panel-close-button", function(e) {
        e.preventDefault();
        $("#custom-fields-panel").hide();
        $(".custom-fields-panel-edit").show();
    });





    /*----------------------------------------------------------------
     *  create modal - start
     * -----------------------------------------------------------*/
    $(document).on('click', '.create-modal-button', function() {


        //set modal splash message
        $("#create-modal-splash-text").html($(this).attr('data-splash-text'));

        //set create url
        $("#create-new-client-button").attr('data-url', $(this).attr('data-new-client-url'));

        //show defauly set
        $(".create-modal-option-contaiers").hide();
        $("#option-existing-client-container").show();


    });


    /*----------------------------------------------------------------
     *  create modal - selector
     * -----------------------------------------------------------*/
    $(document).on('click', '.create-modal-selector', function() {

        var target_containter = $(this).attr('data-target-container');

        //hide all containers
        $(".create-modal-option-contaiers").hide();

        //show the target container
        $("#" + target_containter).show();

    });

    /*----------------------------------------------------------------
     *  inventory selector
     * -----------------------------------------------------------*/
    $(document).on('click', '.item-add-more', function() {

        var target_containter = $(this).attr('data-target-container');

        var values = $("input[name='item_id[]']")
            .map(function() {
                return $(this).val();
            }).get();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: $('#add-more-inventory_item').data('url'),
            dataType: 'json',
            data: {
                id: $('#category_id').val(),
                selected_item: values.filter(n => n).toString()
            },
            success: function(data) {
                NX.notification({
                    type: "success",
                    message: 'Updated Vendor Info',
                });
                NProgress.set(1);
            }
        });


        //hide all containers
        $(".client-selector-container").hide();
        $(".client-type-selector").removeClass('active');

        //set clients election type
        $("#client-selection-type").val($(this).attr('data-type'))

        //show the target container
        $("#" + target_containter).show();
        $(this).addClass('active');


    });

    /*----------------------------------------------------------------
     *  create modal - selector
     * -----------------------------------------------------------*/
    $(document).on('click', '.client-type-selector', function() {

        var target_containter = $(this).attr('data-target-container');

        //hide all containers
        $(".client-selector-container").hide();
        $(".client-type-selector").removeClass('active');

        //set clients election type
        $("#client-selection-type").val($(this).attr('data-type'))

        //show the target container
        $("#" + target_containter).show();
        $(this).addClass('active');


    });


    /*----------------------------------------------------------------
     *  [stop topnav timer] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#my-timer-time-topnav-stop-button', function(e) {
        //hide timer
        $("#my-timer-container-topnav").hide();
    });

    /*----------------------------------------------------------------
     *  create modal - selector
     * -----------------------------------------------------------*/
    $(document).on('click', '.vendor-type-selector', function() {

        var target_containter = $(this).attr('data-target-container');

        //hide all containers
        $(".vendor-selector-container").hide();
        $(".vendor-type-selector").removeClass('active');

        //set vendors election type
        $("#vendor-selection-type").val($(this).attr('data-type'))

        //show the target container
        $("#" + target_containter).show();
        $(this).addClass('active');
    });

    /*----------------------------------------------------------------
     *  [tax button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-tax-popover-button', function(e) {
        //is the tax button enabled?
        if ($(this).hasClass('disabled')) {
            $(this).popover('hide');
            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.add_lineitem_items_first
            });
            NXPURCHASEORDER.log('[purchase-order] initialiseTaxPopover() - tax button is disabled');
        } else {
            NXPURCHASEORDER.toggleTaxDom($("#po_tax_type").val());
        }
    });

    /*----------------------------------------------------------------
     *  [tax type] - tax type drop down has been changed
     *---------------------------------------------------------------*/
    $(document).on('change', '#po-tax-type', function() {
        NXPURCHASEORDER.toggleTaxDom($(this).val());
    });


    /*----------------------------------------------------------------
     *  [tax popover - submit button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-tax-popover-update', function(e) {
        //update tax type
        NXPURCHASEORDER.updateTax();
    });



    /*----------------------------------------------------------------
     *  [adjustments button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-adjustment-popover-button', function() {
        NXPURCHASEORDER.toggleAdjustmentDom();
    });

    /*----------------------------------------------------------------
     *  [adjustments popover - submit button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-adjustment-popover-update', function(e) {
        //update tax type
        NXPURCHASEORDER.updateAdjustment();
    });

    /*----------------------------------------------------------------
     *  [adjustments popover - remove button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-adjustment-popover-remove', function(e) {
        //update tax type
        NXPURCHASEORDER.removeAdjustment();
    });

    /*----------------------------------------------------------------
     *  [freight button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-freight-popover-button', function() {
        NXPURCHASEORDER.toggleFreightDom();
    });

    /*----------------------------------------------------------------
     *  [freight popover - submit button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-freight-popover-update', function(e) {
        //update tax type
        NXPURCHASEORDER.updateFreight();
    });

    /*----------------------------------------------------------------
     *  [freight popover - remove button] - clicked
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-freight-popover-remove', function(e) {
        //update tax type
        NXPURCHASEORDER.removeFreight();
    });


    /*----------------------------------------------------------------
     *  [discount button clicked] - set popover dom
     *---------------------------------------------------------------*/
    $(document).on('click', '#po-discounts-popover-button', function() {
        //is the discounts button enabled?
        if ($(this).hasClass('disabled')) {
            $(this).popover('hide');
            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.add_lineitem_items_first
            });
        } else {
            NXPURCHASEORDER.toggleDiscountDom($("#po_discount_type").val());
        }
    });


    /*----------------------------------------------------------------
     *  [discount type] - tax type drop down has been changed
     * ------------------------------------------------------------*/
    $(document).on('change', '#js-po-discount-type', function() {
        NXPURCHASEORDER.toggleDiscountDom($(this).val());
    });


    /*----------------------------------------------------------------
     *  [discount popover - submit button] - clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#po-discount-popover-update', function(e) {
        //update tax type
        NXPURCHASEORDER.updateDiscount();
    });


    /*----------------------------------------------------------------
     *  [line item] - add new blank line button has been clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#po-item-actions-blank', function(e) {
        NXPURCHASEORDER.DOM.itemNewLine();
    });


    /*----------------------------------------------------------------
     *  [line item] - add new blank line button has been clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#po-time-actions-blank', function(e) {
        NXPURCHASEORDER.DOM.timeNewLine();
    });


    /*----------------------------------------------------------------
     *  [line item] - delete line button has been clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '.js-po-line-item-delete', function(e) {
        NXPURCHASEORDER.DOM.deleteLine($(this));
    });


    /*----------------------------------------------------------------
     *  [bill item] - add selected bill items button clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#itemsPoModalSelectButton', function(e) {
        NXPURCHASEORDER.DOM.addSelectedProductItems($(this));
    });


    /*----------------------------------------------------------------
     *  [epxense item] - add selected expenses button clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#expensesModalSelectButton', function(e) {
        NXPURCHASEORDER.DOM.addSelectedExpense($(this));
    });



    /*----------------------------------------------------------------
     *  [epxense item] - add time billing button clicked
     * ------------------------------------------------------------*/
    $(document).on('click', '#timebillingModalSelectButton', function(e) {
        NXPURCHASEORDER.DOM.addSelectedTimebilling($(this));
    });


    /*----------------------------------------------------------------
     *  [tax type] - tax type drop down has been changed
     * ------------------------------------------------------------*/
    $(document).on('change keyup input paste', '.calculation-element', function() {
        NXPURCHASEORDER.CALC.reclaculateBill(self);
    });


    /*----------------------------------------------------------------
     *  [save changes]
     * ------------------------------------------------------------*/
    $(document).on("click", '#po-save-button', function() {
        NXPURCHASEORDER.CALC.saveBill($(this));
    });


    /*----------------------------------------------------------------
     *  [tax type] - tax type drop down has been changed
     * ------------------------------------------------------------*/
    $(document).on('change keyup input paste', '.js_line_validation_item', function() {
        NXPURCHASEORDER.DOM.revalidateItem($(this));
    });

    // $(document).on("click", '#vendor-edit-info', function () {
    //     $('#edit-vendor-info').hide();
    //     $('#update-vendor-info').show();
    // });

    $(document).on("click", '#addvendorrow', function() {
        $('#btn_div').before("<div class='col-lg-4 col-sm-12 offset-lg-3'><br /><div class='input-group'><input name='payment_info[]' type='text' class='form-control form-control-sm' placeholder='Payment Info'><div class='input-group-append'><button class='btn btn-danger btn-sm' type='button' onclick='removeVendorRow(this)'><i class='sl-icon-trash'></i></button></div></div></div>");
    });

    $(document).on("click", '#vendor-update-info', function() {
        NProgress.set(0.5);
        var values = $("input[name='payment_info[]']")
            .map(function() {
                return $(this).val();
            }).get();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: $('#vendor-update-info').data('url'),
            dataType: 'json',
            data: {
                id: $('#vendor_id').val(),
                payment_info: values.filter(n => n).toString()
            },
            success: function(data) {
                NX.notification({
                    type: "success",
                    message: 'Updated Vendor Info',
                });
                NProgress.set(1);
            }
        });
    });

    /**--------------------------------------
     * remove element
     *---------------------------------------*/
    //remove an element
    $(document).on('click', '.js-remove-parent', function() {
        $(this).closest('.' + $(this).data('parent')).slideUp("slow");
        $(this).closest('.' + $(this).data('parent')).remove();
        NX.NXInventroyMultiItem();
        NX.NXServiceOrdersMultiItem();
        NXSyncServiceOrderItems();
    });

    $(document).on("click", '.convert-project-cencel-button', function() {
        history.go(0);
    });
});


/*----------------------------------------------------------------
    *  [user table filter]
    * ------------------------------------------------------------*/
$(document).on("click", '#user-filter-button', function() {
    window.LaravelDataTables["users-table"].draw();
});

/*----------------------------------------------------------------
    *  [yajra table custom filter]
    * ------------------------------------------------------------*/
$(document).on("change", '.yajra-custom-filter', function() {
    var table = $(this).data('table-id');
    var columnIndex = $(this).attr('name');
    var filterValue = $(this).val();

    if(typeof window.LaravelDataTables !== 'undefined' && window.LaravelDataTables !== null){
        if (table in window.LaravelDataTables) {
            var dataTable = window.LaravelDataTables[table];
        }else{
            var dataTable =  NX.DATATABLE[table]
        }
    }else{
        var dataTable = NX.DATATABLE[table]
    }

    console.log(NX.DATATABLE)
    if (dataTable && dataTable.search && dataTable.draw) {
        var currentURL = dataTable.ajax.url();
        var paramToAdd = columnIndex + '=' + filterValue;

        // Check if the parameter already exists in the URL
        if (currentURL.indexOf(columnIndex) !== -1) {
            // Parameter exists, so replace its value
            var regex = new RegExp('(' + columnIndex + '=.*?)(&|$)', 'g');
            currentURL = currentURL.replace(regex, paramToAdd + '&');
        } else {
            // Parameter doesn't exist, so add it
            if (currentURL.indexOf('?') !== -1) {
                currentURL += '&' + paramToAdd;
            } else {
                currentURL += '?' + paramToAdd;
            }
        }

        dataTable.ajax.url(currentURL).load();
    }else{
        console.error('DataTable not found or invalid.');
    }
});

/*----------------------------------------------------------------
    *  [user table filter]
    * ------------------------------------------------------------*/
$(document).on("change", '.file-upload-ajax', function() {

    var formData = new FormData();
    var $this = $(this);
    var fileInput = $(this)[0];
    if (fileInput.files.length > 0) {
        var file = fileInput.files[0];
        formData.append('file', fileInput.files[0]);

        if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
            NX.notification({type:'error',message: 'File format not allowed'})
            return
        }
        console.log(file.size)
        if (file.size > (1024 * 1024) * 5) {
            NX.notification({type:'error',message: 'File size should be up to 5MB only'})
            return
        }  
    }
    
    $.ajax({
        url: NX.base_url+'/fileUpload',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: formData,
        processData: false,  // Prevent jQuery from processing data
        contentType: false,
        beforeSend: function (xhr) {
            $("#new-loader").addClass('show');
        }, // Prevent jQuery from setting content type
        success: function (response) {
            if(response.success){
                $this.siblings('.attachments').remove();
                var html = '<input type="hidden" class="attachments" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">';
                $this.parent().append(html);
            }
            setTimeout(function(){
                $("#new-loader").removeClass('show');
            });
        },
        error: function (xhr, status, error) {
            $("#new-loader").removeClass('show');
            // Handle the error
        }
    });
});


/*----------------------------------------------------------------
    *  [show vehicle number input to create drivers]
    * ------------------------------------------------------------*/
$(document).on('change', '.user-roles-select', function(){ 
    if($(this).val() == 'driver'){
        $(this).closest('form').find('.vehicle-input').show();
    }else{
        $(this).closest('form').find('.vehicle-input').hide();
    }
});


/*----------------------------------------------------------------
    *  [show vehicle number input to create drivers]
    * ------------------------------------------------------------*/
$(document).on('change', '.client-payment-terms', function(){ 
    if($(this).val() == 'credit_limit'){
        $(this).closest('form').find('.credit-limit-input').prop('disabled',false);
    }else{
        $(this).closest('form').find('.credit-limit-input').prop('disabled',true);
    }
});

/*----------------------------------------------------------------
    *  [show take pvc dimansion input]
    * ------------------------------------------------------------*/
$(document).on('change', '#take_pvc', function(){
    if($(this).is(':checked')){
        $(this).closest('form').find('#take_pvc_dimensions').show();
    }else{
        $(this).closest('form').find('#take_pvc_dimensions').hide();
    }
});

/*----------------------------------------------------------------
    *  [edit order item run]
    * ------------------------------------------------------------*/
$(document).on('click', '.toggle-change-mileage', function(){
    $(this).siblings('input').prop('readonly', function(index, value) {
        return !value;
    });
    $(this).find('i').toggleClass('mdi-pencil mdi-close');
});

/*----------------------------------------------------------------
    *  [sync order items]
    * ------------------------------------------------------------*/
$(document).on('change', 'select[name^="selected_item"]', function(){
    NXSyncServiceOrderItems();
});

/*----------------------------------------------------------------
    *  [user table filter]
    * ------------------------------------------------------------*/
$(document).on("click", '#getColor', function() {

    var formData = new FormData();
    var $this = $('#color-match-image');
    var fileInput = $this[0];
    if (fileInput.files.length > 0) {
        formData.append($this.attr('name'), fileInput.files[0]);
    }

    $.ajax({
        url: NX.base_url+'/floor-operations/get-color',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: formData,
        processData: false,  // Prevent jQuery from processing data
        contentType: false,  // Prevent jQuery from setting content type
        beforeSend: function (xhr) {
            $("#new-loader").addClass('show');
        },
        success: function (response) {
            $("#new-loader").removeClass('show');
            
            var payload = response.dom_html;
            if (payload != undefined && typeof payload == 'object') {
                //loop through the payload and update the dom
                $.each(payload, function (index, value) {
                    //sanity check - make sure its an object
                    if (typeof value == 'object') {
                        if (value.selector != undefined && value.action != undefined && value.value != undefined) {
                            //replace
                            if (value.action == 'replace') {
                                $(value.selector).html(value.value);
                            }
                        }
                    }
                });
            }
        },
        error: function (xhr, status, error) {
            // Handle the error
            $("#new-loader").removeClass('show');
            if(xhr.responseJSON.message !=""){
                NX.notification({type:'error',message: xhr.responseJSON.message})
            }
        }
    });
});