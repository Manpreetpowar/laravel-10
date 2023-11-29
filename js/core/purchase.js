"use strict";

/*----------------------------------------------------------------
 * output debug data - only if debug mode is enabled
 * [returns] - bool
 *--------------------------------------------------------------*/
NXPURCHASEORDER.log = function (payload1, payload2) {
    if (NX.debug_javascript) {
        if (payload1 != undefined) {
            console.log(payload1);
        }
        if (payload2 != undefined) {
            console.log(payload2);
        }
    }
};


/*----------------------------------------------------------------
 * [TOGGLE DISCOUNT AND TAX BUTTONS]
 * - enable or disable these buttons, if the ordering has no value
 *--------------------------------------------------------------*/
NXPURCHASEORDER.toggleTaxDiscountButtons = function (value = 0) {

    var po_final_amount = Number($("#po_final_amount").val());
    var po_subtotal = Number($("#po_subtotal").val());

    //log
    NXPURCHASEORDER.log('[ordering] toggleTaxDiscountButtons() - based on ordering value [po_final_amount]: (' + po_final_amount + ')');

    //dom
    var $discount_button = $("#po-discounts-popover-button");
    var $tax_button = $("#po-tax-popover-button");

    //does the bill have a laue

}


/*----------------------------------------------------------------
 * Purchase order has loaded. Let do some intial tasks
 *--------------------------------------------------------------*/

NXPURCHASEORDER.DOM.domState = function () {

    NXPURCHASEORDER.log('[ordering] state() - setting ordering doma state- [payload]', NXPURCHASEORDER.DATA.INVOICE);

    //toggle discount and tax buttons
    NXPURCHASEORDER.toggleTaxDiscountButtons();

    //remove class from crumbs to avoid actions when check boxes are ticked
    $("#breadcrumbs").removeClass('list-pages-crumbs');
}



/*----------------------------------------------------------------
 * properly format money
 *--------------------------------------------------------------*/

function nxFormatDecimal(number = 0) {

    return accounting.formatNumber(number, 2, "", ".");

}



/*----------------------------------------------------------------
 * [TOGGLE TAX POPOVER]
 * - set the tax DOm elements
 *--------------------------------------------------------------*/
NXPURCHASEORDER.toggleTaxDom = function (tax_type = '') {



    NXPURCHASEORDER.log('[ordering] initialiseTaxPopover() - toggling tax popover - [tax_type]: ' + tax_type);

    //popover elements visibility
    if (tax_type == 'inline') {
        $("#po-tax-popover-inline-info").show();
        $("#po-tax-popover-summary-info").hide();
    }

    if (tax_type == 'summary') {
        $("#po-tax-popover-inline-info").hide();
        $("#po-tax-popover-summary-info").show();
    }

    if (tax_type == 'none') {
        $("#po-tax-popover-inline-info").hide();
        $("#po-tax-popover-summary-info").hide();
    }

    //update tax type
    $("#po-tax-type").val(tax_type);


    //preselect check boxes
    $('#po-logic-taxes :selected').each(function (i, selected) {
        var element_id = $(selected).attr('id');
        $("#" + element_id).prop("checked", true);
    });
};


/*----------------------------------------------------------------
 * [TOGGLE DICOUNTS POPOVER]
 * - set visibility of the form fields
 *--------------------------------------------------------------*/
NXPURCHASEORDER.toggleDiscountDom = function (po_discount_type = '') {

    //get current values
    var po_discount_value = '';

    //log
    NXPURCHASEORDER.log('[ordering] toggleDiscountDom() setting popover state [current_discount_type]: (' + po_discount_type + ')');


    //dom
    var $fixed_container = $("#po-discounts-popover-fixed");
    var $percentage_container = $("#po-discounts-popover-percentage");

    //default visibility
    $fixed_container.hide();
    $percentage_container.hide();

    //fixed discount
    if (po_discount_type == 'fixed') {
        var po_discount_value = $("#po_discount_amount").val();
    }

    //percentage discount
    if (po_discount_type == 'percentage') {
        var po_discount_value = $("#po_discount_percentage").val();
    }


    //percentage discount
    if (po_discount_type == 'none') {
        $("#js-po-discount-type").val('none')
        var po_discount_value = $("#po_discount_percentage").val();
    }


    //fixed discount
    if (po_discount_type == 'fixed') {
        $percentage_container.hide();
        $fixed_container.show();
        $("#js-po-discount-type").val('fixed')
        $("#js_po_discount_amount").val(nxFormatDecimal(po_discount_value));
    }

    //percentage discount
    if (po_discount_type == 'percentage') {
        $fixed_container.hide();
        $percentage_container.show();
        $("#js-po-discount-type").val('percentage')
        $("#js_po_discount_percentage").val(Number(po_discount_value));
    }

}


/*----------------------------------------------------------------
 * [TOGGLE ADJUSTMENTS POPOVER]
 *--------------------------------------------------------------*/
NXPURCHASEORDER.toggleAdjustmentDom = function () {

    NXPURCHASEORDER.log('[ordering] toggleAdjustmentDom() setting adjustment popover state');

    //set the data from the 
    var po_adjustment_description = $("#po_adjustment_description").val();
    var po_adjustment_amount = $("#po_adjustment_amount").val();

    //set forn data
    $("#js_bill_adjustment_amount").val(nxFormatDecimal(po_adjustment_amount));
    $("#js_bill_adjustment_description").val(po_adjustment_description);

}

/*----------------------------------------------------------------
 * [TOGGLE FREIGHT POPOVER]
 *--------------------------------------------------------------*/
NXPURCHASEORDER.toggleFreightDom = function () {

    NXPURCHASEORDER.log('[ordering] toggleFreightDom() setting freight popover state');

    //set the data from the 
    var po_freight_amount = $("#po_freight_amount").val();

    //set forn data
    $("#js_po_freight_amount").val(nxFormatDecimal(po_freight_amount));

}

/*----------------------------------------------------------------
 * update the amount due bill label
 *--------------------------------------------------------------*/

NXPURCHASEORDER.DOM.updateAmountDue = function (po_final_amount = 0) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.updateAmountDue() - updating amount due label [po_final_amount]: (" + po_final_amount + ")");

    //amount due label
    var amount_due_label = $("#po-details-amount-due");

    //calculate based on the current bill balance
    var amount_due = po_final_amount - Number($("#po_total_payments").val());
    if (amount_due > 0) {
        //update amount due and turn lable to red color
        amount_due_label.html(accounting.formatMoney(amount_due)).removeClass("label-success").addClass("label-danger");
    } else {
        //update amount due and turn lable to red color
        amount_due_label.html(accounting.formatMoney(amount_due)).removeClass("label-danger").addClass("label-success");
    }
}


/*----------------------------------------------------------------
 * [LINE ITEM]
 * -add new blank line item
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.itemNewLine = function (data = {}) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.itemNewLine() - cloning time line - [payload]");
    NXPURCHASEORDER.log(data);

    //get data is any was provided
    var item_unit = (data.item_unit != null) ? data.item_unit : '';
    var item_number = (data.item_number != null) ? data.item_number : '';
    var item_quantity = (data.item_quantity != null) ? data.item_quantity : '';
    var item_description = (data.item_description != null) ? data.item_description : '';
    var item_rate = (data.item_rate != null) ? data.item_rate : '';
    var item_total = (data.item_total != null) ? data.item_total : '';
    var item_linked_type = (data.item_linked_type != null) ? data.item_linked_type : '';
    var item_linked_id = (data.item_linked_id != null) ? data.item_linked_id : '';

    //check for deuplicate licked items (expense or task etc)
    if (item_linked_type != '') {
        var check = item_linked_type + '|' + item_linked_id; //e.g. data-duplicate-check='expense|23'
        if ($("input[data-duplicate-check='" + check + "']").length > 0) {
            NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.itemNewLine() - the item being added is a duplicate. Will skip (" + check + ")");
            //note this duplcate error
            NXPURCHASEORDER.DATA.expense_duplicate_count++;
            return;
        }
    }
    console.log(item_number);

    //new element (plain)
    var lineitem = $("#po-line-template-plain").find('tr').first().clone();


    //prefill if any data has been sent
    lineitem.find(".js_item_manufacturer_part_number").val(item_number);
    lineitem.find(".js_item_description").html(item_description);
    lineitem.find(".js_item_quantity").val(item_quantity);
    lineitem.find(".js_item_unit").val(item_unit);
    lineitem.find(".js_item_rate").val(item_rate);
    lineitem.find(".js_item_total").val(item_total);
    lineitem.find(".js_item_linked_type").val(item_linked_type);
    lineitem.find(".js_item_linked_id").val(item_linked_id);
    lineitem.find(".js_linetax_rate").val('');

    //add unique id to the ide
    var uniqueid = NX.uniqueID();

    //add unique id to the table row <tr>
    lineitem.attr('id', uniqueid);

    //change field names to name='foo[xxx]' array with unique id
    lineitem.find(".js_item_manufacturer").attr("name", "js_item_manufacturer[" + uniqueid + "]");
    lineitem.find(".js_item_manufacturer_part_number").attr("name", "js_item_manufacturer_part_number[" + uniqueid + "]");
    lineitem.find(".js_item_description").attr("name", "js_item_description[" + uniqueid + "]");
    lineitem.find(".js_item_quantity").attr("name", "js_item_quantity[" + uniqueid + "]");
    lineitem.find(".js_item_unit").attr("name", "js_item_unit[" + uniqueid + "]");
    lineitem.find(".js_item_rate").attr("name", "js_item_rate[" + uniqueid + "]");
    lineitem.find(".js_item_total").attr("name", "js_item_total[" + uniqueid + "]");
    lineitem.find(".js_linetax_rate").attr("name", "js_linetax_rate[" + uniqueid + "]");
    lineitem.find(".js_item_linked_type").attr("name", "js_item_linked_type[" + uniqueid + "]");
    lineitem.find(".js_item_linked_id").attr("name", "js_item_linked_id[" + uniqueid + "]");
    lineitem.find(".js_item_linked_type").attr("data-duplicate-check", item_linked_type + "|" + item_linked_id); //used for tracking duplicates
    lineitem.find(".js_item_type").attr("name", "js_item_type[" + uniqueid + "]");



    //add hidden field (to track unique id)
    lineitem.append('<input type="hidden" name="uniqueid" value="' + uniqueid + '">');

    //append finished line to the table
    $("#po-items-container").append(lineitem);

    //remove button focus
    self.blur();
};


/*----------------------------------------------------------------
 * [LINE ITEM]
 * -add new blank line item
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.timeNewLine = function (data = {}) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.timeNewLine() - cloning time line - [payload]");
    NXPURCHASEORDER.log(data);

    //get data is any was provided
    var item_description = (data.item_description != null) ? data.item_description : '';
    var item_unit = (data.item_unit != null) ? data.item_unit : NXLANG.invoice_time_unit;
    var item_hours = (data.item_hours != null) ? data.item_hours : '';
    var item_minutes = (data.item_minutes != null) ? data.item_minutes : '';
    var item_rate = (data.item_rate != null) ? data.item_rate : '';
    var item_total = (data.item_total != null) ? data.item_total : '';
    var item_linked_id = (data.item_linked_id != null) ? data.item_linked_id : '';
    var item_timers_list = (data.item_timers_list != null) ? data.item_timers_list : '';


    //round item total
    item_total = accounting.toFixed(item_total, 2);

    //new element (plain or time)
    var lineitem = $("#ordering-line-template-time").find('tr').first().clone();


    //prefill if any data has been sent
    lineitem.find(".js_item_description").html(item_description);
    lineitem.find(".js_item_hours").val(item_hours);
    lineitem.find(".js_item_minutes").val(item_minutes);
    lineitem.find(".js_item_unit").val(item_unit);
    lineitem.find(".js_item_rate").val(item_rate);
    lineitem.find(".js_item_total").val(item_total);
    lineitem.find(".js_item_linked_id").val(item_linked_id);
    lineitem.find(".js_linetax_rate").val('');
    lineitem.find(".js_item_unit").val(item_unit);
    lineitem.find(".js_item_timers_list").val(item_timers_list);


    //add unique id to the ide
    var uniqueid = NX.uniqueID();

    //add unique id to the table row <tr>
    lineitem.attr('id', uniqueid);

    //change field names to name='foo[xxx]' array with unique id
    lineitem.find(".js_item_description").attr("name", "js_item_description[" + uniqueid + "]");
    lineitem.find(".js_item_hours").attr("name", "js_item_hours[" + uniqueid + "]");
    lineitem.find(".js_item_minutes").attr("name", "js_item_minutes[" + uniqueid + "]");
    lineitem.find(".js_item_unit").attr("name", "js_item_unit[" + uniqueid + "]");
    lineitem.find(".js_item_rate").attr("name", "js_item_rate[" + uniqueid + "]");
    lineitem.find(".js_item_total").attr("name", "js_item_total[" + uniqueid + "]");
    lineitem.find(".js_linetax_rate").attr("name", "js_linetax_rate[" + uniqueid + "]");
    lineitem.find(".js_item_linked_type").attr("name", "js_item_linked_type[" + uniqueid + "]");
    lineitem.find(".js_item_linked_id").attr("name", "js_item_linked_id[" + uniqueid + "]");
    lineitem.find(".js_item_timers_list").attr("name", "js_item_timers_list[" + uniqueid + "]");
    lineitem.find(".js_item_type").attr("name", "js_item_type[" + uniqueid + "]");



    //add hidden field (to track unique id)
    lineitem.append('<input type="hidden" name="uniqueid" value="' + uniqueid + '">');

    //append finished line to the table
    $("#po-items-container").append(lineitem);

    //remove button focus
    self.blur();
};


/*-----------------------------------------------------------------------------------------------------------
 * [RECALCULATE LINE ITEMS]
 * - validate and calculate each time items todal
 * ------------------------------------------------------------------------------------------------------------------*/
NXPURCHASEORDER.CALC.recalculateLines = function () {

    NXPURCHASEORDER.log("[ordering] recalculateLines() - validating and recalculating each line item");

    //(1) ------------------------------ find each line item --------------------------------
    $("#po-items-container").find(".po-line-item").each(function () {

        NXPURCHASEORDER.log("[ordering] recalculateLines() - found a line item. Now validating and calculating it");

        var lineitem = $(this);
        var id = lineitem.attr('id');
        var type = lineitem.attr('type');

        //each input fields
        var description = lineitem.find(".js_item_description");
        var quantity = lineitem.find(".js_item_quantity").val();
        var unit = lineitem.find(".js_item_unit").val();
        var rate = lineitem.find(".js_item_rate").val();
        var total = lineitem.find(".js_item_total");
        var selected_taxes = lineitem.find(".js_linetax_rate");
        var tax = lineitem.find(".js_linetax_total");

        //for time items
        var hours = lineitem.find(".js_item_hours").val();
        var minutes = lineitem.find(".js_item_minutes").val();



        //get the total line tax for this row
        var line_tax = 0;
        if ($("#po_tax_type").val() == 'lineitem') {
            selected_taxes.find(':selected').each(function () {
                line_tax += Number($(this).val());
            });
        }

        NXPURCHASEORDER.log("[ordering] recalculateLines() - [quantity]: (" + quantity + ") - [rate]: (" + rate + ") - [linetax]: (" + line_tax + ") - [type]: (" + type + ")");


        /** ---------------------------------------------------
         * SET ZERO DEFAULTS
         * ignore if this is the currently focused item
         * [02-04-2021]
         * --------------------------------------------------*/
        if (hours == '' || hours == null) {
            if (lineitem.find(".js_item_hours").is(":focus")) {
                //do nothing
            } else {
                lineitem.find(".js_item_hours").val(0);
            }
        }
        if (minutes == '' || minutes == null) {
            if (lineitem.find(".js_item_minutes").is(":focus")) {
                //do nothing
            } else {
                lineitem.find(".js_item_minutes").val(0);
            }
        }

        /** ---------------------------------------------------
         * PLAIN LINE ITEMS
         * --------------------------------------------------*/
        if (type == 'plain') {
            //if row is valid, workout total
            if (quantity > 0 && rate > 0) {
                //line total and tax
                var linetotal = quantity * rate;
                total.val(nxFormatDecimal(linetotal));
                //work out tax
                var linetax = linetotal * line_tax / 100;
                //save line tax (sum) for later calculations
                tax.val(linetax);
                //increase bill total
                NXPURCHASEORDER.DATA.calc_total += linetotal;
                NXPURCHASEORDER.log("[ordering] reclaculateBill() - line item is valid. [line item total]: " + linetotal);
            } else {
                NXPURCHASEORDER.log("[ordering] reclaculateBill() - line item is invalid and is skipped");
                total.val('');
            }
        }

        /** ---------------------------------------------------
         * TIME LINE ITEMS
         * --------------------------------------------------*/
        if (type == 'time') {
            //if row is valid, workout total
            if ((hours > 0 || minutes > 0) && rate > 0) {

                //defaults minutes total
                var minutes_total = 0;
                //hours total
                var hours_total = hours * rate;
                //if we have minutes
                if (minutes > 0) {
                    minutes_total = (minutes / 60) * rate;
                }
                //line total
                var linetotal = hours_total + minutes_total;

                //round to 2 decimal places
                var linetotal = accounting.toFixed(linetotal, 2);

                //format to decimal
                total.val(nxFormatDecimal(linetotal));
                //work out tax
                var linetax = linetotal * line_tax / 100;
                //save line tax (sum) for later calculations
                tax.val(linetax);
                //increase bill total
                NXPURCHASEORDER.DATA.calc_total += linetotal;
                NXPURCHASEORDER.log("[ordering] reclaculateBill() - line item is valid. [line item total]: " + linetotal);
            } else {
                NXPURCHASEORDER.log("[ordering] reclaculateBill() - line item is invalid and is skipped");
                total.val('');
            }
        }

    });
}

/*----------------------------------------------------------------
 * [LINE ITEM]
 * -delete line item
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.deleteLine = function (self) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.deleteLine() - deleteing line item");

    //find parent
    var lineitem = self.closest('tr');

    //remove it
    lineitem.remove();

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill();

};


/*----------------------------------------------------------------
 * [ADD INVOICE ITEM]
 * -add the selected product as an bill item
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.addSelectedProductItems = function (self) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.addSelectedProductItems() - adding items selected in add items modal");

    //count
    var count_selected = 0;

    //check if items were selected
    $("#items-list-table").find(".items-checkbox").each(function () {
        
        if ($(this).is(":checked")) {
            //save to object
            var data = {
                'item_number': $(this).attr('data-item-number'),
                'item_description': $(this).attr('data-description'),
                'item_quantity': $(this).attr('data-quantity'),
                'item_unit': $(this).attr('data-unit'),
                'item_rate': $(this).attr('data-rate'),
                'item_total': $(this).attr('data-rate'),
            }

            //create new line item
            NXPURCHASEORDER.DOM.itemNewLine(data);

        }

        count_selected++;
    });

    //close modal or show error
    $("#itemsPoModal").modal('hide');


    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill();
};


/**----------------------------------------------------------------------
 * [ADD EXPENSE]
 * -add the selected expense as a bill line item
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.addSelectedExpense = function (self) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.addSelectedExpense() - adding expenses selected in add expenses modal");

    //count
    var count_selected = 0;

    //duplicates checker
    NXPURCHASEORDER.DATA.expense_duplicate_count = 0;

    //check if expenses were selected
    $("#expenses-list-table").find(".expenses-checkbox").each(function () {
        if ($(this).is(":checked")) {
            //save to object
            var data = {
                'item_description': $(this).attr('data-description'),
                'item_quantity': $(this).attr('data-quantity'),
                'item_unit': $(this).attr('data-unit'),
                'item_rate': $(this).attr('data-rate'),
                'item_total': $(this).attr('data-rate'),
                'item_linked_type': 'expense',
                'item_linked_id': $(this).attr('data-expense-id'), //expense_id
            }

            //create new line expense
            NXPURCHASEORDER.DOM.itemNewLine(data);
        }

        count_selected++;
    });

    //reclaculate bill
    NXPURCHASEORDER.CALC.reclaculateBill();

    //error message about duplicated expense
    if (NXPURCHASEORDER.DATA.expense_duplicate_count) {
        NX.notification({
            type: 'error',
            message: NXLANG.selected_expense_is_already_on_invoice
        });
    }


    //close modal
    $("#expensesModal").modal('hide');
};


/**----------------------------------------------------------------------
 * [ADD TIME BILLING]
 * -add the selected time as a bill line item
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.addSelectedTimebilling = function (self) {

    NXPURCHASEORDER.log("[ordering] NXPURCHASEORDER.DOM.addSelectedTimebilling() - adding hours selected in add time billing modal");

    //count
    var count_selected = 0;

    //check if items were selected
    $("#tasks-list-table").find(".tasks-checkbox").each(function () {
        if ($(this).is(":checked")) {
            //save to object
            var data = {
                'item_description': $(this).attr('data-description'),
                'item_hours': $(this).attr('data-hours'),
                'item_minutes': $(this).attr('data-minutes'),
                'item_rate': $(this).attr('data-rate'),
                'item_unit': $(this).attr('data-unit'),
                'item_total': $(this).attr('data-total'),
                'item_linked_type': $(this).attr('data-linked-type'),
                'item_linked_id': $(this).attr('data-linked-id'),
                'item_timers_list': $(this).attr('data-timers-list'),
            }
            //create new line expense
            NXPURCHASEORDER.DOM.timeNewLine(data);
        }

        count_selected++;
    });

    //reclaculate bill
    NXPURCHASEORDER.CALC.reclaculateBill();

    //close modal
    $("#timebillingModal").modal('hide');
};



/*----------------------------------------------------------------
 * [UPDATE TAX TYPE]
 * -set the selected tax type
 *--------------------------------------------------------------*/
NXPURCHASEORDER.updateTax = function () {

    //get tax type from popover form
    var tax_type = $("#po-tax-type").val();


    NXPURCHASEORDER.log("[ordering] updateTaxType() - updating tax type [type]: (" + tax_type + ")");

    //deselect all taxes
    $("#po_logic_taxes").val([]);

    //[logic] update tax is being updates
    if (tax_type == 'summary') {
        $(".po_col_tax").hide();
    }


    //tax table columns visibility
    if (tax_type == 'inline') {
        $(".po_col_tax").show();
    }

    //tax table columns visibility
    if (tax_type == 'none') {
        $(".po_col_tax").hide();
    }

    //[logic] update po tax type
    $("#po_tax_type").val(tax_type);

    // do this for each selected tax rate
    $("#po-tax-popover-summary-info").find(".js_summary_tax_rate").each(function () {
        //mark as selected
        if ($(this).is(":checked")) {
            //get uniqie tax id
            var id = $(this).attr('data-tax-unique-id');
            $("#po_logic_taxes option[id='" + id + "']").prop("selected", true);
        }
    });

    //close popover
    $('#po-tax-popover-button').popover('hide');

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill(self);
}


/*----------------------------------------------------------------
 * [UPDATE ADJUSTMENTS]
 * -
 *--------------------------------------------------------------*/
NXPURCHASEORDER.updateAdjustment = function () {

    NXPURCHASEORDER.log("[ordering] updateAdjustment() - updating bill adjustment amount");

    //get adjustment description
    var po_adjustment_description = $("#js_bill_adjustment_description").val();
    var po_adjustment_amount = $("#js_bill_adjustment_amount").val();

    //check it its zero amount
    if (Number(po_adjustment_amount) == 0) {
        NXPURCHASEORDER.removeAdjustment();
    }

    //update logic form
    $("#po_adjustment_description").val(po_adjustment_description);
    $("#po_adjustment_amount").val(po_adjustment_amount);

    //update displayed data
    $("#po-adjustment-container-description").html(po_adjustment_description);

    //better 'negative' amount formatting (accounting.formatMoney() returns $-9.99 instead of -$9.99)
    if (po_adjustment_amount < 0) {
        po_adjustment_amount = -po_adjustment_amount;
        $("#po-adjustment-container-amount").html('-' + accounting.formatMoney(po_adjustment_amount));
    } else {
        $("#po-adjustment-container-amount").html(accounting.formatMoney(po_adjustment_amount));
    }

    //show adjustment line
    $("#po-adjustment-container").show();

    //close discount popover
    $('#po-adjustment-popover-button').popover('hide');

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill(self);

}


/*----------------------------------------------------------------
 * [REMOVE ADJUSTMENTS]
 * -
 *--------------------------------------------------------------*/
NXPURCHASEORDER.removeAdjustment = function () {

    //log
    NXPURCHASEORDER.log("[ordering] removeAdjustment() - updating po adjustment amount to zero");


    //update logic form
    $("#po_adjustment_description").val('');
    $("#po_adjustment_amount").val(0);

    //update displayed data
    $("#po-adjustment-container-description").html('');
    $("#po-adjustment-container-amount").html(accounting.formatMoney(0));

    //hide the row
    $("#po-adjustment-container").hide();

    //close discount popover
    $('#po-adjustment-popover-button').popover('hide');

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill(self);

}

/*----------------------------------------------------------------
 * [UPDATE FREIGHT]
 * -
 *--------------------------------------------------------------*/
NXPURCHASEORDER.updateFreight = function () {

    NXPURCHASEORDER.log("[ordering] updateFreight() - updating po freight amount");

    //get freight description
    var po_freight_amount = $("#js_po_freight_amount").val();

    //check it its zero amount
    if (Number(po_freight_amount) <= 0) {
        NXPURCHASEORDER.removeFreight();
    }

    //update logic form
    $("#po_freight_amount").val(po_freight_amount);

    $("#po-freight-container-amount").html(accounting.formatMoney(po_freight_amount));

    //show adjustment line
    $("#po-freight-container").show();

    //close discount popover
    $('#po-freight-popover-button').popover('hide');

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill(self);

}


/*----------------------------------------------------------------
 * [REMOVE FREIGHT]
 * -
 *--------------------------------------------------------------*/
NXPURCHASEORDER.removeFreight = function () {

    //log
    NXPURCHASEORDER.log("[ordering] removeFreight() - updating po freight amount to zero");


    //update logic form
    $("#po_freight_amount").val(0);

    //update displayed data
    $("#po-freight-container-amount").html(accounting.formatMoney(0));

    //hide the row
    $("#po-freight-container").hide();

    //close discount popover
    $('#po-freight-popover-button').popover('hide');

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill(self);

}


/*----------------------------------------------------------------
 * [UPDATE DISCOUNT TYPE]
 * -set the selected discount type
 *--------------------------------------------------------------*/
NXPURCHASEORDER.updateDiscount = function () {

    NXPURCHASEORDER.log("[ordering] updateDiscountType() - updating discount type");

    //type
    var discount_type = $("#js-billing-discount-type").val();


    //validation percentage
    if (discount_type == 'percentage') {
        NXPURCHASEORDER.log("[ordering] updateDiscountType() - [percentage] ", Number($("#js_bill_discount_percentage").val()));
        if (Number($("#js_bill_discount_percentage").val()) > 100 || Number($("#po_discount_type").val()) <= 0) {
            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.invalid_discount
            });
            return;
        }
    }

    //validation percentage
    if (discount_type == 'fixed') {
        NXPURCHASEORDER.log("[ordering] updateDiscountType() - [fixed] ", Number($("#js_bill_discount_amount").val()));
        if (Number($("#js_bill_discount_amount").val()) <= 0) {
            //error message
            NX.notification({
                type: 'error',
                message: NXLANG.invalid_discount
            });
            return;
        }
    }

    //fixed discount
    if (discount_type == 'fixed') {
        //update logif form
        $("#po_discount_type").val('fixed');
        $("#dom-po-discount-type").html('(' + NXLANG.fixed + ')');
        $("#po_discount_percentage").val(0.00);
        $("#po_discount_amount").val($("#js_bill_discount_amount").val());
    }

    //percentage discount
    if (discount_type == 'percentage') {
        $("#po_discount_type").val('percentage');
        $("#dom-po-discount-type").html('(' + $("#js_bill_discount_percentage").val() + '%)');
        $("#po_discount_percentage").val($("#js_bill_discount_percentage").val());
        $("#po_discount_amount").val(0.00);

    }

    //no discount
    if (discount_type == 'none') {
        $("#po_discount_type").val('none');
        $("#po_discount_percentage").val(0.00);
        $("#po_discount_amount").val(0.00);
    }

    //close discount popover
    $('#po-discounts-popover-button').popover('hide');

    //recalculate bill
    NXPURCHASEORDER.CALC.reclaculateBill(self);

}


/*----------------------------------------------------------------
 * [RECALCULATE INVOICE]
 * -calculate total tax rates (summary or line)
 *--------------------------------------------------------------*/
NXPURCHASEORDER.CALC.reclaculateBill = function (self) {

    NXPURCHASEORDER.log("[ordering] reclaculateBill() - recalculating bill");

    //default tax rate
    var po_tax_total_percentage = 0.00;

    //amount before an deductions
    var po_subtotal = 0.00;

    //total discount amount
    var po_discount_amount = 0.00;

    //total tax amount
    var po_tax_total_amount = 0.00;

    //total before tax
    var po_amount_before_tax = 0.00;

    //po sum
    var po_final_amount = 0.00;

    //adjustment
    var po_adjustment_amount = $("#po_adjustment_amount").val();

    //freight
    var po_freight_amount = $("#po_freight_amount").val();

    //recalculate lines
    NXPURCHASEORDER.CALC.recalculateLines();



    //(1) ----------------------- SUM UP LINE ITEMS -------------------------------------
    NXPURCHASEORDER.log("[po] reclaculateBill() - summing up all line items - started");
    $("#po-items-container").find(".po-line-item").each(function () {
        //each line
        var lineitem = $(this);

        //each line item total
        var linetotal = Number(lineitem.find(".js_item_total").val());

        //validate that its a number
        if (typeof linetotal == 'number') {
            NXPURCHASEORDER.log("[po] reclaculateBill() - valid line item found [total]: (" + linetotal + ")");
            po_subtotal += linetotal;
        }
    });

    //(2) ----------------- UPDATE SUBTOTAL (SUM BEFORE ADJUSTMENTS)--------------------------------
    NXPURCHASEORDER.log("[po] reclaculateBill() - updating subtotal [subtotal]: (" + po_subtotal + ") ");
    $("#po-subtotal-figure").html(accounting.formatMoney(po_subtotal));


    //(3) ----------------- DEDUCT ANY DISCOUNTS AND SET SUM BEFOR TAX--------------------
    var po_discount_type = $("#po_discount_type").val();
    var po_discount_percentage = Number($("#po_discount_percentage").val());
    var po_discount_amount = Number($("#po_discount_amount").val());
    NXPURCHASEORDER.log("[po] reclaculateBill() - calculating discounts [po_discount_type]: (" + po_discount_type + ") [po_discount_percentage]: (" + po_discount_percentage + ") [po_discount_amount] (" + po_discount_amount + ")");


    //if po is percentage based
    if (po_discount_type == 'percentage') {
        //calculate
        var po_discount_amount = (po_subtotal * po_discount_percentage) / 100;
        //log
        NXPURCHASEORDER.log("[po] reclaculateBill() - discount is percentage based. [po_discount_amount]: (" + po_discount_amount + ")");
    }



    //if po is fixed
    if (po_discount_type == 'fixed') {
        //log
        NXPURCHASEORDER.log("[ordering] reclaculateBill() - discount is fixed. [po_discount_amount]: (" + po_discount_amount + ")");
    }


    //do we have a discount
    if (po_discount_amount > 0) {
        var po_amount_before_tax = po_subtotal - po_discount_amount;
        //show subtotal
        $("#po-table-section-subtotal").show();
        //set visibilty
        $("#po-table-section-discounts").show();
        //discount amount
        $("#po-sums-discount").html(accounting.formatMoney(po_discount_amount));
        //log
        NXPURCHASEORDER.log("[po] reclaculateBill() - there is a discount - setting DOM  and [po_amount_before_tax]: (" + po_amount_before_tax + ")");
        //set amount before tas
        $("#po-sums-before-tax").html(accounting.formatMoney(po_amount_before_tax));
    } else {
        //hide subtotal
        $("#po-table-section-subtotal").hide();
        //hide discounts section
        $("#po-table-section-discounts").hide();
        //set amunt before tax to be same as subtotal
        po_amount_before_tax = po_subtotal;
        //log
        NXPURCHASEORDER.log("[po] reclaculateBill() - there is no discount - [po_amount_before_tax]: (" + po_amount_before_tax + ")");
    }




    //(1) ------------------------ SUMMARY TAX ------------------------------------------
    if ($("#po_tax_type").val() == 'summary') {
        //log
        NXPURCHASEORDER.log("[ordering] reclaculateBill() - calculating summary taxes");

        //tax row
        var tax_row = '';

        //sum up each selected tax rate. Then add a new row in the bill table
        $("#po_logic_taxes").find(':selected').each(function () {
            var taxrate = Number($(this).val().split("|")[0]);
            var taxname = $(this).val().split("|")[1];
            var uniqueid = $(this).val().split("|")[2];

            //calculate each tax
            var tax_amount = (po_amount_before_tax * taxrate) / 100;
            //create table row
            tax_row += '<tr class="po-sums-tax-container" id="po-sums-tax-container-' + uniqueid + '">' +
                '<td>' + taxname + ' <span class="x-small">(' + taxrate + '%)</span></td>' +
                '<td>' + accounting.formatMoney(tax_amount) + '</td></tr>';
            po_tax_total_percentage += taxrate;
        });

        //do we have tax
        if (po_tax_total_percentage > 0) {
            //log
            NXPURCHASEORDER.log("[po] reclaculateBill() - this bill has [summary] based tax [tax_percentage]: (" + po_tax_total_percentage + "%)");
            //tax calculation
            po_tax_total_amount = (po_amount_before_tax * po_tax_total_percentage) / 100;
            //replace bill tax row in table
            $("#po-table-section-tax").html(tax_row);
            //show subtotal
            $("#po-table-section-subtotal").show();
            //show before tax section
            if (po_discount_amount > 0) {
                $("#po-sums-before-tax-container").show();
            }
            //show tax section
            $("#po-table-section-tax").show();
            //log
            NXPURCHASEORDER.log("[billing] reclaculateBill() - the total tax on this bill is [total_tax_amount]: (" + po_tax_total_amount + ")");
        } else {
            NXPURCHASEORDER.log("[billing] reclaculateBill() - this bill does not have any applicable tax");
            //hide before tax section
            $("#po-sums-before-tax-container").hide();
            //hide tax section
            $("#po-table-section-tax").hide();
        }
    }



    //update bills final sums
    po_final_amount = po_subtotal - po_discount_amount + po_tax_total_amount;
    NXPURCHASEORDER.log("[ordering] reclaculateBill() - total amount before adjustment. [po_final_amount]: (" + po_final_amount + ")");

    //adjustment
    NXPURCHASEORDER.log("[ordering] reclaculateBill() - accounting for the adjustment. [po_adjustment_amount]: (" + po_adjustment_amount + ")");

    po_final_amount = po_final_amount + Number(po_adjustment_amount);

    //freight
    NXPURCHASEORDER.log("[ordering] reclaculateBill() - accounting for the freight. [po_freight_amount]: (" + po_freight_amount + ")");
    po_final_amount = po_final_amount + Number(po_freight_amount);

    //update final amount dom
    $("#po-sums-total").html(accounting.formatMoney(po_final_amount));

    //save values to logc form
    $("#po_subtotal").val(po_subtotal);
    $("#po_discount_amount").val(nxFormatDecimal(po_discount_amount));
    $("#po_amount_before_tax").val(nxFormatDecimal(po_amount_before_tax));
    $("#po_tax_total_percentage").val(po_tax_total_percentage);
    $("#po_tax_total_amount").val(nxFormatDecimal(po_tax_total_amount));
    $("#po_final_amount").val(nxFormatDecimal(po_final_amount));

    //update amount due label
    NXPURCHASEORDER.DOM.updateAmountDue(po_final_amount);

    NXPURCHASEORDER.log("[ordering] reclaculateBill() - bill claculation finished [final amount]: (" + po_final_amount + ")");

    //set bills DOM state
    NXPURCHASEORDER.DOM.domState();

}


/*----------------------------------------------------------------
 * [SAVE PURCHASE ORDER BUTTON]
 *--------------------------------------------------------------*/
NXPURCHASEORDER.CALC.saveBill = function (self) {

    NXPURCHASEORDER.log("[billing] saveBill() - started");

    if (NXPURCHASEORDER.CALC.validateLines()) {

        //recalculate bill
        NXPURCHASEORDER.CALC.reclaculateBill();

        //send to backend
        nxAjaxUxRequest(self);

    } else {
        //error message
        NX.notification({
            type: 'error',
            message: NXLANG.action_not_completed_errors_found
        });
    }

}



/*----------------------------------------------------------------
 * [SAVE PURCHASE ORDER BUTTON]
 *--------------------------------------------------------------*/
NXPURCHASEORDER.CALC.validateLines = function (self) {

    //log
    NXPURCHASEORDER.log("[ordering] validateLines() - started");


    var count_po_error = 0;

    $("#po-items-container").find(".po-line-item").each(function () {

        NXPURCHASEORDER.log("[po] recalculateLines() - found a line item. Now validating and calculating it");

        var lineitem = $(this);

        //each input fields
        var $description = lineitem.find(".js_item_description");
        var $quantity = lineitem.find(".js_item_quantity");
        var $hours = lineitem.find(".js_item_hours");
        var $minutes = lineitem.find(".js_item_minutes");
        // var $unit = lineitem.find(".js_item_unit");
        var $rate = lineitem.find(".js_item_rate");
        var $type = lineitem.find(".js_item_type");


        //reset errors
        $description.removeClass('error');
        $quantity.removeClass('error');
        // $unit.removeClass('error');
        $rate.removeClass('error');
        $hours.removeClass('error');
        $minutes.removeClass('error');

        //validate description
        if ($description.val() == '') {
            $description.addClass('error');
            count_po_error++;
        }

        //validate plain item quantity
        if ($type.val() == 'plain') {
            if ($quantity.val() == '' || $quantity.val() <= 0) {
                $quantity.addClass('error');
                count_po_error++;
            }
        }

        //validate time item quantity
        if ($type.val() == 'time') {
            if ($hours.val() == '' || $hours.val() == null) {
                //just set to zero
                $hours.val(0);
            }
            if ($minutes.val() == '' || $minutes.val() == null) {
                //if hours are also 0 then show error
                $minutes.val(0);
            }
        }

        //validate unit
        // if ($unit.val() == '') {
        //     $unit.addClass('error');
        //     count_po_error++;
        // }

        //validate rate
        if ($rate.val() == '' || $rate.val() <= 0) {
            $rate.addClass('error');
            count_po_error++;
        }
    });

    //log
    NXPURCHASEORDER.log("[po] validateLines() - (" + count_po_error + ") error found");


    //validate
    if (count_po_error == 0) {
        return true;
    } else {
        return false;
    }

}


/*----------------------------------------------------------------
 * [revalidateItem] clear errors when item is changed
 *--------------------------------------------------------------*/
NXPURCHASEORDER.DOM.revalidateItem = function (self) {

    //validate description & unit
    if (self.hasClass('js_item_description') || self.hasClass('js_item_unit')) {
        if (self.hasClass('error') && self.val() != '') {
            self.removeClass('error')
        }
    }

    //validate rate & quantity
    if (self.hasClass('js_item_rate') || self.hasClass('js_item_quantity') || self.hasClass('js_item_hours') || self.hasClass('js_item_minutes')) {
        if (self.hasClass('error') && self.val() > 0) {
            self.removeClass('error')
        }
    }

}