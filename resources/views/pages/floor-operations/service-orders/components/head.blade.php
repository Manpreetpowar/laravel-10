<div class="card-top-left d-flex align-items-center">
    @if($page['previousUrl'])
        <a href="{{ $page['previousUrl']}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>
    @endif
    <h3> {{$page['pageTitle']}} </h3>
</div>

<div class="card-top-right">
    <div class="button-group">
        @if(config('visibility.action_form_edit') == 'show')
            <a href="{{url('service-orders/'.$serviceOrder->id.'/edit')}}" class="btn btn-primary">Edit</a>
        @endif

        @if(config('visibility.action_form_start_servicing') == 'show')
            <a href="{{url('service-orders/'.$serviceOrder->id.'/edit')}}" class="btn btn-primary">Start Servicing</a>
        @endif

        @if(config('visibility.action_form_confirmed') == 'show')
            <a href="{{url('service-orders/'.$serviceOrder->id)}}" class="btn btn-danger">Cancel</a>
            <button type="submit" id="pageSubmitButton"
                    class="btn btn-rounded-x btn-primary waves-effect text-left" data-url="{{url('service-orders/confirm-received/'.$serviceOrder->id)}}"  data-loading-target="new-loader" data-loading-class="show"
                    data-ajax-type="PUT" data-on-start-submit-button="disable" data-form-id="pageForm">Confirm Received</button>
        @endif

        @if(config('visibility.action_form_save') == 'show')
            <button type="submit" id="pageSubmitButton"
                    class="btn btn-rounded-x btn-primary waves-effect text-left" data-url="{{$page['saveButtonUrl']}}"  data-loading-target="new-loader" data-loading-class="show"
                    data-ajax-type="PUT" data-on-start-submit-button="disable" data-form-id="pageForm">Save</button>
        @endif

        @if(config('visibility.action_qc_pass') == 'show')
                <button type="button" title="Confirm QC Pass?"
                    class="data-toggle-action-tooltip btn btn-primary btn-circle confirm-action-primary"
                    data-confirm-title="Confirm QC Pass?" data-confirm-text="Once passed, the invoice will be generated and the job will appear in Delivery Driver’s list for delivery."
                    data-confirm-button="Confirm QC Pass" data-loading-target="new-loader" data-loading-class="show"
                    data-ajax-type="GET" data-url="{{ url('floor-operations/service-orders/'.$serviceOrder->id.'/qc-pass/') }}">
                    QC Pass
                </button>
        @endif

        @if(config('visibility.action_download_invoice') == 'show')
                <a href="{{url('floor-operations/download-invoice/'.$serviceOrder->id)}}" class="btn btn-primary" download>Download Invoice</a>
        @endif

        @if(config('visibility.action_form_generate_invoice') == 'show')
            <button type="button" class="data-toggle-action-tooltip btn btn-primary edit-add-modal-button js-ajax-ux-request btn-circle" data-loading-target="new-loader" data-loading-class="show"
                data-ajax-type="GET" data-url="{{url('floor-operations/on-hold/service-orders/generate-invoice/'.$serviceOrder->id)}}">
                Generate Invoice
            </button>
        @endif

        @if(config('visibility.action_form_manual_print') == 'show')
                <a href="{{ url('floor-operations/print-invoice/'.$serviceOrder->id) }}" class="btn btn-primary" target="_blank">Print Invoice</a>

                <button type="button" class="data-toggle-action-tooltip btn btn-primary edit-add-modal-button js-ajax-ux-request btn-circle" data-loading-target="new-loader" data-loading-class="show"
                    data-ajax-type="GET" data-url="{{url('floor-operations/on-hold/service-orders/mark-delivery/'.$serviceOrder->id)}}">
                    Mark For Delivery
                </button>
        @endif
    </div>
</div>
