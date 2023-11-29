<div class="card-top-left d-flex align-items-center">
    <a href="{{route('accountings.index')}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>
    <h3> {!! $page['pageTitle'] !!} </h3>
</div>
<div class="card-top-right">
    <div class="button-group">
        <button type="button"
                class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" data-url="{{ route('credit-notes.create')}}"
                data-loading-target="commonModalBody" data-modal-title="Add Credit Note"
                data-action-url="{{ route('credit-notes.store') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-modal-size=""
                 
                data-save-button-class="" data-save-button-text="Add Credit Note" data-project-progress="0">
                Add Credit Note
            </button>
    </div>
</div>