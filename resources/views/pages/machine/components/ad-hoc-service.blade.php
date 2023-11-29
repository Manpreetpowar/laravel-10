
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left">
      <h3> {!! $page['service']['pageTitle'] !!} </h3>
  </div>
  <div class="card-top-right">
      <div class="button-group">
        <!-- <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark 
                              actions-modal-button js-ajax-ux-request reset-target-modal-form" id="creditNoteFilterButton"
              data-toggle="modal" data-target="#actionsModal"
              data-modal-title="Filter"
              data-url="{{ url('ad-hoc-services/filter') }}"
              data-action-url="{{ url('ad-hoc-services/filter') }}"
              data-loading-target="actionsModalBody" data-action-method="POST">
              Filter
          </button> -->

          <button type="button"
                  class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                  data-toggle="modal" data-target="#commonModal" data-url="{{ route('ad-hoc-services.create',['machine_id' => $machine->id])}}"
                  data-loading-target="commonModalBody" data-modal-title="Add Ad-Hoc Servicing"
                  data-action-url="{{ route('ad-hoc-services.store') }}"
                  data-action-method="POST"
                  data-action-ajax-class=""
                  data-modal-size=""
                   
                  data-save-button-class="" data-save-button-text="Save" data-close-button-text="Cancel" data-project-progress="0">
                  Add Servicing
              </button>
      </div>
  </div>
</div>
<!-- Credit note head end -->

<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-bordered" id="service-table" style="width:100%">
        <thead>
            <tr>
                <th>Ref No</th>
                <th>Reminder Date</th>
                <th>Servicing Date</th>
                <th>Remarks</th>
                <th>Document</th>
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
      NX.DATATABLE['adHocService'] = $('#service-table').DataTable({
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
          ajax: "{{ url('machine/ad-hoc-services?machine_id='.$machine->id) }}",
          columns: [
              {data: 'service_id',searchable: false},
              {data: 'reminder_date'},
              {data: 'service_date'},
              {data: 'remark', searchable: false},
              {data: 'document', orderable: false, searchable: false},
          ]
      });
      
    });
  </script>
@endpush