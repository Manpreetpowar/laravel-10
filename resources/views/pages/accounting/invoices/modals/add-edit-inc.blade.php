@csrf
  <div class="form-row">
    <div class="form-group col-md-6">
        <label for="client_name">Client Name</label>
        <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Client Name" value="{{$client->client_name ?? ''}}" autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="client_email">Client Email</label>
        <input type="email" class="form-control" id="client_email" name="client_email" placeholder="Client Email"  value="{{$client->client_email ?? ''}}"  autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="poc_name">POC Name</label>
        <input type="text" class="form-control" id="poc_name" name="poc_name" placeholder="POC Name" value="{{$client->poc_name ?? ''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="poc_contact">POC Contact</label>
        <input type="number" type="number" min="0" class="form-control" id="poc_contact" name="poc_contact" placeholder="POC Contact" value="{{$client->poc_contact ?? ''}}" >
    </div>

    <div class="form-group col-md-12">
        <label for="client_address">Address</label>
        <input type="text" class="form-control" id="client_address" name="client_address" placeholder="Client Address" value="{{$client->client_address ?? ''}}" >
    </div>

    <div class="form-group col-md-6">
        <label for="payment_terms">Payment Terms</label>
        <select id="payment_terms" name="payment_terms" class="form-control">
                <option value="" disabled selected>Select Payment terms</option>
                @foreach(config('constants.payment_terms') as $key => $terms_label)
                <option value="{{$key}}" {{ isset($client) ? runtimePreselected($client->payment_terms, $key) : ''}}>{{$terms_label}}</option>
                @endforeach
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="credit_limit">Credit Limit</label>
        <input type="number" min="0" class="form-control" id="credit_limit" name="credit_limit" placeholder="Credit Limit" value="{{$client->credit_limit ?? ''}}" autocomplete="fsd">
    </div>

</div>
