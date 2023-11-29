
<button type="button"
class="btn btn-default btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
data-toggle="modal" data-target="#commonModal" data-url="{{ route('inventories.edit', $product->id) }}"
data-loading-target="commonModalBody" data-modal-title="Add Product"
data-action-url="{{ route('inventories.update', $product->id) }}"
data-action-method="PUT"
data-action-ajax-class=""
data-modal-size=""
 
data-save-button-class="" data-save-button-text="Save Product" data-close-button-text="Close" data-project-progress="0">
<i class="mdi single mdi-square-edit-outline"></i>
</button>

<button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default confirm-action-red"
data-confirm-title="Delete Product" data-confirm-text="Are you sure you want to delete this product."
data-ajax-type="DELETE" data-url="{{ route('inventories.destroy' , $product->id) }}">
<i class="mdi single mdi-trash-can-outline"></i>
</button>
