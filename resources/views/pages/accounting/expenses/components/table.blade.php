
<!-- Credit note table start -->
<div class="row">
  <div class="col-lg-12 grid-margin">
    <table class="table table-striped" id="expense-table" style="width:100%">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Expense No.</th>
                <th>Date</th>
                <th>Expense Name</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Remark</th>
                <th>Attachment</th>
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
      NX.DATATABLE['expenseTable'] = $('#expense-table').DataTable({
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
                // columns: [ 4, ':visible', -1 ]
            }
          }],
          ajax: "{{url('accountings/expenses/list')}}",
          columns: [
              {data: null, "render": function (data, type, full, meta) {  return meta.row + 1; }},
              {data: 'expense_id', orderable: false},
              {data: 'created_at', orderable: false, searchable: false},
              {data: 'expense_name', orderable: false},
              {data: 'category', orderable: false},
              {data: 'amount'},
              {data: 'remarks', orderable: false, searchable: false},
              {data: 'attachment', orderable: false, searchable: false},
              {data: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>
@endpush
