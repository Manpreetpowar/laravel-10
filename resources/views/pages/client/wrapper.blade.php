@extends('layout.master')

@section('content')
<div class="card form_label_rspnsv">
    <div class="card-header d-flex justify-content-between ">
            @include('pages.client.components.head')
    </div>
    <div class="card-body">
        @include('pages.client.components.client-detail')
        <div class="row">
            <div class="col-md-6">
                @include('pages.client.components.service-orders')
            </div>
            <div id="credit-notes" class="col-md-6">
                @include('pages.client.components.credit-note')
            </div>
            <div class="col-md-9">
                @include('pages.client.components.account-statement')
            </div>
        </div>
    </div>
</div>
@endsection
