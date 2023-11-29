<div class="button-group">

    <button type="button"
            class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ route('clients.create')}}"
            data-loading-target="commonModalBody" data-modal-title="Create Client"
            data-action-url="{{ route('clients.store') }}"
            data-action-method="POST"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-save-button-text="Create New Client" data-project-progress="0">
            Create New Client
        </button>
</div>