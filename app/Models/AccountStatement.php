<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatement extends Model
{
    use HasFactory;
    protected $appends = ['attachment_invoice'];

    protected $table = 'account_statements';
    protected $fillable = [
        'account_statement_id',
        'client_id',
        'due_amount',
        'credit_amount',
        'payable_amount',
         'status'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function jobs(){
        return $this->belongsToMany(ServiceOrder::class);
    }

    public function getAttachmentInvoiceAttribute(){
       return Attachment::where('attachmentresource_type', 'statement-account-invoice')->where('attachmentresource_id', $this->id)->orderBy('created_at','desc')->first();
    }

    public function credit_notes(){
        return $this->belongsToMany(CreditNote::class)->withPivot('amount');
    }
}
