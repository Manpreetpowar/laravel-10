<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ServiceOrderRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\DestroyRepository;
use Carbon\Carbon;
use Validator, Datatables;

class AccountingController extends Controller
{
    protected $invoiceRepo;
    protected $clientRepo;
    protected $serviceOrderRepo;
    protected $expenseRepo;

    public function __construct(InvoiceRepository $invoiceRepo,
                                ServiceOrderRepository $serviceOrderRepo,
                                ClientRepository $clientRepo,
                                ExpenseRepository $expenseRepo){
        $this->invoiceRepo = $invoiceRepo;
        $this->clientRepo = $clientRepo;
        $this->serviceOrderRepo = $serviceOrderRepo;
        $this->expenseRepo = $expenseRepo;
    }

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $first = new Carbon('first day of this month');
        $first = $first->format('Y-m-d');
        $last = new Carbon('last day of this month');
        $last = $last->format('Y-m-d');

        //monthly revenue
        request()->replace([]);
        request()->merge(['filter_paid_date_start'=>$first,'filter_paid_date_end'=>$last]);
        $monthly_revenue = $this->serviceOrderRepo->get()->get();
        $monthly_revenue = $monthly_profit = $monthly_revenue->pluck('invoice')->sum('amount');

        //monthly unpaid invoices
        request()->replace([]);
        request()->merge(['filter_date_start'=>$first,'filter_date_end'=>$last, 'filter_payment_status'=>'unpaid', 'filter_delivery_status'=>'delivered']);
        $unpaid_inv_count = $this->invoiceRepo->get()->count();

        //monthly paid invoices
        request()->replace([]);
        request()->merge(['filter_date_start'=>$first,'filter_date_end'=>$last, 'filter_payment_status'=>'paid']);
        $paid_inv_count = $this->invoiceRepo->get()->count();

        //monthly expense
        request()->replace([]);
        request()->merge(['filter_date_start'=>$first,'filter_date_end'=>$last, 'filter_payment_status'=>'paid']);
        $monthly_expense = $this->expenseRepo->get()->sum('amount');

        $page = $this->pageSetting('listing');

        return view('pages.accounting.wrapper', compact('page', 'monthly_revenue','unpaid_inv_count','paid_inv_count', 'monthly_expense'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function invoiceModule()
    {

        $page = $this->pageSetting('invoice-module');

        return view('pages.accounting.invoices.wrapper',compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function invoiceList()
    {
          $invoices = $this->invoiceRepo->get();
        // $invoices = $this->invoiceRepo->orderBy('created_at', 'desc')->get();
        return Datatables::eloquent($invoices)
                ->addColumn('action', function($data) {
                    return view('pages.accounting.invoices.components.action',['invoice'=>$data]);
                })
                ->editColumn('client.client_name', function($data) {
                    return '<a href="'.url('clients/'.$data->client->id).'">'.$data->client->client_name.'</a>';
                })
                ->editColumn('client.payment_terms', function($data) {
                    return config('constants.payment_terms')[$data->client->payment_terms];
                })
                ->editColumn('order.service_order_id', function($data) {
                    return '<a href="'.url('service-orders/'.$data->order->id).'">'.$data->order->service_order_id.'</a>';
                })
                ->editColumn('amount', function($data) {
                    return '$'.$data->amount;
                })
                ->editColumn('is_delivered', function($data) {
                    return $data->is_delivered ? 'Yes' : 'No';
                })
                ->editColumn('invoice_paid', function($data) {
                    $html = '<form id="switchForm-'.$data->id.'"><input type="hidden" name="_token" value="'.csrf_token().'">
                            <div class="form-check m-0 p-0">
                                <label for="is_invoice_paid_'.$data->id.'" class="switch sm">
                                    <input class="form-check-input js-ajax" type="checkbox" data-url="'.url('accountings/invoices/status-change/'.$data->id.'').'" data-ajax-type="post" data-form-id="switchForm-'.$data->id.'" id="is_invoice_paid_'.$data->id.'" name="is_invoice_paid_'.$data->id.'" value="option1" '.runtimePreChecked2($data->invoice_paid,1).'>
                                    <span class="slider round"></span>
                                    <span class="label switch-label">'.($data->invoice_paid ? 'Yes' : 'No').'</span>
                                </label>
                            </div></form>';
                    return $html;
                })
                ->rawColumns(['action','client.client_name','order.service_order_id','invoice_paid'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

       /**
     *invoice filter
     */
    public function invoiceFilter()
    {
        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $clients = $this->clientRepo->get()->get();

        $html = view('pages.accounting.invoices.modals.filter', compact('filter','clients'))->render();
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
     *  change status of job list
     */
    public function changeInvoiceStatus($id)
    {

        $invoice = $this->invoiceRepo->get($id)->first();
        $client  = $this->clientRepo->get($invoice->client_id)->first();

        if (!$invoice) {
            //notice
            $ajax['notification'] = array('type' => 'error', 'value' => 'Order not found');
            //ajax response & view
            return response()->json($ajax);
        }
        
        $status = $invoice->invoice_paid;
        if($status){
            $lifetimeRevenue = $client->lifetime_revenue - $invoice->amount;
            $data['invoice_paid'] = 0;
            $data['invoice_paid_date'] = null;
        }else{
            $lifetimeRevenue = $client->lifetime_revenue + $invoice->amount;
            $data['invoice_paid_date'] = \carbon\Carbon::now()->format('Y-m-d');
            $data['invoice_paid'] = 1;
        }

        //update life time revenue
        request()->merge(['lifetime_revenue'=>$lifetimeRevenue]);
        $this->clientRepo->update($client->id);

        request()->replace([]);
        request()->merge($data);
        $this->invoiceRepo->update($invoice->id);



        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //life time revenu change
        $ajax['dom_val'][] = array('selector' => '#lifetime_revenue', 'value' => $lifetimeRevenue);
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
        $page = ['pageTitle' =>'Accounting Module'];

        if($type == 'details'){ }

        if($type == 'invoice-module'){
            $page['pageTitle'] ='Invoices';
            $page['previousUrl'] = url('accountings');
        }

        return $page;
    }
}
