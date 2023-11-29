<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['invoice_number','client_id','service_order_id','payment_terms','gst_percent','gst_amount','discount_percent','discount_amount','sub_total','amount','is_delivered','invoice_paid','invoice_paid_date','discount_percent'];

    public function order(){
        return $this->belongsTo(ServiceOrder::class,'service_order_id', 'id');
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
