
<!-- Credit note head start -->
<div class="d-flex justify-content-between">
  <div class="card-top-left d-flex justify-content-between">
      <h3> {!! $page['creditNote']['pageTitle'] !!} </h3>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <h4 class="font-weight-bold"><mute>Balance: $<span id="credit-note-amount">{{ $page['creditNote']['creditBalance'] }}</span></mute></h4 class="">
  </div>
  <div class="card-top-right">
      <div class="button-group d-flex">
        <button type="button" class="data-toggle-tooltip list-actions-button btn btn-primary btn-page-actions waves-effect waves-dark
                              actions-modal-button js-ajax-ux-request reset-target-modal-form mr-1" id="creditNoteFilterButton"
              data-toggle="modal" data-target="#actionsModal"
              data-modal-title="Filter"
              data-url="{{ url('credit-notes/filter') }}"
              data-action-url="{{ url('credit-notes/filter') }}"
              data-loading-target="actionsModalBody" data-action-method="POST">
              Filter
          </button>

          <button type="button"
                  class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                  data-toggle="modal" data-target="#commonModal" data-url="{{ route('credit-notes.create',['client_id' => $client->id])}}"
                  data-loading-target="commonModalBody" data-modal-title="Add Credit Note"
                  data-action-url="{{ route('credit-notes.store') }}"
                  data-action-method="POST"
                  data-action-ajax-class=""
                  data-modal-size=""

                  data-save-button-class="" data-save-button-text="Add Credit Note" data-project-progress="0">
                  Add Credit Note
              </button>
      </div>
  </div>
</div>
<!-- Credit note head end -->

<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-bordered" id="credit-note-table" style="width:100%">
        <thead>
            <tr>
                <th>Note No</th>
                <th>Date Credited</th>
                <th>Amount</th>
                <th>Redemption Status</th>
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
      NX.DATATABLE['creditNoteTable'] = $('#credit-note-table').DataTable({
          processing: true,
          serverSide: true,
          dom: '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
          buttons: [{
            extend: 'csv',
            text: 'Export',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(.exclude-export)'
                // columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{ url('clients/credit-notes?client_id='.$client->id) }}",
          columns: [
              {data: 'note_id',searchable: false},
              {data: 'created_at'},
              {data: 'amount'},
              {data: 'status', searchable: false,orderable: false,},
              {data: 'action', orderable: false, searchable: false},
          ]
      });

    });

  </script>
@endpush
