

    @if(config('visibility.credit_note_view'))
    <button type="button"
            class="btn btn-default btn-table-action btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ route('credit-notes.edit' , $note->id) }}"
            data-loading-target="commonModalBody" data-modal-title="Edit Credit Note"
            data-action-url="{{ route('credit-notes.update' , $note->id) }}"
            data-action-method="PUT"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-save-button-text="Update Credit Note" data-project-progress="0">
            <i class="mdi single mdi-eye"></i>
        </button>
        @endif

        @if(config('visibility.credit_note_invoice'))
        <a href=" {{ url('credit-notes/download-invoice/'.$note->id)}}" class="btn btn-default btn-table-action"><i class="mdi single mdi-download"></i></a>
        @endif

        @if(config('visibility.credit_note_delete'))
            <button type="button" title="Delete Credit Note" class="data-toggle-action-tooltip btn btn-default btn-table-action confirm-action-red"
                data-confirm-title="Delete Credit Note" data-confirm-text="Are you sure you want to delete this note."
                data-ajax-type="DELETE" data-url="{{ route('credit-notes.destroy' , $note->id) }}">
                <i class="mdi single mdi-trash-can-outline"></i>
            </button>
        @endif
