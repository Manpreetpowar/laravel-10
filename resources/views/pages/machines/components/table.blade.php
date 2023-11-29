
<div class="row">
  <div class="col-lg-12 grid-margin">
        {{ $dataTable->table() }}
  </div>
</div>
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush