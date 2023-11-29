<div class="button-group">

    <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark 
                            actions-modal-button js-ajax-ux-request reset-target-modal-form" id="userFilter"
            data-toggle="modal" data-target="#actionsModal"
            data-modal-title="Filter"
            data-url="{{ url('users/filter') }}"
            data-action-url="{{ url('users/filter') }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            Filter
        </button>

    <button type="button"
            class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ route('users.create')}}"
            data-loading-target="commonModalBody" data-modal-title="Create User"
            data-action-url="{{ route('users.store') }}"
            data-action-method="POST"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-save-button-text="Create Account" data-close-button-text="Cancel Account Creation" data-project-progress="0">
            Create New User
        </button>
</div>