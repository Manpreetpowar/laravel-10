@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.accounting.expenses.components.head')
    </div>
    <div class="card-body">
        @include('pages.accounting.expenses.components.table')
    </div>
</div>
    @include('pages.accounting.expenses.components.filter')
@endsection