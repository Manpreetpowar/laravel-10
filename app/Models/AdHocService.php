<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdHocService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id', 'machine_id', 'reminder_date', 'service_date', 'remark', 'document'
    ];

    protected $appends = ['attachments'];

    public function getAttachmentsAttribute(){
       return Attachment::where('attachmentresource_type', 'service')->where('attachmentresource_id', $this->id)->orderBy('created_at','desc')->first();
    }


    public function machine() {
        return $this->belongsTo(Machine::class);   
    }
}
