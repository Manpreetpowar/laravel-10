
<div class="card-top-left d-flex align-items-center">
    <a href="{{route('clients.index')}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>
    <h3> {{$page['pageTitle']}} </h3>
</div>
<div class="card-top-right">
    <div class="button-group">
        
        <a href="{{url('clients/'.$client->id.'/download-all-unpaid-invoices')}}" class="btn btn-primary"
            data-ajax-type="GET" data-url="{{url('clients/'.$client->id.'/download-all-unpaid-invoices')}}" download>
            Download All Unpaid Invoices
        </a>

        <button type="button"
                class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" data-url="{{ url('account-statement/generate-statement/'.$client->id)}}"
                data-loading-target="commonModalBody" data-modal-title="Generate Statement of Accounts"
                data-action-url="{{ url('account-statement/generate-statement') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-modal-size="sm"
                data-save-button-class="" data-save-button-text="Generate" data-close-button-text="Cancel" data-project-progress="0">
                Generate Statement of Accounts
            </button>


            <button type="submit" id="pageSubmitButton"
                    class="btn btn-rounded-x btn-primary waves-effect text-left" data-url="{{route('clients.update',$client->id)}}" data-loading-target="new-loader" data-loading-class="show"
                    data-ajax-type="PUT" data-on-start-submit-button="disable" data-form-id="pageForm">Update Client Details</button>
    </div>
</div>
