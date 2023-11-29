@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-top-left">
            <h3> {{$page['pageTitle']}} </h3>
        </div>
        <div class="card-top-right">
            @include('pages.clients.components.head')
        </div>
    </div>
    <div class="card-body">
        @include('pages.clients.components.table')
    </div>
</div>
@endsection