<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_name', 'client_email', 'poc_name', 'poc_contact', 'client_address', 'payment_terms', 'credit_limit', 'auto_send_email', 'outstanding', 'discount', 'apply_discount','credit_notes','lifetime_revenue'
    ];

    public function notes(){
        return $this->hasMany(CreditNote::class);
    }

    public function service_orders(){
        return $this->hasMany(ServiceOrder::class);
    }

    public function invoice(){
        return $this->hasMany(Invoice::class);
    }
}
