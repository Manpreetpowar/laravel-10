@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        @include('pages.floor-operations.service-orders.components.head')
    </div>
    <div class="card-body">
    <form action="" id="pageForm">
        @csrf
        @include('pages.floor-operations.service-orders.components.details')

        @if(config('visibility.color-match-form') == 'show')
            @include('pages.floor-operations.service-orders.components.color-match-form')
        @endif
        <div id="item-container">
            @include('pages.floor-operations.service-orders.components.items')
        </div>

        @include('pages.floor-operations.service-orders.components.bottom-details')
    </form>
    </div>
</div>
@if(config('visibility.dynamic_load_modal'))
<a href="javascript:void(0)" id="dynamic-qc-failed"
    class="show-modal-button reset-notify-modal-form js-ajax-ux-request js-ajax-ux-request" data-toggle="modal"
    data-target="#notifyModal" data-url="{{ url('/') }}"
    data-loading-target="main-top-nav-bar"></a>
@endif

@endsection