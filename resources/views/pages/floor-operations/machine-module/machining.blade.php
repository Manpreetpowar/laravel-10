@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
            @include('pages.floor-operations.machine-module.components.head')
    </div>
    <div class="card-body">
        <div class="row" id="machines-list-container">
            <div class="col-md-6">
                @include('pages.floor-operations.machine-module.components.pending-machines')
            </div>
        </div>
    </div>
</div>
@endsection