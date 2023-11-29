
    <button type="button"
            class="btn btn-default btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ route('users.edit', $user->id) }}"
            data-loading-target="commonModalBody" data-modal-title="Create User"
            data-action-url="{{ route('users.update', $user->id) }}"
            data-action-method="PUT"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-save-button-text="Save Account" data-close-button-text="Cancel Account Edits" data-project-progress="0">
            <i class="mdi single mdi-square-edit-outline"></i>
        </button>

    <button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default confirm-action-red"
            data-confirm-title="Delete User" data-confirm-text="Are you sure you want to delete this user."
            data-ajax-type="DELETE" data-url="{{ route('users.destroy' , $user->id) }}">
            <i class="mdi single mdi-trash-can-outline"></i>
        </button>
