<div class="card-top-left d-flex align-items-center">
    <a href="{{route('machines.index')}}" class="back text-danger h2 mr-2"><span class="mdi mdi-chevron-left"></span></a>
    <h3> {{$page['pageTitle']}} </h3>
</div>
<div class="card-top-right">
    <div class="button-group">
        <button type="submit" id="pageSubmitButton"
            class="btn btn-rounded-x btn-primary waves-effect text-left" data-url="{{route('machines.update',$machine->id)}}" data-loading-target="new-loader" data-loading-class="show"
            data-ajax-type="PUT" data-on-start-submit-button="disable" data-form-id="pageForm">Update Machine</button>
    </div>
</div>
