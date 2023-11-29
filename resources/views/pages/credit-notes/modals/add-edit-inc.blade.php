@csrf

<div class="form-row">
    <div class="form-group col-md-4">
            @if($client instanceof \App\Models\Client)
            <label for="client_select">Client Name</label>
                <select id="client" name="client" class="form-control">
                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                </select>
            @else
        <label for="client_select">Client Name</label>
        <select id="client" name="client" class="form-control">
            <option selected disabled>Select Client</option>
            @foreach ($client as $clients)
                <option value="{{ $clients->id }}">{{ $clients->client_name }}</option>
            @endforeach
        </select>
        @endif
    </div>

    <div class="form-group col-md-4">
        <label for="apply_gst">Apply GST?</label>
        <div class="form-check m-0 p-0">
        <label for="apply_gst" class="switch">
            <input class="form-check-input" type="checkbox" id="apply_gst" name="apply_gst" value="1" {{ isset($creditNote) ? runtimePreChecked2($creditNote->apply_gst,1) : ''}}>
            <span class="slider round"></span>
            <span class="label yes">Yes</span>
            <span class="label no">No</span>
        </label>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label for="terms">Terms</label>
        <input type="text" class="form-control" id="terms" name="terms" placeholder="Terms" value="{{$creditNote->terms ?? ''}}">
    </div>
</div>
<div class="note-items">
    <div class="item-container" id="inventory-items">
        <div class="form-row">
            <div class="form-group col-md-1">
                <label for="terms">SrNo.</label>
            </div>
            <div class="form-group col-md-3">
                <label for="terms">Item Code</label>
            </div>
            <div class="form-group col-md-1">
                <label for="terms">Qty</label>
            </div>
            <div class="form-group col-md-2">
                <label for="terms">Unit Price</label>
            </div>
            <div class="form-group col-md-3">
                <label for="terms">Total Price</label>
            </div>
            <div class="form-group col-md-2">
            </div>
        </div>
        @if(isset($creditNote) && count($creditNote->items))
            @foreach($creditNote->items as $item)
                @include('pages.credit-notes.utils.note-item')
            @endforeach
        @else
            @include('pages.credit-notes.utils.note-item')
        @endif
    </div>
    <div class="text-center">
        <a id="fx-add-more-item-action" type="button" class="btn btn-primary text-white js-ajax-ux-request" data-url="{{url('credit-note/add-item')}}">
            Add More Item
        </a>
    </div>
  </div>
