@csrf
<input type="hidden" id="table-id" name="table-id" value="invoiceTable">
<input type="hidden" id="filter-button" name="filter-button" value="invoicesFilterButton">

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="date-start">Filter From</label>
        <input type="date" id="date-start" class="form-control yajra-filter-input" name="filter_date_start" value="{{$filter['filter_date_start'] ?? ''}}" data-column-index="1">
    </div>

    <div class="form-group col-md-6">
        <label for="date-end">Filter to</label>
        <input type="date" id="date-end" class="form-control yajra-filter-input" name="filter_date_end" value="{{$filter['filter_date_end'] ?? ''}}" data-column-index="1">
    </div>
    <div class="form-group col-md-6">
        <label for="amount-from">Amount From</label>
        <input type="number"  min="0" class="form-control yajra-filter-input" id="amount-from" name="filter_amount_from" value="{{$filter['filter_amount_from'] ?? ''}}" placeholder="100"  autocomplete="fsd">
    </div>
    <div class="form-group col-md-6">
        <label for="amount-to">Amount To</label>
        <input type="number"  min="0" class="form-control yajra-filter-input" id="amount-to" name="filter_amount_to" value="{{$filter['filter_amount_to'] ?? ''}}" placeholder="100"  autocomplete="fsd">
    </div>
    <div class="form-group col-md-6">
        <label for="client-name">Client Name</label>
            <select id="client" name="filter_client_id" class="form-control select2-basic yajra-filter-input" data-placeholder="Select Client">
                <option value="" disabled selected>Select Client</option>
                @foreach ($clients as $client )
                  <option value="{{$client->id}}" {{ isset($filter['filter_client_id']) ? runtimePreselected($client->id, $filter['filter_client_id']) : ''}}>{{$client->client_name}}</option>
                @endforeach
            </select>
    </div>


    <div class="form-group col-md-6">
        <label for="payment-status">Delivery Status</label>
            <select class="form-control select2-basic yajra-filter-input" name="filter_delivery_status" data-placeholder="Select Delivery Status">
                <option value="" disabled selected>Select Delivery Status</option>
                <option value="delivered" {{ isset($filter['filter_delivery_status']) ? runtimePreselected('delivered', $filter['filter_delivery_status']) : ''}}>Delivered</option>
                <!-- <option value="undelivered">Undelivered</option> -->
                <option value="pending" {{ isset($filter['filter_delivery_status']) ? runtimePreselected('pending', $filter['filter_delivery_status']) : ''}}>Inprocess</option>
            </select>
    </div>
    <div class="form-group col-md-6">
        <label for="payment-status">Payment Status</label>
            <select class="form-control select2-basic yajra-filter-input" name="filter_payment_status" data-placeholder="Select Payment Status">
                <option value="" disabled="" selected="">Select Status</option>
                <option value="paid" {{ isset($filter['filter_payment_status']) ? runtimePreselected('paid', $filter['filter_payment_status']) : ''}}>Paid</option>
                <option value="unpaid" {{ isset($filter['filter_payment_status']) ? runtimePreselected('unpaid', $filter['filter_payment_status']) : ''}}>Unpaid</option>
            </select>
    </div>
    <div class="form-group col-md-6">
        <label for="filter_payment_term">Payment Term</label>
        <select id="filter_payment_term" name="filter_payment_term" class="form-control select2-basic yajra-filter-input" data-placeholder="Select Payment Term">
                <option value="" disabled selected>Select Payment Term</option>
                @foreach(config('constants.payment_terms') as $key => $terms_label)
                    <option value="{{$key}}" {{ isset($filter['filter_payment_term']) ? runtimePreselected($key, $filter['filter_payment_term']) : ''}}>{{$terms_label}}</option>
                @endforeach
            </select>
    </div>
</div>

