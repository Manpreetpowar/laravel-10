<div class="card-top-left d-flex align-items-center">
    <a href="{{$page['previousUrl']}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>
    <h3> {!! $page['pageTitle'] !!} </h3>
</div>
<div class="card-top-right">
    <div class="button-group">
        <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                    actions-modal-button js-ajax-ux-request reset-target-modal-form" id="expenseFilterButton"
            data-toggle="modal" data-target="#actionsModal"
            data-modal-title="Filter"
            data-url="{{ url('accountings/expenses/filter') }}"
            data-action-url="{{ url('accountings/expenses/filter') }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            Filter
            </button>
        <button type="button"
                class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" data-url="{{ url('accountings/expenses/create')}}"
                data-loading-target="commonModalBody" data-modal-title="Add Expense"
                data-action-url="{{ url('accountings/expenses/store') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-modal-size=""
                 
                data-save-button-class="" data-save-button-text="Add Expense" data-project-progress="0">
                Add Expense
            </button>
    </div>
</div>
