
@if($data->attachment)
    <a href="{{ asset('storage/files/attachments/'.$data->attachment->attachment_directory.'/'.$data->attachment->attachment_filename)}}" class="btn btn-default btn-table-action"><i class="mdi mdi-download"></i></a>
@endif
    <a href="{{ url('service-orders/'.$data->id)}}'" class="btn btn-default btn-table-action"><i class="mdi mdi-eye"></i></a>
                    
    <button type="button" title="Delete Job" class="data-toggle-action-tooltip btn btn-default btn-table-action confirm-action-red"
                data-confirm-title="Delete Job" data-confirm-text="Are you sure you want to delete this job."
                data-ajax-type="DELETE" data-url="{{ url('service-orders/'. $data->id) }}">
                <i class="mdi single mdi-trash-can-outline"></i>
            </button>