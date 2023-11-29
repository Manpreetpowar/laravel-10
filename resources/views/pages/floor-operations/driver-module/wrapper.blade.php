@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
            @include('pages.floor-operations.driver-module.components.head')
    </div>
    <div class="card-body">
        <div class="row">
            @include('pages.floor-operations.driver-module.components.cards')
        </div>
    </div>
</div>
@endsection