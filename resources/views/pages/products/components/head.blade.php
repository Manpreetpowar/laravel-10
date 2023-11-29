<div ass="button-group">

    <button type="button"
            class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ route('inventories.create')}}"
            data-loading-target="commonModalBody" data-modal-title="Add Product"
            data-action-url="{{ route('inventories.store') }}"
            data-action-method="POST"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-save-button-text="Add Product" data-project-progress="0">
            Add Product
        </button>
</div>
