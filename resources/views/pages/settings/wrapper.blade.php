@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-top-left">
            <h3> {{$page['pageTitle']}} </h3>
        </div>
    </div>
    <div class="card-body">
        @include('pages.settings.components.form')
    </div>
</div>
@endsection
