<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatementCreditNote extends Model
{
    use HasFactory;
    protected $table = 'account_statement_credit_note';

    protected $fillable = [
        'account_statement_id',
        'credit_note_id',
        'amount'
    ];
}