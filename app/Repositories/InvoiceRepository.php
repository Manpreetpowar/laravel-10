<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for invoice
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\User;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\Client;
use Illuminate\Http\Request;
use Log, DB;

class InvoiceRepository {

    /** User models */
    protected $serviceOrder;

    /**Invoice Model */
    protected $invoice;

    /**Invoice Model */
    protected $client;

    public function __construct(ServiceOrder $serviceOrder, Invoice $invoice, Client $client){

        // set valirables
        $this->serviceOrder = $serviceOrder;
        $this->invoice = $invoice;
        $this->client = $client;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->invoice->Query();

        //filters
        if($id){
            $query->where('id',$id);
        }

        //filter by customer
        if(request()->filled('filter_client_id')){
            $query->where('client_id',request('filter_client_id'));
        }

        //filter by amount from
        if(request()->filled('filter_amount_from')){
            $query->where('amount', '>=', request('filter_amount_from'));
        }

        //filter by amount to
        if(request()->filled('filter_amount_to')){
            $query->where('amount', '<=', request('filter_amount_to'));
        }

        //filter by date start
        if(request()->filled('filter_date_start')){
             $query->whereDate('updated_at', '>=', request('filter_date_start'));
        }

        //filter by date end
        if(request()->filled('filter_date_end')){
            $query->whereDate('updated_at', '<=', request('filter_date_end'));
        }

        //filter by delivery status
        if(request()->filled('filter_delivery_status')){
            $status = request('filter_delivery_status') == 'delivered' ? 1 : 0;
            $query->where('is_delivered', $status);
        }

        //filter by payment status
        if(request()->filled('filter_payment_status')){
            $status = request('filter_payment_status') == 'paid' ? 1 : 0;
            $query->where('invoice_paid', $status);
        }

        //filter by payment status
        if(request()->filled('filter_payment_term')){
            $query->where('payment_terms', request('filter_payment_term'));
        }
        $query->orderBy('created_at', 'desc');
        $query->with('order','client');

        //get
        return $query;
    }

    public function generate($id){

        $order = $this->serviceOrder->findOrFail($id);

        $invoice_data = [];
        $invoice_data['invoice_number'] = generateUniqueID(new Invoice, 4);
        $invoice_data['client_id'] = $order->client_id;
        $invoice_data['service_order_id'] = $id;
        $invoice_data['payment_terms'] = $order->client->payment_terms;
        $invoice_data['sub_total'] = $order->items->sum('amount');
        $invoice_data['amount'] = $order->items->sum('amount');
        $invoice_data['invoice_paid'] = 0;
        $invoice_data['discount_percent'] = $invoice_data['discount_amount'] = $invoice_data['gst_amount'] = $invoice_data['gst_percent'] = 0;

        //discount
        if($order->client->apply_discount){
            $invoice_data['discount_amount'] = ($invoice_data['amount'] * $order->client->discount) / 100;
            $invoice_data['amount'] = $invoice_data['amount'] - $invoice_data['discount_amount'];
            $invoice_data['discount_percent'] = $order->client->discount;
        }

        //gst
        if(settings('settings_gst_percentage')){
            $invoice_data['gst_amount'] = ($invoice_data['amount'] * settings('settings_gst_percentage')) / 100;
            $invoice_data['amount'] = $invoice_data['amount'] + $invoice_data['gst_amount'];
            $invoice_data['gst_percent'] = settings('settings_gst_percentage');
        }

        $status['status'] = 'success';

        if($order->client->payment_terms == 'credit_limit'){
            if($invoice_data['amount'] > $order->client->credit_limit){
                $status = ['status'=>'no-credit'];
                $invoice_data['invoice_paid'] = 0;
            }else{
                $invoice_data['invoice_paid'] = 1;
                $this->client->where('id', $invoice_data['client_id'])->update(['lifetime_revenue' => DB::raw('lifetime_revenue + '.$invoice_data['amount'])]);
            }
        }
        
        if(!request('invoice_printed') && ($order->client->client_email == '' || $order->client->auto_send_email == 0))
        {
            $status = ['status'=>'manual-print-required'];
        }

        $invoice_data['invoice_paid_date'] = null;
        if($invoice_data['invoice_paid']){
            $invoice_data['invoice_paid_date'] = \Carbon\Carbon::now()->format('Y-m-d');
        }


        //new object
        if($order->invoice){
            $invoice = $this->invoice->find($order->invoice->id);
        }else{
            $invoice = new $this->invoice;
            $invoice->invoice_number = $invoice_data['invoice_number'];
            $invoice->client_id = $invoice_data['client_id'];
            $invoice->service_order_id = $invoice_data['service_order_id'];
            $invoice->payment_terms = $invoice_data['payment_terms'];
            $invoice->invoice_paid = $invoice_data['invoice_paid'];
            $invoice->invoice_paid_date = $invoice_data['invoice_paid_date'];
        }

            $invoice->discount_percent = $invoice_data['discount_percent'];
            $invoice->discount_amount =  $invoice_data['discount_amount'];
            $invoice->gst_amount = $invoice_data['gst_amount'];
            $invoice->gst_percent = $invoice_data['gst_percent'];
            $invoice->amount = $invoice_data['amount'];
            $invoice->sub_total = $invoice_data['sub_total'];
            $invoice->invoice_paid = $invoice_data['invoice_paid'];
            $invoice->invoice_paid_date = $invoice_data['invoice_paid_date'];

        if($invoice->save()){
            $status['type'] = $order->client->payment_terms;
            $status['amount'] = $invoice_data['amount'];
            return $status;
        }else{
            dd('log');
        }
    }

    public function update($id){
        //new object
        $data = request()->only(['invoice_paid', 'invoice_paid_date','is_delivered']);
        $invoice = $this->invoice->findOrFail($id);
        $invoice->fill($data);
        if($invoice->save()){
            return $invoice;
        }else{
            dd('log');
        }

    }

}
