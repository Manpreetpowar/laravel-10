 
    <a class="btn btn-default btn-add-circle edit-add-modal-button" href="{{url('floor-operations/download-invoice/'.$invoice->service_order_id)}}">
        <i class="mdi mdi-download"></i>
    </a>


    <button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default confirm-action-red"
            data-confirm-title="Delete User" data-confirm-text="Are you sure you want to delete this user."
            data-ajax-type="DELETE" data-url="">
            <i class="mdi single mdi-trash-can-outline"></i>
        </button>
    