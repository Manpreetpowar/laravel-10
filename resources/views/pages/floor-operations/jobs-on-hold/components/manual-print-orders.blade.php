
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left">
      <h3> {!! $page['manual-print']['pageTitle'] !!} </h3>
  </div>
  <div class="card-top-right">
      <div class="button-group">
      </div>
  </div>
</div>
<!-- Credit note head end -->
   
   <div class="row">
        <div class="col-lg-12 grid-margin">
            <table class="table table-striped table-without-header" id="manual-print-require-table" style="width:100%">
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
    
@push('scripts')
  <script type="text/javascript">
    $(function () {
      NX.DATATABLE['manualPrintRequireTable'] = $('#manual-print-require-table').DataTable({
          processing: true,
          serverSide: true,
          ordering: false,
          dom: '<"top"f>rt<"bottom"<"right"p>>',
          language: {
            searchPlaceholder: 'By Order No. or Customer'
          },
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{url('floor-operations/service-orders/manual-print-require-jobs')}}",
          columns: [
              {data: 'service_order_id', orderable: false, searchable: false},
              {data: 'action', className: "order-table action", orderable: false, searchable: false},
          ]
      });

      $('#manual-print-require-table_filter').prepend(`@include("pages.floor-operations.jobs-on-hold.components.manual-print-filters")`);

    });
  </script>
@endpush