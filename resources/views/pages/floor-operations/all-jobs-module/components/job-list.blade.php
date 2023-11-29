
<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table" id="all-jobs-table" style="width:100%">
        <thead>
            <tr>
                <th>Job Code</th>
                <th>Client Name</th>
                <th>Driver</th>
                <th>Status</th>
                <th>Service Status</th>
                <th>Payment Term</th>
                <th>CompletedAt</th>
                <th>Delivered</th>
                <th>DeliveredAt</th>
                <th>Invoice</th>
                <th>Discount</th>
                <th>GST</th>
                <th>Amount</th>
                <th class="noExport">Manage</th>
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
      NX.DATATABLE['allJobsTable'] = $('#all-jobs-table').DataTable({
          processing: true,
          serverSide: true,
          ordering: false,
          dom: '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
          language: {
            searchPlaceholder: ''
          },
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: "thead th:not(.noExport)"
            }
          }],
          ajax: "{{url('floor-operations/all-jobs/list')}}",
          columns: [
              {data: 'service_order_id', orderable: false, searchable: false},
              {data: 'client.client_name', orderable: false, searchable: false},
              {data: 'driver.name', orderable: false, searchable: false},
              {data: 'status', orderable: false, searchable: false},
              {data: 'service_status', orderable: false, searchable: false},
              {data: 'client.payment_terms', orderable: false, searchable: false},
              {data: 'completed_date', orderable: false, searchable: false},
              {data: 'invoice.is_delivered', orderable: false, searchable: false},
              {data: 'deliver_date', orderable: false, searchable: false},
              {data: 'invoice.invoice_paid', orderable: false, searchable: false},
              {data: 'invoice.discount_amount', orderable: false, searchable: false},
              {data: 'invoice.gst_amount', orderable: false, searchable: false},
              {data: 'invoice.amount', orderable: false, searchable: false},
              {data: 'action', className: "order-table action", orderable: false, searchable: false},
          ]
      });
      
    });
  </script>
@endpush