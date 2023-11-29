<div class="row">
        <div class="col-md-4">
          <h3>Daily Revenue: {{$daily_revenue}}</h3>
          <h3>Current Outstanding Jobs: {{$current_outstanding_job}}</h3>
        </div>
        <div class="col-md-4">
            <h3>Monthly Revenue: {{$monthly_revenue}}</h3>
            <h3>Monthly Job Count: {{$job_count}}</h3>
        </div>
        <div class="col-md-4 text-right">
          <span>
            <h3>{{\Carbon\Carbon::now()->format('D, d M Y')}}</h3>
            <h4>{{\Carbon\Carbon::now()->format('g:i A,')}} GMT+8</h4>
          </span>
        </div>
      </div>

      <div class="row mt-5">
        @if(auth()->user()->hasRole('administrator'))
        <a href="{{route('users.index')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Users ({{$user_count}})</h2>

                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->hasRole('administrator','account'))
        <a href="{{route('clients.index')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Clients ({{$client_count}})</h2>

                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->hasRole('administrator'))
        <a href="{{route('machines.index')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Machines ({{$machine_count}})</h2>

                </div>
            </div>
        </a>

        <a href="{{url('inventories')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Inventory ({{$inventry_count}})</h2>
                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->hasRole('administrator','floor_manager'))
        <a href="{{url('floor-operations')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Floor Operations</h2>
                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->hasRole('administrator','account'))
        <a href="{{route('accountings.index')}}" class="col-sm-3">
            <div class="card card-statistics">
                <div class="card-body btn-primary mt-3">
                    <h2>Accounting</h2>
                </div>
            </div>
        </a>
        @endif
      </div>
