
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left">
      <h3> {!! $page['jobs']['pageTitle'] !!} </h3>
  </div>
  <div class="card-top-right">
      <div class="button-group">
      </div>
            <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                    actions-modal-button js-ajax-ux-request reset-target-modal-form" id="machineStatementFilterButton"
                    data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="Filter"
                    data-url="{{ url('machine-jobs/'.$machine->id.'/filter') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    Filter
            </button>
  </div>
</div>
<!-- Credit note head end -->

<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-bordered" id="machine-jobs-table" style="width:100%">
        <thead>
            <tr>
                <th>Job Code</th>
                <th>Client</th>
                <th>Operate Date</th>
                <th>Mileage</th>
                <th>Operator</th>
                <th>Manage</th>
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
      NX.DATATABLE['machineJobsTable'] = $('#machine-jobs-table').DataTable({
          processing: true,
          serverSide: true,
          dom: '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{ url('machine/'.$machine->id.'/jobs-list') }}",
          columns: [
              {data: 'service_order.service_order_id',orderable: false,},
              {data: 'service_order.client.client_name', orderable: false,},
              {data: 'created_at'},
              {data: 'total_run'},
              {data: 'operator.name',orderable: false, searchable: false},
              {data: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>
@endpush
