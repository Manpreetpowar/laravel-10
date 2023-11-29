@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
                @include('pages.floor-operations.color-module.components.head')
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    @include('pages.floor-operations.color-module.components.color-match-form')
                </div>
            </div>
        </div>
    </div>
@endsection
