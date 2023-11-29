<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expense_name', 'category', 'amount', 'remarks'
    ];


    protected $appends = ['attachments'];

    public function getAttachmentsAttribute(){
       return Attachment::where('attachmentresource_type', 'expense')->where('attachmentresource_id', $this->id)->orderBy('created_at','desc')->first();
    }

    
}
