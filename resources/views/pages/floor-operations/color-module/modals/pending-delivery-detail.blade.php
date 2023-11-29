@csrf
  <div class="form-row">
    @if(auth()->user()->hasRole('administrator'))
        <div class="form-group col-md-6">
            <label for="driver">Driver</label>
            <div class="text">{{$serviceOrder->driver->name ?? ''}}</div>
        </div>
    @endif
    <div class="form-group col-md-6">
        <label for="client">Customer Name</label>
        <div class="text">{{$serviceOrder->client->client_name ?? ''}}</div>
    </div>

    <div class="form-group col-md-6">
        <label for="client">Total Pcs</label>
        <div class="text">{{$serviceOrder->total_pieces ?? ''}}</div>
    </div>

    <div class="form-group col-md-6">
        <label for="client">Total Amount</label>
        <div class="text">{{$serviceOrder->invoice->amount ?? ''}}</div>
    </div>

    <div class="form-group col-md-6">
        <label for="client">Invoice Number</label>
        <div class="text">{{$serviceOrder->invoice->invoice_number ?? ''}}</div>
    </div>

    <div class="form-group col-md-6">
        <label for="client">Upload Delivery Image</label>
        <input type="file" class="form-control file-upload-ajax" name="attachment">
    </div>