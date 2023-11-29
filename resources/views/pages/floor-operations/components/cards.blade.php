<div class="row mt-5">
        @if(auth()->user()->hasRole('administrator','floor_manager'))
        <a href="{{url('floor-operations/driver')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Driver Module</h2>
                </div>
            </div>
        </a>
        <a href="{{ url('floor-operations/orders')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Floor Manager Module</h2>
                </div>
            </div>
        </a>
        @endif
        @if(auth()->user()->hasRole('administrator','floor_manager','operator'))
        <a href="{{url('floor-operations/machines')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Machining Module</h2>
                </div>
            </div>
        </a>

        <a href="{{url('floor-operations/pending-qc')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>QC Checks</h2>
                </div>
            </div>
        </a>
        @endif
        @if(auth()->user()->hasRole('administrator','floor_manager'))
        <a href="{{url('floor-operations/color-match-module')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Colour Matching Module</h2>
                </div>
            </div>
        </a>

        <a href="{{url('floor-operations/jobs-on-hold')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Jobs On Hold</h2>
                </div>
            </div>
        </a>

        <a href="{{url('floor-operations/all-jobs')}}" class="col-sm-3 mt-3 rm-underline">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Job Manager</h2>
                </div>
            </div>
        </a>

        @endif
    </div>