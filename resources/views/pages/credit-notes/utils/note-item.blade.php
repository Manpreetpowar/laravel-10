<div class="form-row item" id="note-item-{{$item->id ?? 0}}">
    <div class="form-group col-md-1">
        <span class="note-item-qty">1</span>
        <input type="hidden" name="item_id[]" value="{{$item->id ?? 0}}">
    </div>
    <div class="form-group col-md-3">
        <input type="text" class="form-control" id="item_code" name="item_code[]" placeholder="Item Code" value="{{$item->item_code ?? ''}}" required>
    </div>
    <div class="form-group col-md-1">
        <input type="number"  min="0" class="form-control" id="quantity" name="quantity[]" placeholder="Quantity" value="{{$item->quantity ?? ''}}" required>
    </div>
    <div class="form-group col-md-2">
        <input type="text" class="form-control" id="unit_price" name="unit_price[]" placeholder="Unit Price" value="{{$item->unit_price ?? ''}}" required>
    </div>
    <div class="form-group col-md-3">
        <input type="text" class="form-control" id="total_price" name="total_price[]" placeholder="Total Price" value="{{$item->total_price ?? ''}}" required>
    </div>
    <div class="form-group col-md-2">
        @if(isset($item))
            <button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default confirm-action-red"
                    data-confirm-title="Delete Item" data-confirm-text="Are you sure you want to delete this item."
                    data-ajax-type="DELETE" data-url="{{ url('credit-items/destroy/'.$item->id) }}">
                    <i class="mdi single mdi-trash-can-outline"></i>
                </button>
        @else
            <button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default js-remove-parent"
                data-parent="item">
                    <i class="mdi single mdi-trash-can-outline"></i>
                </button>
        @endif
    </div>
</div>