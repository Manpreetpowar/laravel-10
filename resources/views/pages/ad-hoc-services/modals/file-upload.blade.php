@csrf
<div class="form-row">
    <div class="form-group col-md-12">
        <label for="document">Document</label>
        <input type="file" class="form-control file-upload-ajax" id="document" name="file" placeholder="Document" value="{{$service->document ?? ''}}" autocomplete="fsd">
    </div>
</div>