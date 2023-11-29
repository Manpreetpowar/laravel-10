
<div class="card-top-left d-flex align-items-center">
    @if(isset($page['previousUrl']))<a href="{{ $page['previousUrl']}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>@endif
    <h3> {{$page['pageTitle']}} </h3>
</div>
<div class="card-top-right">
    <div class="button-group">
        <!-- <button type="button"
                class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal" data-url="{{ route('clients.create')}}"
                data-loading-target="commonModalBody" data-modal-title="Create Client"
                data-action-url="{{ route('clients.store') }}"
                data-action-method="POST"
                data-action-ajax-class=""
                data-modal-size=""
                 
                data-save-button-class="" data-save-button-text="Create New Client" data-project-progress="0">
                Create New Client
            </button> -->

          <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                                actions-modal-button js-ajax-ux-request reset-target-modal-form" id="invoicesFilterButton"
                data-toggle="modal" data-target="#actionsModal"
                data-modal-title="Filter"
                data-url="{{ url('invoice/filter') }}"
                data-action-url="{{ url('invoice/filter') }}"
                data-loading-target="actionsModalBody" data-action-method="POST">
                Filter
            </button>
    </div>
</div>
