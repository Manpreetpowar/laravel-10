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
use App\Events\SendInvoiceEvent;
use App\Mail\JobInvoice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Validator, Auth, Datatables;

class QcController extends Controller
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
    public function pendingQc()
    {
        request()->merge(['filter_order_by_service_status' => 'qc-pending']);
        $pendingOrders = $this->serviceOrderRepo->get();

        return Datatables::eloquent($pendingOrders)
                ->addColumn('action', function($data) {
                    return '<a href="'.url('floor-operations/service-orders/qc-check/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a>';
                })
                ->editColumn('service_order_id', function($data) {
                    return '<h3 class="m-0"><b>'.$data->service_order_id.'</b></h3><p class="m-0">Customer Name: '. $data->client->client_name.'</p><p class="m-0">'.$data->total_pieces.' Pcs</p>';
                })
                ->rawColumns(['action', 'service_order_id'])
                ->make(true);
    }


    /**
     * qc check
     */
    public function qcCheck($id)
    {
        config([
            'visibility.action_form_confirmed' => 'hidden',
            'visibility.action_form_save' => 'hidden',
            'visibility.action_item_add_more' => 'hidden',
            'visibility.service_order_form' => 'hidden',
            'visibility.action_qc_pass' => 'show',
            'visibility.select_item' => 'show',
            'visibility.remark' => 'show',
            'visibility.mileage' => 'show',
            'visibility.operator' => 'show',
            'visibility.price' => 'show',
            'visibility.machines' => 'show',
        ]);

        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

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

        config(['visibility.dynamic_load_modal' => true]);

        $page = $this->pageSetting('qc-check',$serviceOrder);

        return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder', 'machine_normal', 'machine_acc', 'machine_thik'));
    }

    /**
     * qc pass
     */
    public function qcPass($id)
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
            $response['delayed_redirect_url'] = url("floor-operations/pending-qc");

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
                'value' => url("floor-operations/pending-qc"),
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
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Driver Module'];
        
        if($type == 'qc-check'){
            $page = ['pageTitle' => 'Service Order: '.$data->service_order_id,
                    'previousUrl' => url('floor-operations/pending-qc')
                    ];
        }
        
        return $page;
    }
}
