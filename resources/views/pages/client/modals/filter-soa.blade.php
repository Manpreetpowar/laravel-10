@csrf
<input type="hidden" id="table-id" name="table-id" value="accountStatementTable">
<input type="hidden" id="filter-button" name="filter-button" value="accountStatementFilterButton">

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
        <label for="filter_status">Payment Status</label>
            <select class="form-control select2-basic yajra-filter-input" id="filter_status" data-placeholder="Select Status" name="filter_status">
                <option value="" disabled selected>Select Status</option>
                <option value="paid" {{ isset($filter['filter_status']) ? runtimePreselected($filter['filter_status'], 'paid') : ''}}>Paid</option>
                <option value="unpaid" {{ isset($filter['filter_status']) ? runtimePreselected($filter['filter_status'], 'unpaid') : ''}}>Unpaid</option>
            </select>
    </div>
</div>
