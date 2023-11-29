@csrf
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="client">Customer</label>
      <select id="client" name="client" class="form-control select2-basic-with-search" tabindex="-1" aria-hidden="true">
            <option value="" disabled selected>Select Customer</option>
            @foreach($clients as $client)
            <option value="{{$client->id}}" {{ isset($client) ? runtimePreselected($client->id, 1) : ''}}>{{$client->client_name}}</option>
            @endforeach
      </select>
    </div>

   @if(auth()->user()->hasRole('administrator'))
        <div class="form-group col-md-6">
            <label for="driver">Driver</label>
            <select id="driver" name="driver" class="form-control select2-basic-with-search" tabindex="-1" aria-hidden="true">
                    <option value="" disabled selected>Select Driver</option>
                    @foreach($drivers as $driver)
                    <option value="{{$driver->id}}" {{ isset($driver) ? runtimePreselected($driver->id, 1) : ''}}>{{$driver->name}}</option>
                    @endforeach
            </select>
        </div>
    @endif 

    <div class="form-group col-md-6">
        <label for="remarks">Remarks</label>
        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks"  value="{{''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="total_pieces">Total Pcs</label>
        <input type="number" min="0" class="form-control" id="total_pieces" name="total_pieces" placeholder="Total Pieces"  value="{{''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="acc_remark">ACC Remark</label>
        <input type="text" class="form-control" id="acc_remark" name="acc_remark" placeholder="ACC Remark"  value="{{''}}">
    </div>

    <div class="form-row col-md-6">
        <div class="form-group col-6">
            <label for="take_pvc_label">Take PVC?</label>
            <div class="form-check m-0 p-0">
            <label for="take_pvc" class="switch">
                <input class="form-check-input" type="checkbox" id="take_pvc" name="take_pvc" value="option1">
                <span class="slider round"></span>
                <span class="label yes">Yes</span>
                <span class="label no">No</span>
            </label>
            </div>
        </div>

        <div class="form-group col-6" id="take_pvc_dimensions" style="display:none;">
            <label for="pvc_dimensions">Take PVC Dimensions</label>
            <input type="text" class="form-control" id="pvc_dimensions" name="pvc_dimensions" placeholder="Dimensions"  value="{{''}}">
        </div>
    </div>

    <div class="form-group col-md-6 text-warning bg-input-warning">
        <label for="handcraft_remark">Handcraft Remarks</label>
        <input type="text" class="form-control" id="handcraft_remark" name="handcraft_remark" placeholder="Handcraft Remarks"  value="{{''}}">
    </div>

    <div class="form-group col-md-6 text-success bg-input-success">
        <label for="thik_remark">Thick Remarks</label>
        <input type="text" class="form-control" id="thik_remark" name="thik_remark" placeholder="Thick Remarks"  value="{{''}}">
    </div>

</div>
  