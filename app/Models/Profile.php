<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'em_contact_name',
        'em_contact_number',
        'driver_vehicle_number'
    ];

    

    public function user() {
        return $this->belongsTo(User::class);   
    }
}
