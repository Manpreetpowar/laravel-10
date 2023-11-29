
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left">
      <h3> {!! $page['pending-machine']['pageTitle'] !!} </h3>
  </div>
  <div class="card-top-right">
      <div class="button-group">
      </div>
  </div>
</div>
<!-- Credit note head end -->

<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-striped table-without-header" id="pending-orders-table" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th></th>
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
      NX.DATATABLE['pendingOrdersTable'] = $('#pending-orders-table').DataTable({
          processing: true,
          serverSide: true,
          ordering: false,
          dom: '<"top"f>rt<"bottom"<"right"p>>',
          language: {
            searchPlaceholder: 'Order No. or Customer'
          },
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{url('floor-operations/pending-orders/list')}}",
          columns: [
              {data: 'service_order_id', orderable: false, searchable: false},
              {data: 'action', className: "order-table action", orderable: false, searchable: false},
          ]
      });

    });
  </script>
@endpush
