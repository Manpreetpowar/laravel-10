
        <button type="button"
            class="btn btn-default btn-add-circle edit-add-modal-button js-ajax-ux-request btn-table-action reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ url('accountings/expenses/'.$expense->id.'/edit') }}"
            data-loading-target="commonModalBody" data-modal-title="Edit Expense"
            data-action-method="PUT"
            data-action-url="{{ url('accountings/expenses/update/'.$expense->id) }}"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-project-progress="0">
            <i class="mdi single mdi-square-edit-outline"></i>
        </button>

        <button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default btn-table-action confirm-action-red"
            data-confirm-title="Delete Expense" data-confirm-text="Are you sure you want to delete this expense."
            data-ajax-type="DELETE" data-url="{{ url('accountings/expenses/delete/'. $expense->id) }}">
            <i class="mdi single mdi-trash-can-outline"></i>
        </button>
