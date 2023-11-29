@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
            @include('pages.floor-operations.machine-module.components.head')
    </div>
    <div class="card-body">
        <div class="row" id="machines-list-container">
            @include('pages.floor-operations.machine-module.components.box-grid')
        </div>
    </div>
</div>
@endsection