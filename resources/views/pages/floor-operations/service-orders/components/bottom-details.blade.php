
@if(config('visibility.machines') == 'writable')
    <div class="form-row">
            @if($machine_normal)
                <div class="form-group col-md-2">
                    <label for="machine" class="font-weight-bold">Machine (Normal)</label>
                    <input type="text" class="form-control" id="machine" name="machine" placeholder="Machine" value="{{$machine_normal->machine_name}}" readonly>
                </div>
            @endif

            @if($machine_acc)
                <div class="form-group col-md-2">
                    <label for="machine_acc" class="font-weight-bold">Machine (ACC)</label>
                    <input type="text" class="form-control" id="machine_acc" name="machine_acc" placeholder="Machine (ACC)"  value="{{$machine_acc->machine_name}}" readonly>
                </div>
            @endif

            @if($machine_thik)
                {{-- <div class="form-group col-md-2">
                    <label for="machine_thik" class="font-weight-bold">Machine (Thik)</label>
                    <input type="text" class="form-control" id="machine_thik" name="machine_thik" placeholder="Machine (Thik)"  value="{{$machine_thik->machine_name}}" readonly>
                </div> --}}
            @endif
    </div>
@elseif(config('visibility.machines') == 'show')
    <div class="form-row">
            @if($machine_normal)
                <div class="form-group col-md-2">
                    <label for="machine" class="font-weight-bold">Machine (Normal)</label>
                    <div class="text">{{$machine_normal->machine_name}}</div>
                </div>
            @endif

            @if($machine_acc)
                <div class="form-group col-md-2">
                    <label for="machine_acc" class="font-weight-bold">Machine (ACC)</label>
                    <div class="text">{{$machine_acc->machine_name}}</div>
                </div>
            @endif

            @if($machine_thik)
                <div class="form-group col-md-2">
                    <label for="machine_thik" class="font-weight-bold">Machine (Thik)</label>
                    <div class="text">{{$machine_thik->machine_name}}</div>
                </div>
            @endif
    </div>
@endif

<div class="form-row">
    @if(config('visibility.qc_checker') == 'show')
        <div class="form-group col-md-2">
            <label for="qc_checker" class="font-weight-bold">QC Checked By</label>
            <div class="text">{{$serviceOrder->qc_checker->name}}</div>
        </div>
    @endif
</div>
