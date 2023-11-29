    <div class="card-top-left d-flex align-items-center">
    @if(isset($page['previousUrl']))<a href="{{ $page['previousUrl']}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>@endif
        <h3> {{$page['pageTitle']}} </h3>
    </div>
    <div class="card-top-right">
        <div class="button-group text-right">
            @if(isset($page['pageRightTitle']))<h3> {{$page['pageRightTitle']}} </h3>@endif
            
            @if(config('visibility.action_button_leave_machine') == 'show')
                <button type="button" title="Leave Machine"
                        class="data-toggle-action-tooltip btn btn-outline-danger btn-circle confirm-action-danger"
                        data-confirm-title="Leave Machine" data-confirm-text="Are you sure you want to end your session on this machine?"
                        data-ajax-type="GET" data-url="{{ url('floor-operations/machines/'.$machine->id.'/leave') }}">
                        Leave Machine
                    </button>
            @endif
        </div>
    </div>
