@extends('layout.master')

@section('content')
<div class="card" id="machine-module">
    <div class="card-header d-flex justify-content-between">
        @include('pages.machine.components.head')
    </div>
    <div class="card-body">
        @include('pages.machine.components.details')
        <div class="row">
            <div class="col-md-6">
                @include('pages.machine.components.job-list')
            </div>
            <div id="ad-hoc-service" class="col-md-6">
                @include('pages.machine.components.ad-hoc-service')
            </div>
        </div>
    </div>
</div>
@endsection
