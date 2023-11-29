@csrf
<input type="hidden" id="table-id" name="table-id" value="machineJobsTable">
<input type="hidden" id="filter-button" name="filter-button" value="machineStatementFilterButton">
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="date-start"> Start Date</label>
        <input type="date" id="date-start" class="form-control yajra-filter-input" name="filter_date_start" value="{{$filter['filter_date_start'] ?? ''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="date-end"> End Date</label>
        <input type="date" id="date-end" class="form-control yajra-filter-input" name="filter_date_end" value="{{$filter['filter_date_end'] ?? ''}}">
    </div>
    <div class="form-group col-md-6">
        <label for="mileage-from">Mileage From</label>
        <input type="number" min="0" class="form-control yajra-filter-input" id="mileage-from" name="filter_mileage_from" value="{{$filter['filter_mileage_from'] ?? ''}}" placeholder="100"  autocomplete="fsd">
    </div>
    <div class="form-group col-md-6">
        <label for="mileage-to">Mileage To</label>
        <input type="number" min="0" class="form-control yajra-filter-input" id="mileage-to" name="filter_mileage_to" value="{{$filter['filter_mileage_to'] ?? ''}}" placeholder="100"  autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="client-name">Client Name</label>
            <select id="client" class="form-control select2-basic yajra-filter-input" name="filter_client_id" data-placeholder="Select Client">
                <option value="" disabled selected>Select Client</option>
                @foreach($clients as $client)
                    <option value="{{$client->id}}" {{ isset($filter['filter_client_id']) ? runtimePreselected($filter['filter_client_id'], $client->id) : ''}}>{{$client->client_name}}</option>
                @endforeach
            </select>
    </div>

    <div class="form-group col-md-6">
        <label for="operator-name">Operator Name</label>
        <select id="operator" class="form-control select2-basic yajra-filter-input" name="filter_operator_id" data-placeholder="Select Operator">
                <option value="" disabled selected>Select Operator</option>
                @foreach($operators as $operator)
                    <option value="{{$operator->id}}" {{ isset($filter['filter_operator_id']) ? runtimePreselected($filter['filter_operator_id'], $operator->id) : ''}}>{{$operator->name}}</option>
                @endforeach
            </select>
    </div>
</div>

