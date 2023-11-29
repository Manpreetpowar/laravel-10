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
                @if($machine->operator)
                    <tr><th>Operator:</th><td>{{ $machine->operator->name }}</td></tr>
                @endif
            </table>
        </div>
        <div class="card-footer bg-white text-center border-0">
            @if($machine->operator && $machine->operator->id != auth()->user()->id)
                <button type="button" title="Operate Machine"
                        class="data-toggle-action-tooltip btn btn-primary btn-circle confirm-action-info"
                        data-confirm-title="Operate Machine" data-confirm-text="This machine is actively being operated. Proceed to take over?"
                        data-ajax-type="GET" data-confirm-button="Take Over" data-url="{{ url('floor-operations/operate-machines/'.$machine->id.'') }}">
                        Operate Machine
                    </button>

            @elseif($machine->operator && $machine->operator->id == auth()->user()->id)
                <a href="{{url('floor-operations/machines/'.$machine->id.'/operate')}}" class="btn btn-primary">
                    Operate Machine
                </a>
                <button type="button" title="Leave Machine"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle confirm-action-danger"
                    data-confirm-title="Leave Machine" data-confirm-text="Are you sure you want to end your session on this machine?"
                    data-ajax-type="GET" data-url="{{ url('floor-operations/machines/'.$machine->id.'/leave') }}">
                    Leave Machine
                </button>
            @else
                <button type="button" title="Operate Machine"
                        class="data-toggle-action-tooltip btn btn-primary btn-circle confirm-action-info"
                        data-confirm-title="Operate Machine" data-confirm-text="Are you sure you want to assume control as the operator?"
                        data-ajax-type="GET" data-confirm-button="Operate" data-url="{{ url('floor-operations/operate-machines/'.$machine->id.'') }}">
                        Operate Machine
                    </button>
            @endif
        </div>
    </div>
</div>
@endforeach
