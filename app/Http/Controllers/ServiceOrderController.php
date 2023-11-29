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
use App\Repositories\DestroyRepository;
use App\DataTables\Driver\OrdersDataTable;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Validator, Auth, Datatables, PDF;

class ServiceOrderController extends Controller
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
    public function index()
    {
        //
    }


     /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $orders = $this->serviceOrderRepo->get();
       return Datatables::eloquent($orders)
                ->addColumn('action', function($data) {
                    return '<a href="'.url('service-orders/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a>';
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
    public function create()
    {
        $clients = $this->clientRepo->get()->get();

        request()->merge(['filter_user_role_type'=>'driver']);
        $drivers = $this->userRepo->get()->get();

        $html = view('pages.floor-operations.driver-module.modals.add-edit-inc', compact('clients','drivers'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateOrder'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $driver_id = $request->driver ?? Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'driver' => ['sometimes','required']
        ]);
        validationToaster($validator);

        $service_order_id = generateUniqueID(new ServiceOrder, 4);
        request()->merge(['service_order_id'=> $service_order_id, 'driver'=>$driver_id]);
        $this->serviceOrderRepo->create();

        // count of pending jobs
        request()->replace([]);
        if(Auth::user()->hasRole('driver')){
            request()->merge(['filter_driver'=>$driver_id]);
        }
        request()->merge(['filter_order_by_status' => ['pending','confirmed']]);
        $service_order_count = $this->serviceOrderRepo->get()->count();

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];
        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['dom_html'][] = array(
            'selector' => '#view-order-count',
            'action' => 'replace',
            'value' => 'View Order ('.$service_order_count.')');
        //ajax response & view
        return response()->json($ajax);

    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceOrder $serviceOrder)
    {
        $serviceOrder = $this->serviceOrderRepo->get($serviceOrder->id)->first();

        $inventories = $this->productRepo->get_variants()->get();

        $page = $this->pageSetting('details',$serviceOrder);

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

            $page = $this->pageSetting('details',$serviceOrder);

            return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories', 'machine_normal','machine_acc', 'machine_thik'));
        }else{
            config([
                'visibility.action_form_edit' => 'show',
                'visibility.service_order_form' => 'show',
                'visibility.service_edit_remark' => 'writable',
                'visibility.select_item' => 'show',
                'visibility.remark' => 'show',
                'visibility.mileage' => 'hidden',
                'visibility.operator' => 'hidden',
                'visibility.price' => 'hidden',
            ]);

            if($serviceOrder->status == 'pending'){
                config([
                    'visibility.action_form_edit' => 'hide',
                    'visibility.action_form_start_servicing' => 'show'
                ]);
            }

            return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceOrder $serviceOrder)
    {
        if($serviceOrder->status == 'pending'){
            config([
                'visibility.action_form_confirmed' => 'show',
                'visibility.action_form_save' => 'hidden',
                'visibility.action_item_add_more' => 'show',
                'visibility.service_order_form' => 'writable',
                'visibility.select_item' => 'writable',
                'visibility.remark' => 'writable',
                'visibility.mileage' => 'hidden',
                'visibility.operator' => 'hidden',
                'visibility.price' => 'hidden',
                'visibility.color-match-form' => 'show'
            ]);
        }elseif($serviceOrder->status == 'confirmed'){
            config([
                'visibility.action_form_confirmed' => 'hidden',
                'visibility.action_form_save' => 'show',
                'visibility.action_item_add_more' => 'hidden',
                'visibility.service_order_form' => 'show',
                'visibility.select_item' => 'show',
                'visibility.remark' => 'writable',
                'visibility.mileage' => 'hidden',
                'visibility.operator' => 'hidden',
                'visibility.price' => 'hidden',
            ]);
        }

        $serviceOrder = $this->serviceOrderRepo->get($serviceOrder->id)->first();

        $inventories = $this->productRepo->get_variants()->get();
        $clients = $this->clientRepo->get()->get();

        request()->merge(['filter_user_role_type'=>'driver']);
        $drivers = $this->userRepo->get()->get();

        $page = $this->pageSetting('edit', $serviceOrder);

        return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories','drivers','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceOrder $serviceOrder)
    {
        if(isset($request->item_id)){
            foreach($request->item_id as $key => $item){
                $item_data = [
                    'remarks' => $request->item_remark[$key],
                ];

                $this->serviceOrderRepo->updateItem($item, $item_data);

            }
        }

        //notice
        request()->session()->flash('success-notification-longer', 'Request has been completed');

        //redirect to view page
        $jsondata['delayed_redirect_url'] = url("service-orders/".$serviceOrder->id);

        //ajax response & view
        return response()->json($jsondata);
    }

    /**
     * Update the specified resource in storage.
     */
    public function confirmReceived(Request $request, $id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $validator = Validator::make($request->all(), [
            'selected_item.*' => ['sometimes','required'],
            'item_qty.*' => ['sometimes','required'],
            'item_price.*' => ['sometimes','required'],
            'selected_item' => 'required|array|min:1'
        ],[
            'selected_item.*.required' => 'Selected items can not be empty.',
            'item_qty.*.required' => 'Selected items quantity can not be empty.',
            'selected_item'=>'At least one item need to be add.',
            'item_price.*.required' => 'Selected items price can not be empty.'
        ]);

        validationToaster($validator);

        $this->serviceOrderRepo->update($serviceOrder->id);

        if(isset($request->selected_item)){
            foreach($request->selected_item as $key => $item){
                $item_data = [
                    'service_order_id' => $id,
                    'type' => $request->item_type[$key],
                    'quantity' => $request->item_qty[$key],
                    'remarks' => $request->item_remark[$key],
                ];
                if($request->item_type[$key] == 'custom'){
                    $item_data['item_name'] = $item;
                    $item_data['price'] = $request->item_price[$key];
                }else{
                    $item_price = $this->productRepo->get_variant($item)->first();
                    $item_data['product_variant_id'] = $item;
                    $item_data['price'] = $item_price->option_price;
                }

                if($request->item_id[$key]){
                    $this->serviceOrderRepo->updateItem($request->item_id[$key], $item_data);
                }else{
                    $this->serviceOrderRepo->storeItem($item_data);
                }
            }
        }

        // update statuses
        request()->replace([]);
        request()->merge(['status'=>'confirmed']);
        $this->serviceOrderRepo->updateStatus($id);

        //redirect to view page
        $jsondata['redirect_url'] = url("service-orders/".$id);

        //notice
        request()->session()->flash('success-notification-longer', 'Request has been completed');

        //ajax response & view
        return response()->json($jsondata);
    }

    /**
     * Update the specified resource in storage.
     */
    public function showCompleteJob($id)
    {

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

        $serviceOrder = $this->serviceOrderRepo->get($order_id)->first();

        $inventories = $this->productRepo->get_variants()->get();
        $clients = $this->clientRepo->get()->get();

        request()->merge(['filter_user_role_type'=>'driver']);
        $drivers = $this->userRepo->get()->get();

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

        $pageData = ['id'=>$serviceOrder->id, 'service_order_id'=>$serviceOrder->service_order_id, 'machine_id'=>$machine_id];
        $page = $this->pageSetting('operate-machine', $pageData);

        return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','machine_acc', 'machine_normal', 'machine_thik'));
    }

    /**
     * Add new item in service order.
     */
    public function addItem($type)
    {
        config([
            'visibility.select_item' => 'writable',
            'visibility.remark' => 'writable',
            'visibility.mileage' => 'hidden',
            'visibility.operator' => 'hidden',
            'visibility.price' => 'hidden',
            'visibility.remove' => 'show',
        ]);

        if($type == 'custom'){
            config(['visibility.price' => 'writable']);

            $html = view('pages.floor-operations.service-orders.components.item', compact('type'))->render();
            $response['dom_html'][] = array(
                'selector' => '#service-order-items',
                'action' => 'append',
                'value' => $html);

            $response['postrun_functions'][] = [
                'value' => 'NXServiceOrdersMultiItem'];

            return response()->json($response,200);
        }else{

            $inventories = $this->productRepo->get_variants()->get();
            $html = view('pages.floor-operations.service-orders.components.item', compact('inventories'))->render();
            $response['dom_html'][] = array(
                'selector' => '#service-order-items',
                'action' => 'append',
                'value' => $html);

            $response['postrun_functions'][] = [
                'value' => 'NXServiceOrdersMultiItem'];

            $response['postrun_functions'][] = [
                'value' => 'NXSyncServiceOrderItems'];

            return response()->json($response,200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRepository $destroyRepo, $id)
    {
        $serviceOrder = $this->serviceOrderRepo->get($id)->first();

        $destroyRepo->deleteServiceOrder($serviceOrder->id);

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
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Driver Module'];

        if($type == 'details'){
            $page['pageTitle'] = 'Service Order: '.$data->service_order_id;

            // $page['previousUrl'] = route('clients.show' , $data->client_id);
            $page['previousUrl'] = url('floor-operations/orders');
            $page['creditNote'] = ['pageTitle' =>''];
        }
        if($type == 'edit'){
            $page = ['pageTitle' => 'Service Order: '.$data->service_order_id,
            'previousUrl' => url('service-orders/'.$data->id),
            'saveButtonUrl' => route('service-orders.update',$data->id)
            ];
        }

        // if($type == 'pending-orders'){
        //     $page = ['pageTitle' =>'Pending Orders'];
        //     if(Auth::user()->hasRole('administrator')){
        //         $page['previousUrl'] = url('floor-operations/driver');
        //     }else{
        //         $page['previousUrl'] = url('/');
        //     }
        // }

        if($type == 'operate-machine'){
            $page = [
                'pageTitle' => 'Service Order: '.$data['service_order_id'],
                'previousUrl' => url('floor-operations/machines/'.$data['machine_id'].'/operate'),
                'saveButtonUrl' => url('floor-operations/operate-machine/'.$data['machine_id'].'/service-orders/'.$data['id']),
            ];
        }

        return $page;
    }
}
