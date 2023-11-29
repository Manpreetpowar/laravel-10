
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left">
      <h3> {!! $page['serviceJob']['pageTitle'] !!} </h3>
  </div>
  <div class="card-top-right">
      <div class="button-group">
        <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                              actions-modal-button js-ajax-ux-request reset-target-modal-form" id="jobListFilterButton"
              data-toggle="modal" data-target="#actionsModal"
              data-modal-title="Filter"
              data-url="{{ url('clients/service-order/filter') }}"
              data-action-url="{{ url('service-order/filter') }}"
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
    <table class="table table-bordered" id="client-jobs-table" style="width:100%">
        <thead>
            <tr>
                <th>Job Code</th>
                <th>Date Delivered</th>
                <th>Amount</th>
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
      NX.DATATABLE['clientJobsTable'] = $('#client-jobs-table').DataTable({
          processing: true,
          serverSide: true,
          dom: '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(.exclude-export)'
            }
          }],
          ajax: "{{ url('clients/'.$client->id.'/jobs-list') }}",
          columns: [
              {data: 'service_order_id',orderable: false},
              {data: 'deliver_date'},
              {data: 'invoice.amount',orderable: false},
              {data: 'invoice.invoice_paid',orderable: false, searchable: false},
              {data: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>
@endpush
