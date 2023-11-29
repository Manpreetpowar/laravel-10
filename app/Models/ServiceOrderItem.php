<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderItem extends Model
{
    use HasFactory;

     /**
     * Table name.
     *
     */
    protected $table = 'order_items';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_order_id',
        'product_variant_id',
        'operator_id',
        'machine_id',
        'item_name',
        'quantity',
        'remarks',
        'price',
        'amount',
        'type',
        'total_run'
    ];

    public function service_order(){
        return $this->belongsTo(ServiceOrder::class);
    }

    public function product_variant(){
        return $this->belongsTo(ProductVariant::class);
    }

    public function operator(){
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }

    public function machine(){
        return $this->belongsTo(Machine::class);
    }

    public function getVisibilityAttribute(){
        if($this->type == 'inventory'){
            if($this->product_variant->product_option_type == 'standard'){
                $Item = $this->service_order->items->first(function($item){
                    return $item->product_variant && $item->product_variant->product_option_type === 'acc' && $item->product_variant->product_id === $this->product_variant->product_id;
                });

                if($Item && $Item->total_run <= 0){
                    return false;
                }
            }

            if($this->product_variant->product_option_type == 'hc' || $this->product_variant->product_option_type == 'tp'){
                $Item = $this->service_order->items->first(function($item){
                    return $item->product_variant && $item->product_variant->product_option_type === 'standard' && $item->product_variant->product_id === $this->product_variant->product_id;
                });

                if($Item && $Item->total_run <= 0){
                    return false;
                }
            }
        }

        return true;
    }
}
