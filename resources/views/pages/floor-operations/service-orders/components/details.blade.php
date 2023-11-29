    <div class="form-row">
        @if(config('visibility.service_order_form') == 'writable')
            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Customer</label>
                <select id="client" name="client" class="form-control select2-basic-with-search" tabindex="-1" aria-hidden="true">
                        <option value="" disabled selected>Select Customer</option>
                        @foreach($clients as $client)
                        <option value="{{$client->id}}" {{ isset($serviceOrder) ? runtimePreselected($client->id, $serviceOrder->client->id) : ''}}>{{$client->client_name}}</option>
                        @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="driver" class="font-weight-bold">Driver</label>
                <select id="driver" name="driver" class="form-control select2-basic-with-search" tabindex="-1" aria-hidden="true">
                        <option value="" disabled selected>Select Driver</option>
                        @foreach($drivers as $driver)
                        <option value="{{$driver->id}}" {{ isset($serviceOrder) ? runtimePreselected($serviceOrder->driver->id, $driver->id) : ''}}>{{$driver->name}}</option>
                        @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="remarks" class="font-weight-bold">Remarks</label>
                <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remark"  value="{{ $serviceOrder->remarks ?? ''}}">
            </div>

            <div class="form-group col-md-2">
                <label for="driver_vehicle_number" class="font-weight-bold">Vehicle</label>
                <input type="text" class="form-control" id="driver_vehicle_number" name="driver_vehicle_number" placeholder="Vehicle Number"  value="{{ $serviceOrder->driver->profile->driver_vehicle_number ?? ''}}">
            </div>
        @else
            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Customer</label>
                <div class="text">{{ $serviceOrder->client->client_name ?? ''}}</div>
            </div>

            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Driver</label>
                <div class="text">{{ $serviceOrder->driver->name ?? ''}}</div>
            </div>

            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Remarks</label>
                <div class="text">{{ $serviceOrder->remarks ?? ''}}</div>
            </div>

            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Vehicle</label>
                <div class="text">{{ $serviceOrder->driver->profile->driver_vehicle_number ?? ''}}</div>
            </div>
        @endif
    </div>

    <div class="form-row">
        @if(config('visibility.service_order_form') == 'writable')
            <div class="form-group col-md-2">
                <label for="total_pieces" class="font-weight-bold">Total Pieces</label>
                <input type="number" min="0" class="form-control" id="total_pieces" name="total_pieces" placeholder="Total Pieces"  value="{{ $serviceOrder->total_pieces ?? ''}}">
            </div>

            <div class="form-group col-md-2">
                <label for="acc_remark" class="font-weight-bold">ACC Remark</label>
                <input type="text" class="form-control" id="acc_remark" name="acc_remark" placeholder="ACC Remark"  value="{{ $serviceOrder->acc_remark ?? ''}}">
            </div>

            <div class="form-group col-md-2">
                <label for="handcraft_remark" class="font-weight-bold">Handcraft Remark</label>
                <input type="text" class="form-control" id="handcraft_remark" name="handcraft_remark" placeholder="Handcraft Remark"  value="{{ $serviceOrder->handcraft_remark ?? ''}}">
            </div>

            <div class="form-group col-md-2">
                <label for="thik_remark" class="font-weight-bold">Thick Remark</label>
                <input type="text" class="form-control" id="thik_remark" name="thik_remark" placeholder="Thick Remark"  value="{{ $serviceOrder->thik_remark ?? ''}}">
            </div>
        @else
            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Total Pieces</label>
                <div class="text">{{ $serviceOrder->total_pieces ?? ''}}</div>
            </div>

            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">ACC Remark</label>
                <div class="text">{{ $serviceOrder->acc_remark ?? ''}}</div>
            </div>

            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Handcraft Remark</label>
                <div class="text">{{ $serviceOrder->handcraft_remark ?? ''}}</div>
            </div>

            <div class="form-group col-md-2">
                <label for="client" class="font-weight-bold">Thick Remark</label>
                <div class="text">{{ $serviceOrder->thik_remark ?? ''}}</div>
            </div>
        @endif

        @if($serviceOrder->take_pvc)
            @if(config('visibility.service_order_form') == 'writable')
                <div class="form-group col-md-2">
                    <label for="pvc_dimensions" class="font-weight-bold">Take PVC Dimension</label>
                    <input type="text" class="form-control" id="pvc_dimensions" name="pvc_dimensions" placeholder="PVC Dimension"  value="{{ $serviceOrder->pvc_dimensions ?? ''}}">
                </div>
            @else
                <div class="form-group col-md-2">
                    <label for="client" class="font-weight-bold">Take PVC Dimension</label>
                    <div class="text">{{ $serviceOrder->pvc_dimensions ?? ''}}</div>
                </div>
            @endif
        @endif

    </div>
