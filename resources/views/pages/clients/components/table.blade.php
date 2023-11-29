<div class="row">
  <div class="col-lg-12 grid-margin">
        {!! $dataTable->table() !!}
  </div>
</div>
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
      $(document).ready(function () {
        setTimeout(() => {
          $('#clients-table_filter').prepend(`@include("pages.clients.components.filter")`);
          NXbootstrap();
        });
      });
    </script>
@endpush