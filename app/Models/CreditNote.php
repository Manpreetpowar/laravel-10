<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use HasFactory;


    protected $appends = ['attachment_invoice'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'apply_gst', 'amount', 'terms', 'remark','partial_amount','status','gst_percent',
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function items(){
        return $this->hasMany(CreditNoteItem::class);
    }

    public function account_statement(){
        return $this->belongsToMany(AccountStatement::class);
    }

    public function getAttachmentInvoiceAttribute(){
       return Attachment::where('attachmentresource_type', 'credit-note-invoice')->where('attachmentresource_id', $this->id)->orderBy('created_at','desc')->first();
    }

    public function as_amount($id){
        $cn = AccountStatementCreditNote::where('account_statement_id',$id)->where('credit_note_id',$this->id)->first();
        return $cn->amount ?? 0;
    }
}
