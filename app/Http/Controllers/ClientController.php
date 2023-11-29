<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\AccountStatement;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\AccountStatementRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Repositories\MachineRepository;
use App\Repositories\DestroyRepository;
use App\DataTables\ClientsDataTable;
use App\Models\CreditNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Validator, Datatables;
use PDF, PDFMerger;

class ClientController extends Controller
{
    protected $clientRepo;
    protected $serviceOrderRepo;
    protected $accountStatementRepo;
    protected $invoiceRepo;
    protected $productRepo;
    protected $machineRepo;

    public function __construct(
        ClientRepository           $clientRepo,
        ServiceOrderRepository     $serviceOrderRepo,
        AccountStatementRepository $accountStatementRepo,
        InvoiceRepository          $invoiceRepo,
        ProductRepository          $productRepo,
        MachineRepository          $machineRepo

        ){
        $this->clientRepo           = $clientRepo;
        $this->serviceOrderRepo     = $serviceOrderRepo;
        $this->accountStatementRepo = $accountStatementRepo;
        $this->productRepo              = $productRepo;
        $this->invoiceRepo              = $invoiceRepo;
        $this->machineRepo              = $machineRepo;
        }

     /**
     * Display a listing of the resource.
     */
    public function index(ClientsDataTable $dataTable)
    {
        $page = $this->pageSetting('listing');

        return $dataTable->render('pages.clients.wrapper',compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $html = view('pages.clients.modals.add-edit-inc')->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action'   => 'replace',
            'value'    => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateClient'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'client_email' => ['required', 'string', 'unique:clients', 'email', 'max:255'],
            'credit_limit' => ['max:11'],
        ]);
        validationToaster($validator);

        $client_id = generateUniqueID(new Client, 4);

        request()->merge(['client_id' => $client_id]);
        $client    = $this->clientRepo->create();

        $clients = $this->clientRepo->get($client->id)->get();

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
    public function show(Client $client)
    {
        $page = $this->pageSetting('details',$client);

        request()->merge(['client_id',$client->id]);
        return view('pages.client.wrapper',compact('page','client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {

        $validator = Validator::make($request->all(), [
            'client_name'    => ['required', 'string', 'max:255'],
            'client_address' => ['required'],
            'poc_name'       => ['required', 'string', 'max:255'],
            'poc_contact'    => ['required'],
            'client_email'   => ['required', 'string', 'unique:clients,client_email,'.$client->id, 'email', 'max:255'],
            'credit_limit'   => ['required', 'max:11'],
            'discount'       => ['required', 'max:11'],
        ]);
        validationToaster($validator);

        request()->merge(['auto_send_email'=> $request->auto_send_email ? 1 : 0, 'apply_discount' => $request->apply_discount ? 1 : 0]);
        $client = $this->clientRepo->update($client->id);

        $clients = $this->clientRepo->get($client->id)->get();

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function viewJob($id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $inventories = $this->productRepo->get_variants()->get();

        $page = $this->pageSetting('job-details',$serviceOrder);

        config([
            'visibility.action_form_confirmed' => 'hidden',
            'visibility.action_form_save' => 'hidden',
            'visibility.action_item_add_more' => 'hidden',
            'visibility.action_qc_pass' => 'hidden',
            'visibility.action_download_invoice' => 'hidden',
            'visibility.service_order_form' => 'show',
            'visibility.select_item' => 'show',
            'visibility.remark' => 'show',
            'visibility.mileage' => 'show',
            'visibility.operator' => 'show',
            'visibility.price' => 'show',
            'visibility.machines' => 'show',
            'visibility.qc_checker' => 'show'
        ]);

        // Machine list
        $machine_normal = $machine_acc = $machine_thik = false;

        // Machine acc
        $acc = $serviceOrder->items->first(function($item){
            return $item->product_variant && $item->product_variant->product_option_type === 'acc';
        });
        if($acc && $acc->machine_id){
            $machine_acc = $this->machineRepo->get($acc->machine_id)->first();
        }

        // Machine normal
        $normal = $serviceOrder->items->first(function($item){
            return $item->product_variant && $item->product_variant->product_option_type === 'standard';
        });
        if($normal && $normal->machine_id){
            $machine_normal = $this->machineRepo->get($normal->machine_id)->first();
        }

        // Machine thik
        $thik = $serviceOrder->items->first(function($item){
            return $item->product_variant && $item->product_variant->product_option_type === 'tp';
        });
        if($thik && $thik->machine_id){
            $machine_thik = $this->machineRepo->get($thik->machine_id)->first();
        }

        return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories', 'machine_normal','machine_acc', 'machine_thik'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRepository $destroyRepo, $id)
    {
        $client = $this->clientRepo->get($id)->first();

        $destroyRepo->deleteClient($client->id);

        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function jobsList($id)
    {
        request()->merge(['filter_client_id'=>$id, 'filter_order_by_service_status'=> 'delivered', 'filter_order_by_status'=> 'completed',]);
        $orders = $this->serviceOrderRepo->get();
       return Datatables::eloquent($orders)
                ->addColumn('action', function($data) {
                    return '<a href="'.url('clients/service-orders/'.$data->id).'" class="btn btn-default  btn-table-action"><i class="mdi single mdi-eye"></i></a> <a href="'.url('floor-operations/download-invoice/'.$data->id).'" class="btn btn-default  btn-table-action"><i class="mdi single mdi-download"></i></a>';
                })
                ->editColumn('invoice.amount', function($data) {
                    return '$'.$data->invoice->amount;
                })
                ->editColumn('invoice.invoice_paid', function($data) {
                    $html = '<button type="button" title="Status Change" class="data-toggle-action-tooltip btn btn-default confirm-action-primary"'
                        .'data-confirm-title="Change Status" data-confirm-text="Are you sure you want to change status."'
                        .'data-ajax-type="POST" data-url="'. url('accountings/invoices/status-change/'. $data->invoice->id).'">'
                        .($data->invoice->invoice_paid ? '<span class="text-success">Paid</span>' : '<span class="text-danger">Unpaid</span>')
                    .'</button>';
                     return $html;
                })
                ->editColumn('deliver_date', function($data) {
                    return runtimeDate($data->deliver_date);
                })
                ->rawColumns(['action','invoice.invoice_paid'])
                ->make(true);
    }

    public function jobListfilter(){
        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $html = view('pages.client.modals.filter-job-list', compact('filter'))->render();
        $response['dom_html'][] = array(
            'selector' => '#actionsModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#actionsModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXFilter'];

        return response()->json($response,200);
    }

    /**
     * Page content.
     */
    public function downloadAllUnpaidInvoices($client_id){

        // Zip file name and path
        // Directory path where you want to create the ZIP file
        $directoryPath = public_path('storage/files/');

        // ZIP file name (e.g., 'unpaid_invoice_files.zip')
        $zipFileName = 'unpaid_invoice_files.zip';

        // Full path to the ZIP file
        $zipFilePath = $directoryPath . $zipFileName;
        $tempDirectory = storage_path('app/temp_pdf_files');

        // File::makeDirectory($tempDirectory, 0755, true);

        // Create a new ZIP archive
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Add all PDF files to the ZIP archive

            request()->merge(['filter_invoice_status'=> 'unpaid', 'filter_order_by_service_status'=> 'delivered', 'filter_order_by_status'=> 'completed', 'filter_client_id'=> $client_id]);
            $unpaidCompleted_jobs = $this->serviceOrderRepo->get()->get();
            foreach($unpaidCompleted_jobs as $job){
                if($job->attachment->count()){
                    $attachment = $job->attachment;
                }else{
                    event(new GenerateInvoiceEvent($job));
                    $job = $this->serviceOrderRepo->get($job->id)->first();
                    $attachment = $job->attachment;
                }
                $file_path = public_path('storage/files/attachments/' .$attachment->attachment_directory.'/'.$attachment->attachment_filename);

                $zip->addFile($file_path, $attachment->attachment_filename);
            }

            // Close the ZIP archive
            $zip->close();
            // Download the ZIP file
            if (file_exists($zipFilePath)) {
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Client Management Module'];
        if($type == 'details'){
            $page = ['pageTitle' => $data->client_name];
            $balance = $data->credit_notes;
            $page['creditNote'] = ['pageTitle' =>'Credit Notes'];
            $page['creditNote']['creditBalance'] =  $balance;
            $page['serviceJob'] = ['pageTitle' =>'Job List'];
            $page['accountStatement'] = ['pageTitle' =>'Statement of Accounts List'];
        }

        if($type == 'job-details'){
            $page['pageTitle'] = 'Service Order: '.$data->service_order_id;
            $page['previousUrl'] = route('clients.show' , $data->client_id);
        }

        return $page;
    }
}
