@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.floor-operations.qc-module.components.head')
    </div>
    <div class="card-body">
        <div class="row" id="qcs-list-container">
            <div class="col-md-6">
                @include('pages.floor-operations.qc-module.components.pending-qc')
            </div>
        </div>
    </div>
</div>
@endsection