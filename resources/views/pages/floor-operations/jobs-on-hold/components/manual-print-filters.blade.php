    <label>    
        <a href="{{url('floor-operations/manual-print/print-all-invoices')}}" class="btn btn-rounded-x btn-primary waves-effect text-left" target="_blank">Print All Invoices</a>
    </label>
    <label>    
        <button type="button" title="Mark all for delivery?"
                    class="data-toggle-action-tooltip btn btn-primary btn-circle confirm-action-primary"
                    data-confirm-title="Mark for delivery?" data-confirm-text="Are you sure you have printed all invoices?" 
                    data-confirm-button="Confirm"
                    data-loading-target="new-loader" data-loading-class="show"
                    data-ajax-type="GET" data-url="{{ url('floor-operations/manual-print/mark-all-for-delivery') }}">
                    Mark all for delivery
                </button>
    </label>