@foreach($machines as $machine)
<div class="col-sm-3 col-md-3 col-lg-3" id="machine-card-{{$machine->id}}">
    <div class="card">
        <div class="card-header text-white bg-primary">
            <h4>{{$machine->machine_name}}</h4>
        </div>
        <div class="card-body bg-default">
            <table>
                <tr><th>Brand:</th><td>{{$machine->brand_name}}</td></tr>
                <tr><th>Model:</th><td>{{$machine->model}}</td></tr>
                <tr><th>Total Mileage:</th><td>{{$machine->total_mileage}}</td></tr>
                <tr><th>Current Mileage:</th><td>{{$machine->current_mileage}}</td></tr>
            </table>
        </div>
        <div class="card-footer bg-white text-center border-0">
            <a href="{{route('machines.show',$machine->id)}}" class="btn btn-primary">
                Operate Machine
            </a>
        </div>
    </div>
</div>
@endforeach
