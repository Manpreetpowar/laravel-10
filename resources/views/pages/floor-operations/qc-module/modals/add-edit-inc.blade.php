@csrf
  <div class="form-row">
    <div class="form-group col-md-6">
        <label for="machine_name">Machine Name</label>
        <input type="text" class="form-control" id="machine_name" name="machine_name" placeholder="Machine Name" value="{{$mechine->machine_name ?? ''}}" autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="brand_name">Brand Name</label>
        <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Brand Name" value="{{$mechine->brand_name ?? ''}}" autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="model">Model</label>
        <input type="text" class="form-control" id="model" name="model" placeholder="Model" value="{{$mechine->model ?? ''}}" autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="mileage_servicing_reminder">Mileage Servicing Reminder</label>
        <input type="text" class="form-control" id="mileage_servicing_reminder" name="mileage_servicing_reminder" placeholder="10000" value="{{$mechine->mileage_servicing_reminder ?? ''}}" autocomplete="fsd">
    </div>
    
</div>
  