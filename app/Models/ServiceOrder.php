<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;


     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id', 'qc_check_id', 'driver_id', 'remarks', 'status', 'service_status', 'acc_remark', 'handcraft_remark', 'thik_remark','total_pieces','pvc_dimensions', 'completed_date', 'deliver_date'
    ];

    protected $appends = ['attachment'];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function driver(){
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    public function qc_checker(){
        return $this->belongsTo(User::class, 'qc_check_id', 'id');
    }

    public function items(){
        return $this->hasMany(ServiceOrderItem::class);
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }

    public function account_statement(){
        return $this->belongsToMany(AccountStatement::class);
    }

    public function getAttachmentAttribute(){
       return Attachment::where('attachmentresource_type', 'job-invoice')->where('attachmentresource_id', $this->id)->orderBy('created_at','desc')->first();
    }

}
