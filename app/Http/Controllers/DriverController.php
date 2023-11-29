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
use Illuminate\Http\Request;
use Validator, Auth, Datatables;

class DriverController extends Controller
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
    public function driverOrders()
    {
        $page = $this->pageSetting('driver-view-orders');
        return view('pages.floor-operations.driver-module.orders-list',compact('page'));
    }

    /**
     * Display the specified resource.
     */
    public function showDriverOrders($id)
    {

        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $inventories = $this->productRepo->get_variants()->get();

        $page = $this->pageSetting('driver-order-details',$serviceOrder);

        if($serviceOrder->status == 'completed'){
            config([
                'visibility.action_form_confirmed' => 'hidden',
                'visibility.action_form_save' => 'hidden',
                'visibility.action_item_add_more' => 'hidden',
                'visibility.action_qc_pass' => 'hidden',
                'visibility.action_download_invoice' => 'show',
                'visibility.service_order_form' => 'show',
                'visibility.select_item' => 'show',
                'visibility.remark' => 'show',
                'visibility.mileage' => 'show',
                'visibility.operator' => 'show',
                'visibility.price' => 'show',
                'visibility.machines' => 'show',
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
        }else{
            config([
                'visibility.action_form_edit' => 'hidden',
                'visibility.service_order_form' => 'show',
                'visibility.service_edit_remark' => 'show',
                'visibility.select_item' => 'show',
                'visibility.remark' => 'show',
                'visibility.mileage' => 'hidden',
                'visibility.operator' => 'hidden',
                'visibility.price' => 'hidden',
            ]);

            return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories'));
        }
    }

    /**
    * Display a listing of the resource.
    */
    public function driverPendingOrdersList()
    {
        $user = Auth::user();

        if(!$user->hasRole('administrator')){
          request()->merge(['filter_driver' => $user->id]);
        }

        request()->merge(['filter_order_by_status' => ['pending','confirmed']]);

        $orders = $this->serviceOrderRepo->get();
        return Datatables::eloquent($orders)
                 ->addColumn('action', function($data) {
                     return '<a href="'.url('driver/service-orders/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a>';
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
    public function driverCompletedOrdersList()
    {
        $user = Auth::user();

        if(!$user->hasRole('administrator')){
          request()->merge(['filter_driver' => $user->id]);
        }

        request()->merge(['filter_order_by_status' => 'completed']);

        $orders = $this->serviceOrderRepo->get();

        return Datatables::eloquent($orders)
                 ->addColumn('action', function($data) {
                     return '<a href="'.url('driver/service-orders/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a>';
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
    public function pendingDelivery()
    {

        $page = $this->pageSetting('pending-delivery');
        return view('pages.floor-operations.driver-module.pending-delivery',compact('page'));
    }

    /**
    * Display a listing of the resource.
    */
    public function pendingDeliveryList()
    {
        $user = Auth::user();
        if(!$user->hasRole('administrator')){
          request()->merge(['filter_driver' => $user->id]);
        }

        request()->merge(['filter_order_by_service_status' => 'out-for-delivery']);
        $orders = $this->serviceOrderRepo->get();

        return Datatables::eloquent($orders)
                 ->addColumn('action', function($data) {
                    return '<a href="javascript:void(0);" class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal" data-url="'.url('service-orders/pending-delivery/'.$data->id).'"
                        data-loading-target="commonModalBody" data-modal-title="'.$data->service_order_id.'"
                        data-action-url="'.url('service-orders/pending-delivery/'.$data->id.'/mark-delivered').'"
                        data-action-method="POST"
                        data-action-ajax-class=""
                        data-modal-size=""

                        data-save-button-class="" data-save-button-text="Confirm Delivery" data-close-button-text="Back" data-project-progress="0">
                        <i class="mdi mdi-arrow-right-thick font-xxl"></i>
                    </a>';
                 })
                 ->editColumn('service_order_id', function($data) {
                     return '<h3 class="m-0"><b>'.$data->service_order_id.'</b></h3><p class="m-0">Customer Name: '. $data->client->client_name.'</p><p class="m-0">'.$data->total_pieces.' Pcs</p>';
                 })
                 ->rawColumns(['action', 'service_order_id'])
                 ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pendingDeliveryItem($id)
    {

        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $html = view('pages.floor-operations.driver-module.modals.pending-delivery-detail', compact('serviceOrder'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXMarkOrderDeliver'];

        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pendingDeliveryMarkDeliver(Request $request, $id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();
        $validator = Validator::make($request->all(), [
            'attachments' => ['required']
        ]);
        validationToaster($validator);

        if(request()->has('attachments')){
            foreach(request('attachments') as $key => $value){
                $data = [
                    'attachment_id' => $key,
                    'attachmentresource_type' => 'delivery',
                    'attachmentresource_id' => $id,
                    'attachment_directory' => $key,
                    'attachment_uniqiueid' => $key,
                    'attachment_filename' => $value,
                ];

                if(!$file = $this->attachmentRepo->process($data)){
                    abort(409, 'Something went wrong');
                }
            }
        }

        request()->merge(['deliver_date'=>\Carbon\Carbon::now(), 'service_status'=>'delivered']);


        if($serviceOrder->invoice->payment_terms == 'cod'){
            request()->merge(['invoice_paid'=>1]);
            $this->invoiceRepo->update($serviceOrder->invoice->id);

            $lifetime_revenue = $serviceOrder->client->lifetime_revenue + $serviceOrder->invoice->amount;
            request()->merge(['lifetime_revenue'=>$lifetime_revenue]);
            $this->clientRepo->update($serviceOrder->client->id);
        }

        $this->serviceOrderRepo->update($id);

        request()->replace([]);
        request()->merge(['is_delivered'=>1]);
         $this->invoiceRepo->update($serviceOrder->invoice->id);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        // notice
        request()->session()->flash('success-notification-longer', 'Request has been completed');

        //redirect to view page
        if(Auth::user()->hasRole('administrator','floor_manager')){
            $response['delayed_redirect_url'] = url("floor-operations/driver");
        }else{
            $response['delayed_redirect_url'] = url("driver/service-orders/pending-delivery");
        }

        return response()->json($response,200);
    }


    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Driver Module'];

        if($type == 'driver-order-details'){
            $page['pageTitle'] = 'Service Order: '.$data->service_order_id;
            $page['previousUrl'] = url('driver/service-orders');
        }

        if($type == 'driver-view-orders'){
            $page['pageTitle'] = 'View Order';
            if(Auth::user()->hasRole('administrator')){
                $page['previousUrl'] = url('floor-operations/driver');
            }else{
                $page['previousUrl'] = url('/');
            }
            $page['completed-jobs']['pageTitle'] = 'Completed Jobs';
            $page['pending-jobs']['pageTitle'] = 'Pending Jobs';
        }

        if($type == 'pending-delivery'){
            $page = ['pageTitle' =>'Pending Delivery'];
            if(Auth::user()->hasRole('administrator')){
                $page['previousUrl'] = url('floor-operations/driver');
            }else{
                $page['previousUrl'] = url('/');
            }
        }

        return $page;
    }
}
