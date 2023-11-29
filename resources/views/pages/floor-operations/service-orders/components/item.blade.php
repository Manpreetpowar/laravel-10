
<div class="form-row item">
    <div class="form-group col-1">
        <div class="font-weight-bold item-count">{{ isset($key) ? $key+1 : 1}}</div>
    </div>
    <input type="hidden" name="item_id[{{$key ?? 0}}]" value="{{$item->id ?? '0'}}">
    @if(config('visibility.select_item') == 'writable')
        <div class="form-group col-4">
            @if(isset($type) && $type == 'custom')
                <input type="hidden" name="item_type[{{$key ?? 0}}]" value="custom">
                <input type="text" class="form-control" id="item" name="selected_item[{{$key ?? 0}}]" placeholder="Item Name"  value="{{$item->item_name ?? ''}}" required>
            @else
                <input type="hidden" name="item_type[{{$key ?? 0}}]" value="inventory">
                <select id="client" name="selected_item[{{$key ?? 0}}]" class="form-control select2-basic-with-search" data-placeholder="Select Item"  tabindex="-1" aria-hidden="true" required>
                    <option value="" disabled selected>Select Item</option>
                    @foreach($inventories as $inv_item)
                    <option value="{{$inv_item->id}}" {{ isset($item) ? runtimePreselected($inv_item->id, $item->procuct_variant_id) : ''}}>{{$inv_item->product_sku_code}}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="form-group col-1">
            <input type="number" min="0" class="form-control" id="quantity" name="item_qty[{{$key ?? 0}}]" placeholder="Total Pcs"  value="{{$item->quantity ?? ''}}" required>
        </div>
    @else
        <div class="form-group col-4">
            @if(isset($type) && $type == 'custom')
                <div class="text">{{$item->item_name ?? ''}}</div>
            @else
                <div class="text">{{$item->product_variant->product_sku_code ?? ''}}</div>
            @endif
        </div>

        <div class="form-group col-1">
            <div class="text">{{$item->quantity ?? ''}}</div>
        </div>
    @endif

    @if(config('visibility.remark') == 'writable')
        <div class="form-group col-2">
            <input type="text" class="form-control" id="item_remark" name="item_remark[{{$key ?? 0}}]" placeholder="Remarks"  value="{{$item->remarks ?? ''}}">
        </div>
    @else
        <div class="form-group col-2">
            <div class="text">{{$item->remarks ?? ''}}</div>
        </div>
    @endif

    @if(config('visibility.mileage') == 'writable')
        <div class="form-group col-1 d-flex">
            <input type="number" min="0" class="form-control" id="item_mileage" name="item_mileage[{{$key ?? 0}}]" placeholder="Total Run"  value="{{$item->total_run ?? ''}}" @if(!$item->visibility || $item->total_run > 0) readonly @endif>
            @if($item->total_run > 0 )<a href="javascript:void(0);" class="toggle-change-mileage text-dark"><i class="mdi mdi-pencil"></i></a>@endif
        </div>
    @elseif(config('visibility.mileage') == 'show')
        <div class="form-group col-1">
            <div class="text">{{$item->total_run ?? ''}}</div>
        </div>
    @endif

    @if(config('visibility.operator') == 'show')
        <div class="form-group col-2">
            <div class="text">{{$item->operator->name ?? ''}}</div>
        </div>
    @endif

    @if(isset($type) && $type == 'custom' && config('visibility.price') == 'writable')
        <div class="form-group col-1">
            <input type="number" min="0" class="form-control" id="item_price" name="item_price[{{$key ?? 0}}]" placeholder="Unit Price" value="{{$item->price ?? ''}}">
        </div>
    @elseif(config('visibility.price') == 'show')
        <div class="text">{{$item->amount ?? ''}}</div>
    @endif

    @if(config('visibility.remove') == 'show')
        <div class="form-group col-1">
            <button type="button" title="Delete" class="data-toggle-action-tooltip btn btn-default js-remove-parent"
                data-parent="item">
                    <i class="mdi single mdi-trash-can-outline"></i>
                </button>
        </div>
    @endif
</div>
