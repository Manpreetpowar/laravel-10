@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
            @include('pages.floor-operations.machine-module.components.head')
    </div>
    <div class="card-body">
        <div class="row">
            @include('pages.floor-operations.wrapper')
        </div>
    </div>
</div>
@endsection