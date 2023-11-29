<?php

namespace App\Http\Controllers;

use App\Models\FloorOperation;
use App\Repositories\MachineRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
use App\DataTables\Driver\OrdersDataTable;
use Illuminate\Http\Request;
use Auth, Datatables;

class FloorOperationController extends Controller
{
    protected $machineRepo;
    protected $serviceOrderRepo;
    protected $userRepo;
    protected $clientRepo;

    public function __construct(
        MachineRepository $machineRepo,
        ServiceOrderRepository $serviceOrderRepo,
        UserRepository $userRepo,
        ClientRepository $clientRepo){
        $this->machineRepo = $machineRepo;
        $this->serviceOrderRepo = $serviceOrderRepo;
        $this->userRepo = $userRepo;
        $this->clientRepo = $clientRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = $this->pageSetting();

        return view('pages.floor-operations.wrapper', compact('page'));
    }

    /**
     * Display a listing of the resource.
     */
    public function pendingOrders()
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
    public function completedOrders()
    {
        request()->merge(['filter_order_by_status' => 'completed']);
        $completedOrders = $this->serviceOrderRepo->get();

        return Datatables::eloquent($completedOrders)
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
    public function allJobsList()
    {
        $allJobs = $this->serviceOrderRepo->get();
        
        return Datatables::eloquent($allJobs)
                ->addColumn('action', function($data) {
                    return view('pages.floor-operations.all-jobs-module.components.action',['data'=>$data]);
                })
                ->editColumn('client.client_name', function($data) {
                    return '<a href="'.url('clients/'.$data->client->id).'">'. $data->client->client_name.'</a>';
                })
                 ->editColumn('status', function($data) {
                    return config('constants.order_status')[$data->status];
                })
                 ->editColumn('service_status', function($data) {
                    return $data->service_status ? config('constants.service_status')[$data->service_status] : '';
                })
                ->editColumn('client.payment_terms', function($data) {
                   return config('constants.payment_terms')[$data->client->payment_terms];
                })
                 ->editColumn('completed_date', function($data) {
                    return runtimeDate($data->completed_date);
                })
                 ->editColumn('invoice.is_delivered', function($data) {
                    return $data->invoice ? ($data->invoice->is_delivered == 1 ? 'Yes' : 'No') : '—';
                })
                 ->editColumn('deliver_date', function($data) {
                    return runtimeDate($data->deliver_date);
                })
                 ->editColumn('invoice.invoice_paid', function($data) {
                    return $data->invoice ? ($data->invoice->invoice_paid == 1 ? 'Paid' : 'Unpaid') : '—';
                })
                ->editColumn('invoice.discount_amount', function($data) {
                    return $data->invoice ? $data->invoice->discount_amount : '---';
                })
                ->editColumn('invoice.gst_amount', function($data) {
                    return $data->invoice ? $data->invoice->gst_amount : '---';
                })
                ->editColumn('invoice.amount', function($data) {
                    return $data->invoice ? $data->invoice->amount : '---';
                })
                ->rawColumns(['action','client.client_name'])
                ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function managerModule()
    {
        $page = $this->pageSetting('floor-operation-module');

        return view('pages.floor-operations.manager-module.wrapper', compact('page'));
    }

    /**
     * Display a listing of the resource.
     */
    public function machineModule()
    {
        $page = $this->pageSetting('machine-module');

        $machines = $this->machineRepo->get()->get();

        return view('pages.floor-operations.machine-module.wrapper', compact('page','machines'));
    }

    /**
     * Display a listing of the resource.
     */
    public function qcModule()
    {
        $page = $this->pageSetting('qc-module');

        return view('pages.floor-operations.qc-module.wrapper', compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function driverModule()
    {
        $page = $this->pageSetting('driver-module');

        //get pending order count
        request()->merge(['filter_order_by_status'=>['pending','confirmed']]);
        $order_count    = $this->serviceOrderRepo->get()->get()->count();

        //get delivery count
        request()->replace([]);
        request()->merge(['filter_order_by_service_status'=>'out-for-delivery']);
        $delivery_count    = $this->serviceOrderRepo->get()->get()->count();

        return view('pages.floor-operations.driver-module.wrapper', compact('page','order_count','delivery_count'));
    }

    /**
    * Display a listing of the resource.
    */
    public function jobsOnHold()
    {
        $page = $this->pageSetting('jobs-on-hold');
        return view('pages.floor-operations.jobs-on-hold.wrapper',compact('page'));
    }

    /**
     * Display a listing of the resource.
     */
    public function allJobsModule()
    {
        $page = $this->pageSetting('all-jobs-module');

        return view('pages.floor-operations.all-jobs-module.wrapper', compact('page'));
    }

    /**
     * all job filter
     */
    public function allJobsFilter()
    {
        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $clients = $this->clientRepo->get()->get();
        request()->merge(['filter_user_role_type'=>'driver']);
        $drivers = $this->userRepo->get()->get();

        $html = view('pages.floor-operations.all-jobs-module.modals.filter-modal', compact('clients', 'drivers'))->render();
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
        $page = ['pageTitle' =>'Floor Operations Module'];
        $page['completed-jobs']['pageTitle'] = 'Completed Jobs';
        $page['pending-machine']['pageTitle'] = 'Pending Machining';

        if($type == 'details'){
            $page['service']['pageTitle'] = 'Servicing History';
        }

        if($type == 'floor-operation-module'){
            $page['previousUrl'] = url('floor-operations');
        }

        if($type == 'machine-module'){
            $page['pageTitle'] = 'Machining Module';
            if(Auth::user()->hasRole('administrator','floor_manager')){
                $page['previousUrl'] = url('floor-operations');
            }else{
                $page['previousUrl'] = url('/');
            }
        }

        if($type == 'qc-module'){
            $page['pageTitle'] = 'QC Module';
            if(Auth::user()->hasRole('administrator','floor_manager')){
                $page['previousUrl'] = url('floor-operations');
            }else{
                $page['previousUrl'] = url('/');
            }
        }

        if($type == 'driver-module'){
            $page['pageTitle'] = 'Driver Module';
            $page['previousUrl'] = url('floor-operations');
        }

        if($type == 'all-jobs-module'){
            $page['pageTitle'] = 'All Jobs';
            $page['previousUrl'] = url('floor-operations');
        }

        if($type == 'jobs-on-hold'){
            $page['pageTitle'] = 'Jobs On Hold';
            $page['previousUrl'] = url('floor-operations');
            $page['no-credit']['pageTitle'] = 'Jobs On Hold - No Credit';
            $page['manual-print']['pageTitle'] = 'Jobs On Hold - Manual Printing Required';
        }

        return $page;
    }
}
