<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for clients
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Http\Request;
use Log;

class ServiceOrderRepository {

    /** Client models */
    protected $order;

    protected $orderItem;

    public function __construct(
        ServiceOrder $order,
        ServiceOrderItem $orderItem){
        // set valirables
        $this->order = $order;
        $this->orderItem = $orderItem;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->order->Query();

        //filters

        if($id){
            $query->where('id',$id);
        }
        //filter by Status
        if(request()->filled('filter_order_by_status')){
            if(is_array(request('filter_order_by_status'))){
                $query->whereIn('status',request('filter_order_by_status'));
            }else{
                $query->where('status',request('filter_order_by_status'));
            }
        }

        //filter by Service status
        if(request()->filled('filter_order_by_service_status')){
            if(is_array(request('filter_order_by_service_status'))){
                $query->whereIn('service_status',request('filter_order_by_service_status'));
            }else{
                $query->where('service_status',request('filter_order_by_service_status'));
            }
        }

        //filter by driver
        if(request()->filled('filter_driver')){
            $query->where('driver_id',request('filter_driver'));
        }

        //filter by customer
        if(request()->filled('filter_client_id')){
            $query->where('client_id',request('filter_client_id'));
        }


        //filter by date complete start
        if(request()->filled('ffilter_complete_date_start')){
             $query->where('completed_date', '>=', request('ffilter_complete_date_start'));

        }

        //filter by date complete end
        if(request()->filled('filter_complete_date_end')){
            $query->where('completed_date', '<=', request('filter_complete_date_end'));
        }

        //filter by date create start
        if(request()->filled('filter_date_start')){
             $query->whereDate('created_at', '>=', request('filter_date_start'));

        }

        //filter by date create end
        if(request()->filled('filter_date_end')){
            $query->whereDate('created_at', '<=', request('filter_date_end'));
        }


        //filter by date paid start
        if(request()->filled('filter_paid_date_start')){
            $query->whereHas('invoice', function($inv) {
                $inv->whereNotNull('invoice_paid_date')->whereDate('invoice_paid_date', '>=', request('filter_paid_date_start'));
            });
        }

        //filter by date paid start
        if(request()->filled('filter_paid_date_end')){
            $query->whereHas('invoice', function($inv) {
                $inv->whereNotNull('invoice_paid_date')->whereDate('invoice_paid_date', '<=', request('filter_paid_date_end'));
            });
        }

        //filter invoice status
        if(request()->filled('filter_invoice_status')){
            $query->whereHas('invoice', function($inv) {
                $status = request('filter_invoice_status') == 'paid' ? 1 : 0;
                $inv->where('invoice_paid', $status);
            });
        }

        //filter by amount from
        if(request()->filled('filter_amount_from')){
            $query->whereHas('invoice', function($inv) {
                $inv->where('amount', '>=', request('filter_amount_from'));
            });
        }

        //filter by amount to
        if(request()->filled('filter_amount_to')){
            $query->whereHas('invoice', function($inv) {
                $inv->where('amount', '<=', request('filter_amount_to'));
            });
        }
        
        //filter by delivery status
        if(request()->filled('filter_delivery_status')){
            $status = request('filter_delivery_status') == 'delivered' ? 1 : 0;
            $query->whereHas('invoice', function($inv) {
                $inv->where('is_delivered', $status);
            });
        }        

        //filter by search
        if(request()->filled('search')){
            $query->where(function ($q) {
                $q->Where('service_order_id', 'LIKE', '%' . request('search')['value']. '%');
                $q->orWhereHas('client', function ($client) {
                    $client->where('client_name', 'LIKE', '%' . request('search')['value'] . '%');
                });
            });
        }
        
        if(request()->filled('orderBy')){
            $query->orderBy('create_at', request('orderBy'));
        }else{
            $query->orderBy('id', 'DESC');
        }

        $query->with('client','driver', 'invoice', 'items', 'items.product_variant.product');

        //get
        return $query;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function getOrderItem($id=null){

        //new object
        $query = $this->orderItem->Query();

        //filters

        if($id){
            $query->where('id',$id);
        }

        if(request()->filled('filter_order_item_by_id')){
            $query->where('service_order_id',request('filter_order_item_by_id'));
        }

        //filter by machine
        if(request()->filled('filter_machine_id')){
            $query->where('machine_id',request('filter_machine_id'));
        }

        //filter by customer
        if(request()->filled('filter_client_id')){
            $query->whereHas('service_order', function($q){
                $q->where('client_id',request('filter_client_id'));
            });
        }

        //filter by operator
        if(request()->filled('filter_operator_id')){
            $query->where('operator_id',request('filter_operator_id'));
        }

        //filter by mileage from
        if(request()->filled('filter_mileage_from')){
            $query->where('total_run', '>=', request('filter_mileage_from'));
        }

        //filter by mileage to
        if(request()->filled('filter_mileage_to')){
            $query->where('total_run', '<=', request('filter_mileage_to'));
        }

        //filter by date start
        if(request()->filled('filter_date_start')){
             $query->where('updated_at', '>=', request('filter_date_start'));
        }

        //filter by date end
        if(request()->filled('filter_date_end')){
            $query->where('updated_at', '<=', request('filter_date_end'));
        }
        
        $query->orderBy('id', 'DESC');

        $query->with('product_variant', 'product_variant.product', 'operator', 'service_order','service_order.client');

        //get
        return $query;
    }

    /**
     * create
     * @param string $type the type of the category
     * @return object
     */
    public function create(){

        //new object
        $order = new $this->order;
        $order->service_order_id = request('service_order_id');
        $order->client_id = request('client');
        $order->driver_id = request('driver');
        $order->remarks = request('remarks');
        $order->total_pieces = request('total_pieces');
        $order->acc_remark = request('acc_remark');
        $order->take_pvc = request('take_pvc') != '' ? 1 : 0;
        $order->pvc_dimensions = request('pvc_dimensions');
        $order->handcraft_remark = request('handcraft_remark');
        $order->thik_remark = request('thik_remark');

        if($order->save()){
            return $order;
        }else{
            dd('log');
        }

    }

    /**
     * update
     * @param string $type the type of the category
     * @return object
     */
    public function update($id){

        //new object
        $data = request()->only(['remarks', 'service_status', 'status', 'acc_remark', 'handcraft_remark', 'thik_remark','total_pieces','pvc_dimensions', 'completed_date', 'deliver_date']);

        if(request()->filled('client')){
            $data['client_id'] = request('client');
        }

        if(request()->filled('qc_check_id')){
            $data['qc_check_id'] = request('qc_check_id');
        }

        if(request()->filled('driver')){
            $data['driver_id'] = request('driver');
        }

        $serviceOrder = $this->order->findOrFail($id);

        $serviceOrder->fill($data);

        if($serviceOrder->save()){
            return $serviceOrder;
        }else{
            dd('log');
        }

    }

    /**
     * Store SO item
     * @param string $type the type of the category
     * @return object
     */
    public function storeItem(array $data){

        $item = $this->orderItem->create($data);

        if($item){
            return $item;
        }else{
            dd('log');
        }

    }

    /**
     * Update SO item
     * @param string $type the type of the category
     * @return object
     */
    public function updateItem($id, array $data){

        $item = $this->orderItem->findOrFail($id);

        $item->fill($data);

        if($item->save()){
            return $item;
        }else{
            dd('log');
        }

    }


    /**
     * Update SO item
     * @param string $type the type of the category
     * @return object
     */
    public function statusValidate($id){

        $order = $this->get($id)->first();
        // foreach(['acc', 'standard'] as $type){
        //     $Item = $order->items->first(function ($item) use($type) {
        //         return $item->product_variant && $item->product_variant->product_option_type === $type;
        //     });
        //     if($Item){
        //         $desiredItemKey = '';
        //         foreach (request('item_id') as $key => $value) {
        //             if ($value == $Item->id) {
        //                 $desiredItemKey = (int)$key;
        //                 break;
        //             }
        //         }
        //         if($Item->total_run <= 0 && (int)request('item_mileage')[$desiredItemKey] <= 0){
        //             return $type.' is require';
        //         }
        //     }
        // }
        return false;

    }

    /**
     * Update SO item
     * @param string $type the type of the category
     * @return object
     */
    public function updateStatus($id){

        $order = $this->get($id)->first();
        $status = null;
        foreach(['acc','standard','hc','tp'] as $type){
            $Items = $order->items->filter(function ($item) use($type) {
                return $item->product_variant && $item->product_variant->product_option_type === $type;
            });
            foreach($Items as $Item){
                if($Item->total_run <= 0){
                    switch($type){
                        case 'acc':
                            $status = 'acc-pending';
                        break 3;
                        case 'standard':
                            $status = 'standard-pending';
                        break 3;
                        case 'hc':
                            $status = 'hc-pending';
                        break 3;
                        case 'tp':
                            $status = 'pvc-pending';
                        break 3;
                    }
                }
            }
        }
        if(!$status){
            $status = 'qc-pending';
        }

        request()->merge(['service_status' => $status]);
        $this->update($id);
    }
}
