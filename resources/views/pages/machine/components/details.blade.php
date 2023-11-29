<form action="{{route('machines.update',$machine->id)}}" id="pageForm" method="post">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="machine_name">Machine Name</label>
            <input type="text" class="form-control" id="machine_name" name="machine_name" placeholder="Machine Name" value="{{$machine->machine_name ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="brand_name">Brand Name</label>
            <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Brand Name" value="{{$machine->brand_name ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="model">Model</label>
            <input type="text" class="form-control" id="model" name="model" placeholder="Model" value="{{$machine->model ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="total_mileage">Total Mileage(ft)</label>
            <input type="number" min="0" class="form-control" id="total_mileage" name="total_mileage" placeholder="10000" value="{{$machine->total_mileage ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="current_mileage">Current Cycle Mileage(ft)</label>
            <input type="number" min="0" class="form-control" id="current_mileage" name="current_mileage" placeholder="10000" value="{{$machine->current_mileage ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="mileage_servicing_reminder">Mileage Servicing Reminder(ft)</label>
            <input type="number" min="0" class="form-control" id="mileage_servicing_reminder" name="mileage_servicing_reminder" placeholder="10000" value="{{$machine->mileage_servicing_reminder ?? ''}}" autocomplete="fsd">
        </div>
    </div>
</form>
