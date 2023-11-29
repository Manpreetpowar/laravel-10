
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left">
      <h3> {!! $page['accountStatement']['pageTitle'] !!} </h3>
  </div>
  <div class="card-top-right">
      <div class="button-group">
        <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                              actions-modal-button js-ajax-ux-request reset-target-modal-form" id="accountStatementFilterButton"
              data-toggle="modal" data-target="#actionsModal"
              data-modal-title="Filter"
              data-url="{{ url('account-statement/filter') }}"
              data-action-url="{{ url('account-statement/filter') }}"
              data-loading-target="actionsModalBody" data-action-method="POST">
              Filter
          </button>
      </div>
  </div>
</div>
<!-- Credit note head end -->

<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-bordered" id="account-statement-table" style="width:100%">
        <thead>
            <tr>

                <th>Job Code</th>
                <th>Date Created</th>
                <th>Due Amount</th>
                <th>Credit Amount</th>
                <th>Payable Amount</th>
                <th>Payment Status</th>
                <th class="exclude-export">Manage</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
  </div>
</div>
<!-- Credit note table end -->


@push('scripts')
  <script type="text/javascript">
    $(function () {
      NX.DATATABLE['accountStatementTable'] = $('#account-statement-table').DataTable({
          processing: true,
          serverSide: true,
          dom: '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {


                 // columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{ url('account-statement/'.$client->id.'/statement-list') }}",
          columns: [
              {data: 'account_statement_id',orderable: false},
              {data: 'created_at'},
              {data: 'due_amount'},
              {data: 'credit_amount'},
              {data: 'payable_amount'},
              {data: 'status', searchable: false,orderable: false},
              {data: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>
@endpush
