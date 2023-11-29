
<div class="card-top-left">
    <h3> {{$page['pageTitle']}} </h3>
</div>
<div class="card-top-right">
    <div class="date-time-container">
        <h5>{{\Carbon\Carbon::now()->format('D, d M Y')}}</h5>
        <p>{{\Carbon\Carbon::now()->format('g:i A,')}} GMT+8</p>
    </div>
</div>