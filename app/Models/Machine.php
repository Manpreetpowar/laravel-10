<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

      protected $fillable = [
         'machine_name', 'operator_id', 'brand_name', 'model', 'total_mileage','current_mileage','mileage_servicing_reminder'
      ];

      public function services() {
         return $this->hasMany(AdHocService::class);   
      }

      public function operator() {
         return $this->belongsTo(User::class, 'operator_id', 'id');   
      }

      public function order_items() {
         return $this->hasMany(ServiceOrderItem::class);   
      }
}
