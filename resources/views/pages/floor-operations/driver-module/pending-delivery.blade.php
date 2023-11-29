@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.floor-operations.driver-module.components.head')
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 grid-margin">
                <table class="table table-striped table-without-header" id="driver-delivery-orders-table" style="width:100%">
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
    </div>
</div>
    <!-- include('pages.users.components.filter') -->
@endsection


@push('scripts')
  <script type="text/javascript">
    $(function () {
      NX.DATATABLE['driverDeliveryOrdersTable'] = $('#driver-delivery-orders-table').DataTable({
          processing: true,
          serverSide: true,
          ordering: false,
          dom: '<"top"f>rt<"bottom"<"right"p>>',
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{url('driver/service-orders/pending-delivery/list')}}",
          columns: [
              {data: 'service_order_id', orderable: false, searchable: false},
              {data: 'action', className: "order-table action", orderable: false, searchable: false},
          ]
      });
      
    });
  </script>
@endpush