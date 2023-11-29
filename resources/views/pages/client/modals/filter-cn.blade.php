@csrf
<input type="hidden" id="table-id" name="table-id" value="creditNoteTable">
<input type="hidden" id="filter-button" name="filter-button" value="creditNoteFilterButton">

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="date-start"> Start Date</label>
        <input type="date" id="date-start" class="form-control yajra-filter-input" name="filter_date_start" value="{{$filter['filter_date_start'] ?? ''}}" data-column-index="1">
    </div>

    <div class="form-group col-md-6">
        <label for="date-end"> End Date</label>
        <input type="date" id="date-end" class="form-control yajra-filter-input" name="filter_date_end" value="{{$filter['filter_date_end'] ?? ''}}" data-column-index="1">
    </div>
    <div class="form-group col-md-6">
        <label for="payment-status">Redemption Status</label>
            <select class="form-control select2-basic yajra-filter-input" data-placeholder="Select Redemption Status" name="filter_status">
                <option value="" disabled selected>Select Redemption Status</option>
                <option value="redeemed" {{ isset($filter['filter_status']) ? runtimePreselected($filter['filter_status'], 'redeemed') : ''}}>Redeemed</option>
                <option value="unredeemed" {{ isset($filter['filter_status']) ? runtimePreselected($filter['filter_status'], 'unredeemed') : ''}}>Unredeemed</option>
                <option value="partial" {{ isset($filter['filter_status']) ? runtimePreselected($filter['filter_status'], 'partial') : ''}}>Partial</option>
            </select>
    </div>
</div>
