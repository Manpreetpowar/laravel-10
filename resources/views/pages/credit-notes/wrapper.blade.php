@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.credit-notes.components.head')
    </div>
    <div class="card-body">
        @include('pages.credit-notes.components.table')
    </div>
</div>
    @include('pages.credit-notes.components.filter')
@endsection