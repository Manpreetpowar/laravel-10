@csrf
<input type="hidden" id="table-id" name="table-id" value="expenseTable">
<input type="hidden" id="filter-button" name="filter-button" value="expenseFilterButton">

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="date-start">Start Date </label>
        <input type="date" id="date-start" class="form-control yajra-filter-input" name="filter_date_start" value="{{$filter['filter_date_start'] ?? ''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="date-end">End Date </label>
        <input type="date" id="date-end" class="form-control yajra-filter-input" name="filter_date_end" value="{{$filter['filter_date_end'] ?? ''}}">
    </div>


</div>

