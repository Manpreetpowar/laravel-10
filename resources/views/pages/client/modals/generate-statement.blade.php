@csrf
<input type="hidden" name="client_id" value="{{$client->id}}">
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="date-start"> Start Date</label>
        <input type="date" id="date-start" class="form-control" name="date_start" value="{{$date[0]}}" readonly>
    </div>

    <div class="form-group col-md-6">
        <label for="date-end"> End Date</label>
        <input type="date" id="date-end" class="form-control" name="date_end" value="{{$date[1]}}" readonly>
    </div>

</div>
