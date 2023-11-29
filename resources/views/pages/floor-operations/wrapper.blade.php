@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.floor-operations.components.head')
    </div>
    <div class="card-body">
        @include('pages.floor-operations.components.cards')
    </div>
</div>
    <!-- include('pages.users.components.filter') -->
@endsection
