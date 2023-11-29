@csrf
<input type="hidden" name="machine_id" value="{{$machine->id ?? 0 }}">
  <div class="form-row">
    <div class="form-group col-md-8">
        <label for="remark">Remarks</label>
        <input type="text" class="form-control" id="remark" name="remark" placeholder="Remark" value="{{$service->remark ?? ''}}" autocomplete="fsd">
    </div>

    <div class="form-group col-md-4">
        <label for="document">Upload Document</label>
        <input type="file" class="form-control file-upload-ajax" id="document" name="file" placeholder="Document" value="{{$service->document ?? ''}}" autocomplete="fsd">
    </div>

</div>
