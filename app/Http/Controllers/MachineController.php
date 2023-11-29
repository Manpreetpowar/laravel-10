<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Repositories\ClientRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\MachineRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\AttachmentRepository;
use App\DataTables\Driver\OrdersDataTable;
use App\Events\GenerateAdHocServiceEvent;
use Illuminate\Http\Request;
use Validator, Auth, Datatables;

class MachineController extends Controller
{
    protected $machineRepo;
    protected $clientRepo;
    protected $serviceOrderRepo;
    protected $productRepo;
    protected $userRepo;
    protected $invoiceRepo;
    protected $orderItem;

    public function __construct(MachineRepository $machineRepo,
                                ClientRepository $clientRepo,
                                ServiceOrderRepository $serviceOrderRepo,
                                ProductRepository $productRepo,
                                UserRepository $userRepo,
                                InvoiceRepository $invoiceRepo,
                                AttachmentRepository $attachmentRepo,
                                ServiceOrderItem $orderItem){
        $this->machineRepo = $machineRepo;
        $this->clientRepo = $clientRepo;
        $this->serviceOrderRepo = $serviceOrderRepo;
        $this->productRepo = $productRepo;
        $this->userRepo = $userRepo;
        $this->machineRepo = $machineRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->attachmentRepo = $attachmentRepo;
        $this->orderItem  = $orderItem;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = $this->pageSetting('listing');

        $machines = $this->machineRepo->get()->get();
        return view('pages.machines.wrapper',compact('page','machines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $html = view('pages.machines.modals.add-edit-inc')->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateMachine'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machine_name' => ['required', 'max:100'],
            'brand_name' => ['required', 'max:50'],
            'model' => ['required', 'max:50']
        ]);
        validationToaster($validator);

        $machine_id = generateUniqueID(new Machine, 4);

        request()->merge(['machine_id' => $machine_id]);
        $machine = $this->machineRepo->create();

        $machines = $this->machineRepo->get($machine->id)->get();

        $html = view('pages.machines.components.box-grid',compact('machines'))->render();
        $ajax['dom_html'][] = array(
            'selector' => '#machines-list-container',
            'action' => 'append',
            'value' => $html);

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
    public function show(Machine $machine)
    {
        $page = $this->pageSetting('details',$machine);

        request()->merge(['machine_id',$machine->id]);
        return view('pages.machine.wrapper',compact('page','machine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Machine $machine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Machine $machine)
    {
        $validator = Validator::make($request->all(), [
            'machine_name' => ['required', 'max:100'],
            'brand_name' => ['required', 'max:50'],
            'model' => ['required', 'max:50']
        ]);
        validationToaster($validator);

        $machine = $this->machineRepo->update($machine->id);

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Machine $machine)
    {
        //
    }

    /**
     * job list
     */
    public function jobsList($id)
    {
        request()->merge(['filter_machine_id'=>$id]);
        $orders = $this->serviceOrderRepo->getOrderItem();

       return Datatables::eloquent($orders)
                ->addColumn('action', function($data) {
                    return '<a href="'.url('service-orders/'.$data->service_order->id).'" class="btn btn-default btn-sm"><i class="mdi single mdi-eye"></i></a>
                        <a href="'.url('service-orders/'.$data->service_order->id).'" class="btn btn-default btn-sm"><i class="mdi single mdi-trash-can-outline"></i></a>';
                })
                ->editColumn('total_run', function($data) {
                    return $data->total_run. ' ft';
                })
                ->editColumn('service_order.client.client_name', function($data) {
                    return '<a href="'.url('clients/'.$data->service_order->client->id).'" class="text-dark"><u>'.$data->service_order->client->client_name.'</u></a>';
                })
                ->editColumn('created_at', function($data) {
                    return runtimeDate($data->created_at);
                })
                ->rawColumns(['action','service_order.client.client_name'])
                ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function operateMachine($id)
    {
        $machine = $this->machineRepo->get($id)->first();
        config(['visibility.action_button_leave_machine' => 'show']);
        $page = $this->pageSetting('operate-machine',$machine);

        return view('pages.floor-operations.machine-module.machining', compact('page','machine'));
    }

    /**
     * Display a listing of the resource.
     */
    public function becomeMachineOperator($id)
    {
        $machine = $this->machineRepo->get($id)->first();

        request()->merge(['operator_id'=>Auth::user()->id]);
        $this->machineRepo->update($id);

        //notice
        request()->session()->flash('success-notification-longer', 'Request has been completed');

        //redirect to view page
        $jsondata['redirect_url'] = url("floor-operations/machines/$machine->id/operate");

        return response()->json($jsondata,200);
    }

    /**
     * Display a listing of the resource.
     */
    public function leaveMachine($id)
    {
        $machines = $this->machineRepo->get()->get();

        request()->merge(['operator_id'=>null]);
        $this->machineRepo->update($id);

        //redirect to view page
        $jsondata['redirect_url'] = url("floor-operations/machines");

        //notice
        request()->session()->flash('success-notification-longer', 'Request has been completed');

        return response()->json($jsondata,200);
    }

    /**
     * Display a listing of the resource.
     */
    public function pendingMachines()
    {
        request()->merge(['filter_order_by_status' => 'pending']);
        $pendingOrders = $this->serviceOrderRepo->get();

        return Datatables::eloquent($pendingOrders)
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
     * Display a listing of the resource.
     */
    public function receivedOrders($machine_id)
    {
        request()->merge(['filter_order_by_status' => 'confirmed']);
        $receivedOrders = $this->serviceOrderRepo->get();

        return Datatables::eloquent($receivedOrders)
                ->addColumn('action', function($data) use($machine_id) {
                    $service_status = $data->service_status ?? '';
                    return '<div class="d-flex align-items-center justify-content-end"><span class="status mr-5">'.config('constants.service_status.'.$service_status).'</span><a href="'.url('floor-operations/operate-machine/'.$machine_id.'/service-orders/'.$data->id).'"><i class="mdi mdi-arrow-right-thick font-xxl"></i></a></div>';
                })
                ->editColumn('service_order_id', function($data) {
                    return '<h3 class="m-0"><b>'.$data->service_order_id.'</b></h3><p class="m-0">Customer Name: '. $data->client->client_name.'</p><p class="m-0">'.$data->total_pieces.' Pcs</p>';
                })
                ->rawColumns(['action', 'service_order_id'])
                ->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function operateMachineOrder($machine_id, $order_id)
    {
        config([
            'visibility.action_form_confirmed' => 'hidden',
            'visibility.action_form_save' => 'show',
            'visibility.action_item_add_more' => 'hidden',
            'visibility.service_order_form' => 'show',
            'visibility.select_item' => 'show',
            'visibility.remark' => 'show',
            'visibility.mileage' => 'writable',
            'visibility.operator' => 'hidden',
            'visibility.price' => 'hidden',
            'visibility.machines' => 'writable',
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
        $page = $this->pageSetting('operate-machine-order', $pageData);

        return view('pages.floor-operations.service-orders.wrapper', compact('page','serviceOrder','inventories','drivers','clients','machine_acc', 'machine_normal', 'machine_thik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function saveMachineMileage($machine_id, $order_id)
    {
        $user = Auth::user();
        $serviceOrder = $this->serviceOrderRepo->get($order_id)->first();

        if(isset(request()->item_id)){
            if($msg = $this->serviceOrderRepo->statusValidate($order_id)){
                // notice
                $jsondata['notification'] = array('type' => 'error', 'value' => $msg);
                return response()->json($jsondata);
            }
            $service_status = '';
            foreach(request()->item_id as $key => $item_id){
                $item = $this->serviceOrderRepo->getOrderItem($item_id)->first();
                if($item->total_run != request()->item_mileage[$key]){
                    $update_data = [
                        'operator_id' => $user->id,
                        'machine_id' => $machine_id,
                        'total_run' => request()->item_mileage[$key]
                    ];

                    if($item->machine_id != ''){
                        $this->machineRepo->decreaseMileage($item->machine_id,$item->total_run);
                    }

                    // if($item->type == 'custom'){
                    //     $update_data['amount'] = $item->price * request()->item_mileage[$key];
                    // }else{
                    //     $update_data['amount'] = $item->product_variant->option_price * request()->item_mileage[$key];
                    // }
                    $update_data['amount'] = $item->price * request()->item_mileage[$key];

                    $this->serviceOrderRepo->updateItem($item->id, $update_data);
                    $this->machineRepo->increaseMileage($machine_id,$update_data['total_run']);
                }
            }
        }

        event(new GenerateAdHocServiceEvent($machine_id));

        request()->replace([]);
        $this->serviceOrderRepo->updateStatus($order_id);

        config([
            'visibility.action_form_confirmed' => 'hidden',
            'visibility.action_form_save' => 'show',
            'visibility.action_item_add_more' => 'hidden',
            'visibility.service_order_form' => 'show',
            'visibility.select_item' => 'show',
            'visibility.remark' => 'show',
            'visibility.mileage' => 'writable',
            'visibility.operator' => 'hidden',
            'visibility.price' => 'hidden',
            'visibility.machines' => 'writable',
        ]);

        $serviceOrder = $this->serviceOrderRepo->get($order_id)->first();

        $html = view('pages.floor-operations.service-orders.components.items', compact('serviceOrder'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#item-container',
            'action' => 'replace',
            'value' => $html);

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($jsondata);
    }


    public function machineFilter($id)
    {
        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }
        $machine = $this->machineRepo->get($id)->first();
        $operators = $machine->order_items->pluck('operator')->unique();
        $clients = $machine->order_items->pluck('service_order.client')->unique();

        $html = view('pages.machine.modals.filter-job-list', compact('filter', 'operators', 'clients'))->render();
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
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Machine Module'];
        $page['completed-jobs']['pageTitle'] = 'Completed Jobs';
        $page['pending-machine']['pageTitle'] = 'Pending Machining';

        if($type == 'details'){
            // $page['pageTitle'] =$data->machine_name;
            $page['service']['pageTitle'] = 'Servicing History';
            $page['jobs']['pageTitle'] = 'Job List';
        }

        if($type == 'operate-machine'){
            $page['pageTitle'] = 'Machining Module';
            $page['previousUrl'] = url('floor-operations/machines');
            $page['pageRightTitle'] = 'Machine: '.$data->machine_name;
        }

        if($type == 'operate-machine-order'){
            $page['pageTitle'] = 'Service Order: '.$data['service_order_id'];
            $page['previousUrl'] = url('floor-operations/machines/'.$data['machine_id'].'/operate');
            $page['saveButtonUrl'] = url('floor-operations/operate-machine/'.$data['machine_id'].'/service-orders/'.$data['id']);

        }
        return $page;
    }
}
