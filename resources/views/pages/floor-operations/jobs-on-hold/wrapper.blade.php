@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
                @include('pages.floor-operations.jobs-on-hold.components.head')
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @include('pages.floor-operations.jobs-on-hold.components.no-credit-orders')
                </div>
                <div id="credit-notes" class="col-md-6">
                    @include('pages.floor-operations.jobs-on-hold.components.manual-print-orders')
                </div>
            </div>
        </div>
    </div>
@endsection
