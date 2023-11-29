@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.accounting.invoices.components.head')
    </div>
    <div class="card-body">
        @include('pages.accounting.invoices.components.table')
    </div>
</div>
@endsection