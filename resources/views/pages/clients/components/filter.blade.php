
        <label class="form-group">
            <select id="show_all_balance" name="filter_show_all_balance" class="form-control  yajra-custom-filter" data-table-id="clients-table" data-placeholder="Show All Balance">
                    <option value="" disabled selected>Show All Balance</option>
                    <option value="all">Show All Balance</option>
                    <option value="DESC">Outstanding Balance</option>
                    {{--
                         <option value="all">All</option>
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                    --}}
            </select>
        </label>

        <label class="form-group">
            <select id="payment_type" name="filter_payment_type" class="form-control  yajra-custom-filter" data-table-id="clients-table" data-placeholder="Payment Type">
                    <option value="" disabled selected>Payment Term</option>
                    <option value="all">All</option>
                    @foreach(config('constants.payment_terms') as $key => $terms_label)
                    <option value="{{$key}}" {{ isset($client) ? runtimePreselected($client->payment_terms, $key) : ''}}>{{$terms_label}}</option>
                    @endforeach
            </select>
        </label>

        <!-- <button type="button"
            class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
             actions-modal-button js-ajax-ux-request reset-target-modal-form mr-2" id="userFilter"
            data-toggle="modal" data-target="#actionsModal"
            data-modal-title="Filter"
            data-url="{{ url('clients/filter') }}"
            data-action-url="{{ url('clients/filter') }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            Filter
        </button> -->
