<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Repositories\ClientRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\MachineRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\AttachmentRepository;
use App\DataTables\Driver\OrdersDataTable;
use App\Events\GenerateInvoiceEvent;
use App\Mail\JobInvoice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Validator, Auth, Datatables, PDF, PDFMerger;

class JobsOnHoldController extends Controller
{
    protected $clientRepo;
    protected $serviceOrderRepo;
    protected $productRepo;
    protected $userRepo;
    protected $machineRepo;
    protected $invoiceRepo;

    public function __construct(ClientRepository $clientRepo,
                                ServiceOrderRepository $serviceOrderRepo,
                                ProductRepository $productRepo,
                                UserRepository $userRepo,
                                MachineRepository $machineRepo,
                                InvoiceRepository $invoiceRepo,
                                AttachmentRepository $attachmentRepo){
        $this->clientRepo = $clientRepo;
        $this->serviceOrderRepo = $serviceOrderRepo;
        $this->productRepo = $productRepo;
        $this->userRepo = $userRepo;
        $this->machineRepo = $machineRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->attachmentRepo = $attachmentRepo;
    }


    /**
    * Display a listing of the resource.
    */
    public function noCreditJobs()
    {
        $user = Auth::user();

        request()->merge(['filter_order_by_status' => 'on-hold', 'filter_order_by_service_status' => 'no-credit']);

        $orders = $this->serviceOrderRepo->get();
        return Datatables::eloquent($orders)
                 ->addColumn('action', function($data) {
                     return '<div class="d-flex align-items-center justify-content-end"><span class="status mr-5 text-danger">'.config('constants.order_status')[$data->status].' - '.config('constants.service_status')[$data->service_status].'</span><a href="'.url('floor-operations/on-hold/service-orders/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a></div>';
                 })
                 ->editColumn('service_order_id', function($data) {
                     return '<h3 class="m-0"><b>'.$data->service_order_id.'</b></h3><p class="m-0">Customer Name: '. $data->client->client_name.'</p><p class="m-0">'.$data->total_pieces.' Pcs</p>';
                 })
                 ->rawColumns(['action', 'service_order_id'])
                 ->make(true);
    }

    /**
    * Display a listing of the resource.
    */
    public function manualPrintRequireJobs()
    {
        $user = Auth::user();

        request()->merge(['filter_order_by_status' => 'on-hold', 'filter_order_by_service_status' => 'manual-print-required']);

        $orders = $this->serviceOrderRepo->get();
        return Datatables::eloquent($orders)
                 ->addColumn('action', function($data) {
                     return '<div class="d-flex align-items-center justify-content-end"><span class="status mr-5 text-warning">'.config('constants.order_status')[$data->status].' - '.config('constants.service_status')[$data->service_status].'</span><a href="'.url('floor-operations/on-hold/service-orders/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a></div>';
                 })
                 ->editColumn('service_order_id', function($data) {
                     return '<h3 class="m-0"><b>'.$data->service_order_id.'</b></h3><p class="m-0">Customer Name: '. $data->client->client_name.'</p><p class="m-0">'.$data->total_pieces.' Pcs</p>';
                 })
                 ->rawColumns(['action', 'service_order_id'])
                 ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function showOnHoldOrders($id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $inventories = $this->productRepo->get_variants()->get();

        $page = $this->pageSetting('on-hold-order-details',$serviceOrder);


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
        ]);

        if($serviceOrder->service_status == 'no-credit'){
            config(['visibility.action_form_generate_invoice' => 'show']);
        }elseif($serviceOrder->service_status == 'manual-print-required'){
            config(['visibility.action_form_manual_print' => 'show']);
        }

        config(['visibility.dynamic_load_modal' => true]);

        return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories', 'machine_normal','machine_acc', 'machine_thik'));
    }

    /**
     * qc pass
     */
    public function generateInvoice($id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();
        $invoce = $this->invoiceRepo->generate($serviceOrder->id);

        if($invoce['status'] == 'success'){

            if($invoce['type'] == 'credit_limit'){
                request()->merge(['credit_limit' => $serviceOrder->client->credit_limit - $invoce['amount']]);
                $this->clientRepo->update($serviceOrder->client->id);
            }

            event(new GenerateInvoiceEvent($serviceOrder));
            // send invoice to client
            Mail::to($serviceOrder->client->client_email)->send(new JobInvoice($serviceOrder));

            request()->merge(['service_status' => 'out-for-delivery', 'status' => 'completed', 'qc_check_id'=>Auth::id(), 'completed_date' => \Carbon\Carbon::now()->format('Y-m-d')]);

            // notice
            request()->session()->flash('success-notification-longer', 'Request has been completed');
            //redirect to view page
            $response['delayed_redirect_url'] = url("floor-operations/jobs-on-hold");

        }elseif(in_array($invoce['status'],['no-credit','manual-print-required'])){

            // SHOW MODAL------
            $response['dom_action'][] = [
                'selector' => '#dynamic-qc-failed',
                'action' => 'trigger',
                'value' => 'click',
            ];
            // notifyModalBody

            $html = view('pages.floor-operations.qc-module.modals.qc-failed')->render();
            $response['dom_html'][] = array(
                'selector' => '#notifyModalBody',
                'action' => 'replace',
                'value' => $html);

            $response['dom_attributes'][] = [
                'selector' => '#notifyModalSubmitButton',
                'attr' => 'href',
                'value' => url("floor-operations/jobs-on-hold"),
            ];

            //show modal footer
            $response['dom_visibility'][] = array('selector' => '#notifyModalFooter', 'action' => 'show');

            request()->merge(['service_status' => $invoce['status'], 'status' => 'on-hold']);
        }

        $this->serviceOrderRepo->update($id);

        //ajax response & view
        return response()->json($response);
    }

    /**
     * qc pass
     */
    public function markForDelivery($id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        request()->merge(['invoice_printed' => true]);
        $invoce = $this->invoiceRepo->generate($serviceOrder->id);

        if($invoce['status'] == 'success'){

            if($invoce['type'] == 'credit_limit'){
                request()->merge(['credit_limit' => $serviceOrder->client->credit_limit - $invoce['amount']]);
                $this->clientRepo->update($serviceOrder->client->id);
            }

            event(new GenerateInvoiceEvent($serviceOrder));
            // send invoice to client
            Mail::to($serviceOrder->client->client_email)->send(new JobInvoice($serviceOrder));

            request()->merge(['service_status' => 'out-for-delivery', 'status' => 'completed', 'qc_check_id'=>Auth::id(), 'completed_date' => \Carbon\Carbon::now()->format('Y-m-d')]);

            // notice
            request()->session()->flash('success-notification-longer', 'Request has been completed');
            //redirect to view page
            $response['delayed_redirect_url'] = url("floor-operations/jobs-on-hold");

        }elseif(in_array($invoce['status'],['no-credit','manual-print-required'])){

            // SHOW MODAL------
            $response['dom_action'][] = [
                'selector' => '#dynamic-qc-failed',
                'action' => 'trigger',
                'value' => 'click',
            ];

            // notifyModalBody
            $html = view('pages.floor-operations.qc-module.modals.qc-failed')->render();
            $response['dom_html'][] = array(
                'selector' => '#notifyModalBody',
                'action' => 'replace',
                'value' => $html);

            $response['dom_attributes'][] = [
                'selector' => '#notifyModalSubmitButton',
                'attr' => 'href',
                'value' => url("floor-operations/jobs-on-hold"),
            ];

            //show modal footer
            $response['dom_visibility'][] = array('selector' => '#notifyModalFooter', 'action' => 'show');

            request()->merge(['service_status' => $invoce['status'], 'status' => 'on-hold']);
        }

        $this->serviceOrderRepo->update($id);

        //ajax response & view
        return response()->json($response);
    }

    /**
     * mark all for delivery of manual printing order
     */
    public function markAllDelivery()
    {
        request()->merge(['filter_order_by_service_status'=>'manual-print-required']);
        $serviceOrders = $this->serviceOrderRepo->get()->get();

        if(!$serviceOrders->count()){
            // notice
            $response['notification'] = array('type' => 'error', 'value' => 'Nothing to mark delivery');

            //ajax response & view
            return response()->json($response);
        }

        foreach($serviceOrders as $serviceOrder){

            request()->merge(['invoice_printed' => true]);
            $invoce = $this->invoiceRepo->generate($serviceOrder->id);

            if($invoce['status'] == 'success'){

                if($invoce['type'] == 'credit_limit'){
                    request()->merge(['credit_limit' => $serviceOrder->client->credit_limit - $invoce['amount']]);
                    $this->clientRepo->update($serviceOrder->client->id);
                }

                event(new GenerateInvoiceEvent($serviceOrder));
                // send invoice to client
                Mail::to($serviceOrder->client->client_email)->send(new JobInvoice($serviceOrder));
    
                request()->merge(['service_status' => 'out-for-delivery', 'status' => 'completed', 'qc_check_id'=>Auth::id(), 'completed_date' => \Carbon\Carbon::now()->format('Y-m-d')]);

                //notice
                $response['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

            }elseif(in_array($invoce['status'],['no-credit','manual-print-required'])){

                request()->merge(['service_status' => $invoce['status']]);

                //notice
                $response['notification'] = array('type' => 'error', 'value' => 'Some customers has no credit that can not be mark for delivery.');
            }

            request()->merge(['status' => 'on-hold']);

            $this->serviceOrderRepo->update($serviceOrder->id);

        }

        $response['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($response);
    }

    /**
     * print invoice
     */
    public function printInvoice($id)
    {
        $pdf_data = config('pdf-data');
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        if(request('view') == 'preview'){
            return view('pdf.service-order-invoice', compact('serviceOrder','pdf_data'))->render();
        }
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="'.$serviceOrder->service_order_id.'-'.time().'.pdf'.'"');
        header('Content-Transfer-Encoding: binary');

        $pdf = PDF::loadView('pdf.service-order-invoice', compact('serviceOrder','pdf_data'));
        return $pdf->stream($serviceOrder->service_order_id.'-'.time().'.pdf', array("Attachment" => false));
    }

    /**
     * print all invoices of manual printing
     */
    public function printAllInvoices()
    {
        $pdf_data = config('pdf-data');
        request()->merge(['filter_order_by_service_status'=>'manual-print-required']);
        $serviceOrders = $this->serviceOrderRepo->get()->get();

        if(!$serviceOrders->count()){
            // notice
            request()->session()->flash('error-notification-longer', 'Nothing to print');
            return redirect()->back();
        }

        if(request('view') == 'preview'){
            foreach($serviceOrders as $serviceOrder){
                echo view('pdf.service-order-invoice', compact('serviceOrder','pdf_data'))->render();
            }
            die;
        }

        $fileName = 'manual-print-'.time();
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="'.$fileName.'.pdf'.'"');
        header('Content-Transfer-Encoding: binary');

        $combinedPdf = PDFMerger::init();
        // $combinedPdf->setTimeout(1200);
        foreach ($serviceOrders as $serviceOrder) {
            $pdf = PDF::loadView('pdf.service-order-invoice', compact('serviceOrder','pdf_data'));
            $combinedPdf->addString($pdf->output(),'all');
        }

        $combinedPdf->merge();
        $combinedPdf->setFileName($fileName.'.pdf');
        echo $combinedPdf->output(); exit;
        // $combinedPdf->download();
    }

    /**
     * download invoice
     */
    public function downloadInvoice($id)
    {
        $pdf_data = config('pdf-data');
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        event(new GenerateInvoiceEvent($serviceOrder));

        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $inv = $serviceOrder->attachment->attachment_directory.'/'.$serviceOrder->attachment->attachment_filename;

        return response()->download(public_path('storage/files/attachments/' .$serviceOrder->attachment->attachment_directory.'/'.$serviceOrder->attachment->attachment_filename));

        // if(request('view') == 'preview'){
        //     return view('pdf.service-order-invoice', compact('serviceOrder','pdf_data'))->render();
        // }
        // $pdf = PDF::loadView('pdf.service-order-invoice', compact('serviceOrder','pdf_data'));
        // return $pdf->download($serviceOrder->service_order_id.'-'.time().'.pdf');
    }

    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Driver Module'];


        if($type == 'on-hold-order-details'){
            $page['pageTitle'] = 'Service Order: '.$data->service_order_id;
            $page['previousUrl'] = url('floor-operations/jobs-on-hold');
        }

        return $page;
    }
}
