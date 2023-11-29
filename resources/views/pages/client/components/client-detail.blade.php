<form action="{{route('clients.update',$client->id)}}" id="pageForm" method="post">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="client_name">Client Name</label>
            <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Client Name" value="{{$client->client_name ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-4">
            <label for="client_address">Client Address</label>
            <input type="text" class="form-control" id="client_address" name="client_address" placeholder="Client Address" value="{{$client->client_address ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="lifetime_revenue">Lifetime Revenue</label>
            <input type="text" class="form-control" id="lifetime_revenue" name="lifetime_revenue" placeholder="Total Revenue" value="{{$client->lifetime_revenue ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="poc_name">Client POC</label>
            <input type="text" class="form-control" id="poc_name" name="poc_name" placeholder="Client POC" value="{{$client->poc_name ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="poc_contact">POC Contact Number</label>
            <input type="text" class="form-control" id="poc_contact" name="poc_contact" placeholder="Client POC Number" value="{{$client->poc_contact ?? ''}}" autocomplete="fsd">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="client_email">Client Email Address</label>
            <input type="text" class="form-control" id="client_email" name="client_email" placeholder="Client Email" value="{{$client->client_email ?? ''}}" autocomplete="fsd">
        </div>
        <div class="form-group col-md-2">
            <label for="auto_send_email_label">Auto-send emails?</label>
            <div class="form-check m-0 p-0">
                <label for="auto_send_email" class="switch">
                    <input class="form-check-input" type="checkbox" id="auto_send_email" name="auto_send_email" value="option1" {{ isset($client) ? runtimePreChecked2($client->auto_send_email,1) : ''}}>
                    <span class="slider round"></span>
                    <span class="label yes">Yes</span>
                    <span class="label no">No</span>
                </label>
            </div>
        </div>
        <div class="form-group col-md-2">
            <label for="payment_terms">Client Payment Terms</label>
            <select id="payment_terms" name="payment_terms" class="form-control client-payment-terms">
                    <option value="" disabled selected>Select Payment terms</option>
                    @foreach(config('constants.payment_terms') as $key => $terms_label)
                    <option value="{{$key}}" {{ isset($client) ? runtimePreselected($client->payment_terms, $key) : ''}}>{{$terms_label}}</option>
                    @endforeach
                </select>
        </div>
        <div class="form-group col-md-2">
            <label for="credit_limit">Credit Amount</label>
            <input type="text" class="form-control credit-limit-input" id="credit_limit" name="credit_limit" placeholder="Credit Amount" value="{{$client->credit_limit ?? ''}}" @if($client->payment_terms != 'credit_limit') disabled @endif>
        </div>
        <div class="form-group col-md-2">
            <label for="apply_discount_label">Apply Corporate Discount?</label>
            <div class="form-check m-0 p-0">
            <label for="apply_discount" class="switch">
                <input class="form-check-input" type="checkbox" id="apply_discount" name="apply_discount" value="1" {{ isset($client) ? runtimePreChecked2($client->apply_discount,1) : ''}}>
                <span class="slider round"></span>
                <span class="label yes">Yes</span>
                <span class="label no">No</span>
            </label>
            </div>
        </div>
        <div class="form-group col-md-2">
            <label for="discount">Discount Amount (in %)</label>
            <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount Amount" value="{{$client->discount ?? ''}}" autocomplete="fsd">
        </div>
    </div>
</form>
