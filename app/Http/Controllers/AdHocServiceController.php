<?php

namespace App\Http\Controllers;

use App\Models\AdHocService;
use App\Repositories\MachineRepository;
use App\Repositories\AdHocServiceRepository;
use App\Repositories\AttachmentRepository;
use Illuminate\Http\Request;
use Validator, Datatables;

class AdHocServiceController extends Controller
{
    protected $serviceRepo;
    protected $attachmentRepo;
    protected $machineRepo;

    public function __construct(
        AdHocServiceRepository $serviceRepo,
        MachineRepository      $machineRepo,
        AttachmentRepository   $attachmentRepo,
        ){
        $this->serviceRepo    = $serviceRepo;
        $this->attachmentRepo = $attachmentRepo;
        $this->machineRepo    = $machineRepo;

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

     /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $service = $this->machineRepo->get_services();

       return Datatables::eloquent($service)
                ->editColumn('reminder_date', function ($data) {
                    return $data->reminder_date ? \Carbon\Carbon::parse($data->reminder_date )->format('d M Y') : '-';
                })
                ->editColumn('service_date', function ($data) {
                    return $data->service_date ?
                        \Carbon\Carbon::parse($data->service_date )->format('d M Y')
                    :
                        '<a class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal" data-url="'.url('ad-hoc-services/update-date/'.$data->id).'"
                        data-loading-target="commonModalBody" data-modal-title="Service Date"
                        data-action-url="'.url('ad-hoc-services/update-date/'.$data->id).'"
                        data-action-method="POST"
                        data-action-ajax-class=""
                        data-modal-size="sm"

                        data-save-button-class="" data-save-button-text="Update" data-project-progress="0"><u class="text-danger">Not Serviced</u></a>';;
                })
                ->editColumn('document', function ($data) {
                    return $data->attachments
                    ?
                        '<a href="'.asset('storage/files/attachments/'.$data->attachments->attachment_directory.'/'.$data->attachments->attachment_filename).'" download>file.'.$data->attachments->attachment_extension.'</a>'
                    :
                        '<a class="btn-link edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal" data-url="'.url('ad-hoc-services/upload-file/'.$data->id).'"
                        data-loading-target="commonModalBody" data-modal-title="Upload Document"
                        data-action-url="'.url('ad-hoc-services/upload-file/'.$data->id).'"
                        data-action-method="POST"
                        data-action-ajax-class=""
                        data-modal-size="sm"

                        data-save-button-class="" data-save-button-text="Upload" data-project-progress="0"><u>Upload</u></a>';
                })
                ->editColumn('remark', function ($data) {
                   return '<form id="remarkForm-'.$data->id.'"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="text" class="remark-input form-control" name="remark" value="'.$data->remark.'"'
                    .'data-url="'.url('ad-hoc-service/update-remark/'.$data->id.'').'" data-ajax-type="post" data-form-id="remarkForm-'.$data->id.'"></form>';
                })
                ->filterColumn('created_at', function ($query, $date) {
                })
                ->rawColumns(['service_date','remark', 'document'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $machine = $this->machineRepo->get(request('machine_id'))->first();

        $html = view('pages.ad-hoc-services.modals.add-edit-inc',compact('machine'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateService'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machine_id' => ['required'],
        ], ['machine_id.required'=> 'Something is wrong!']);
        validationToaster($validator);

        $service_id =  generateUniqueID(new AdHocService, 4);
        request()->merge(['service_id' => $service_id]);

        $service = $this->serviceRepo->create();

        if(request()->has('attachments')){
            foreach(request('attachments') as $key => $value){
                $data = [
                    'attachment_id' => $key,
                    'attachmentresource_type' => 'service',
                    'attachmentresource_id' => $service->id,
                    'attachment_directory' => $key,
                    'attachment_uniqiueid' => $key,
                    'attachment_filename' => $value,

                ];

                if(!$file = $this->attachmentRepo->process($data)){
                    abort(409, 'Something went wrong');
                }
            }
        }

       $ajax['postrun_functions'][] = [
        'value' => 'NXDatatableReload'];
        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdHocService $adHocService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdHocService $adHocService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdHocService $adHocService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdHocService $adHocService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateRemark(Request $request, $id)
    {
        $service = $this->serviceRepo->update($id);
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');
        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Update the specified resource in storage.
     */
    public function uploadFileModal($id)
    {
        $service = $this->serviceRepo->get($id)->first();

        $html = view('pages.ad-hoc-services.modals.file-upload',compact('service'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateService'];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function uploadFile($id)
    {
        $service = $this->serviceRepo->get($id)->first();

        if(request()->has('attachments')){
            foreach(request('attachments') as $key => $value){
                $data = [
                    'attachment_id' => $key,
                    'attachmentresource_type' => 'service',
                    'attachmentresource_id' => $service->id,
                    'attachment_directory' => $key,
                    'attachment_uniqiueid' => $key,
                    'attachment_filename' => $value,

                ];

                if(!$file = $this->attachmentRepo->process($data)){
                    abort(409, 'Something went wrong');
                }
            }
        }

        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        return response()->json($ajax,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDateModal($id)
    {
        $service = $this->serviceRepo->get($id)->first();

        $html = view('pages.ad-hoc-services.modals.service-date',compact('service'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateService'];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDate($id)
    {
        $service = $this->serviceRepo->update($id);

        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        return response()->json($ajax,200);
    }
}
