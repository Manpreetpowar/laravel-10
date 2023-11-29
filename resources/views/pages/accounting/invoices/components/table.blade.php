
<!-- invoices  head start -->
  <!-- <div class="d-flex justify-content-between">
    <div class="card-top-left">
        {{-- <h3> {!! $page['accountStatement']['pageTitle'] !!} </h3> --}}
    </div>
    <div class="card-top-right">

        <div class="button-group">
          <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                                actions-modal-button js-ajax-ux-request reset-target-modal-form" id="accountStatementFilterButton"
                data-toggle="modal" data-target="#actionsModal"
                data-modal-title="Filter"
                data-url="{{ url('invoice/filter') }}"
                data-action-url="{{ url('invoice/filter') }}"
                data-loading-target="actionsModalBody" data-action-method="POST">
                Filter
            </button>
        </div>
    </div>
  </div> -->
  <!-- invoices  head end -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-striped" id="invoice-table" style="width:100%">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Invoice No.</th>
                <th>Client Name</th>
                <th>Client Payment Terms</th>
                <th>SO No.</th>
                <th>Date Completed</th>
                <th>Amount</th>
                <th>Item Delivered?</th>
                <th>Invoice Paid?</th>
                <th class="exclude-export">Manage</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
  </div>
</div>
@push('scripts')
  <script type="text/javascript">
    $(function () {
      NX.DATATABLE['invoiceTable'] = $('#invoice-table').DataTable({
          processing: true,
          serverSide: true,
          ordering: false,
          dom: '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(.exclude-export)'
                // columns: [ 9, ':visible', -1 ]
            }
          }],
          ajax: "{{url('accountings/invoices/list')}}",
          columns: [
              {data: null, orderable: true, "render": function (data, type, full, meta) {  return meta.row + 1; }},
              {data: 'invoice_number', orderable: false},
              {data: 'client.client_name', orderable: false},
              {data: 'client.payment_terms', orderable: false},
              {data: 'order.service_order_id', orderable: false},
              {data: 'order.completed_date', orderable: false},
              {data: 'amount', orderable: false},
              {data: 'is_delivered', orderable: false},
              {data: 'invoice_paid', orderable: false},
              {data: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>
@endpush
