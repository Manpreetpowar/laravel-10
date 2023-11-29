@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
                @include('pages.floor-operations.all-jobs-module.components.head')
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    @include('pages.floor-operations.all-jobs-module.components.job-list')
                </div>
            </div>
        </div>
    </div>
@endsection