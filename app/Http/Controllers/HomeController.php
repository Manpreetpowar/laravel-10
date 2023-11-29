<?php

namespace App\Http\Controllers;

use App\Repositories\MachineRepository;
use App\Repositories\UserRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    protected $machineRepo;
    protected $clientRepo;
    protected $invoiceRepo;
    protected $userRepo;
    protected $serviceOrderRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository         $userRepo,
        MachineRepository      $machineRepo,
        ClientRepository       $clientRepo,
        ProductRepository      $inventryRepo,
        ServiceOrderRepository $serviceOrderRepo,
        InvoiceRepository      $invoiceRepo
        )
    {
        $this->machineRepo      = $machineRepo;
        $this->inventryRepo     = $inventryRepo;
        $this->clientRepo       = $clientRepo;
        $this->userRepo         = $userRepo;
        $this->serviceOrderRepo = $serviceOrderRepo;
        $this->invoiceRepo      = $invoiceRepo;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasRole('administrator','account','floor_manager')){
            $user_count     = $this->userRepo->get()->count();
            $client_count   = $this->clientRepo->get()->count();
            $machine_count  = $this->machineRepo->get()->count();
            $inventry_count = $this->inventryRepo->get()->count();
            $current_date     = Carbon::now()->format('Y-m-d');

            $first = new Carbon('first day of this month');
            $first = $first->format('Y-m-d');
            $last = new Carbon('last day of this month');
            $last = $last->format('Y-m-d');
            //daily revenue
            request()->merge(['filter_paid_date_start'=>$current_date,'filter_paid_date_end'=>$current_date]);
            $daily_revenue  =  $this->serviceOrderRepo->get()->get();
            $daily_revenue = $daily_revenue->pluck('invoice')->sum('amount');

            //monthly job count
            request()->replace([]);
            request()->merge(['filter_date_start'=>$first,'filter_date_end'=>$last]);
            $job_count = $this->serviceOrderRepo->get()->get()->count();


           //monthly revenue
            request()->replace([]);
            request()->merge(['filter_paid_date_start'=>$first,'filter_paid_date_end'=>$last]);
             $monthly_revenue = $this->serviceOrderRepo->get()->get();
             $monthly_revenue = $monthly_revenue->pluck('invoice')->sum('amount');

             //current outstanding job
            request()->replace([]);
            request()->merge(['filter_invoice_status'=>'unpaid', 'filter_order_by_status'=>'completed']);
            $current_outstanding_job = $this->serviceOrderRepo->get()->count();

            return view('pages.dashboard.administrator', compact('user_count','client_count','machine_count','inventry_count','job_count','monthly_revenue','daily_revenue','current_outstanding_job'));

        }elseif(Auth::user()->hasRole('driver')){
            $driver_id = Auth::user()->id;

            request()->merge(['filter_driver'=>$driver_id,'filter_order_by_status'=>['pending','confirmed']]);
            $order_count    = $this->serviceOrderRepo->get()->count();

            request()->replace([]);
            request()->merge(['filter_driver'=>$driver_id,'filter_order_by_service_status'=>'out-for-delivery']);
            $delivery_count    = $this->serviceOrderRepo->get()->get()->count();

            $page = $this->pageSetting('driver-module');
            return view('pages.dashboard.driver',compact('page','order_count','delivery_count'));

        }elseif(Auth::user()->hasRole('operator')){
            $page = $this->pageSetting('operator-module');
            $machines = $this->machineRepo->get()->get();
            return view('pages.dashboard.operator',compact('page','machines'));

        }
    }

    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Dashboard'];

        if($type == 'driver-module'){
            $page['pageTitle'] = 'Driver Module';
        }

        if($type == 'operator-module'){
            $page['pageTitle'] = 'Operator Module';
        }

        return $page;
    }
}
