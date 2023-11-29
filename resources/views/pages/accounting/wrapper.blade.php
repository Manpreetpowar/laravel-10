@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
            @include('pages.accounting.components.head')
    </div>
    <div class="card-body">
        <div class="row">
            @include('pages.accounting.components.revenue')
        </div>
        <div class="row mt-2">
            @include('pages.accounting.components.box-grid')
        </div>
    </div>
</div>
    @include('pages.accounting.components.filter')
@endsection
