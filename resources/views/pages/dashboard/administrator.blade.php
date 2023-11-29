@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            @include('pages.dashboard.components.administrator')
        </div>
    </div>
</div>
@endsection