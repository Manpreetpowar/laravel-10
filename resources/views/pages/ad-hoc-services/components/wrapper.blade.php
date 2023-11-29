@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-top-left">
            <h3> {{$page['pageTitle']}} </h3>
        </div>
        <div class="card-top-right">
            @include('pages.machines.components.head')
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="machines-list-container">
            @include('pages.machines.components.box-grid')
        </div>
    </div>
</div>
    @include('pages.machines.components.filter')
@endsection