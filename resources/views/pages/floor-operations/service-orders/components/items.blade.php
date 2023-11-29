
<div class="form-row">
    <div class="form-group col-1">
        <label for="client" class="font-weight-bold">S/N</label>
    </div>

    <div class="form-group col-4">
        <label for="client" class="font-weight-bold">Item Code</label>
    </div>

    <div class="form-group col-1">
        <label for="client" class="font-weight-bold">Pcs</label>
    </div>

    <div class="form-group col-2">
        <label for="client" class="font-weight-bold">Remarks</label>
    </div>
    @if(config('visibility.mileage') == 'show' || config('visibility.mileage') == 'writable')
        <div class="form-group col-1">
            <label for="client" class="font-weight-bold">FT Run</label>
        </div>
    @endif

    @if(config('visibility.operator') == 'show')
        <div class="form-group col-2">
            <label for="client" class="font-weight-bold">Filled By</label>
        </div>
    @endif

    @if(config('visibility.price') == 'show')
        <div class="form-group col-1">
            <label for="client" class="font-weight-bold">Price</label>
        </div>
    @endif
</div>

<div id="service-order-items">
    @if($serviceOrder->items)
        @foreach($serviceOrder->items as $key => $item)
            @php $type = $item->type; @endphp
            @include('pages.floor-operations.service-orders.components.item',compact('type','key'))
        @endforeach
    @elseif(config('visibility.action_item_add_more') == 'show')
        @include('pages.floor-operations.service-orders.components.item')
    @endif
</div>
@if(config('visibility.action_item_add_more') == 'show')
    <div class="row button-group">
        <div class="form-group col-6 text-center">
            <a id="fx-add-more-item-action" type="button" class="btn btn-primary text-white js-ajax-ux-request m-1" data-url="{{url('service-orders/add-item/inventory')}}">
                Add More Item
            </a>

            <a id="fx-add-more-item-action" type="button" class="btn btn-primary text-white js-ajax-ux-request m-1" data-url="{{url('service-orders/add-item/custom')}}">
                Add Custom Item
            </a>
        </div>
    </div>
@endif
