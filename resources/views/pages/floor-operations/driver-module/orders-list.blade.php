@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
                @include('pages.floor-operations.driver-module.components.head')
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @include('pages.floor-operations.driver-module.components.pending-orders')
                </div>
                <div id="credit-notes" class="col-md-6">
                    @include('pages.floor-operations.driver-module.components.completed-orders')
                </div>
            </div>
        </div>
    </div>
@endsection
