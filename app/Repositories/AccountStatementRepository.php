<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for clients
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Http\Request;
use App\Models\AccountStatement;
use App\Models\Client;
use App\Models\AccountStatementCreditNote;
use Log;

class AccountStatementRepository {

    /** Client models */
    protected $statement;
    protected $client;
    protected $ac_cn;

    public function __construct(
    AccountStatement $statement ,
    Client $client,
    AccountStatementCreditNote $ac_cn){
        $this->statement = $statement;
        $this->client    = $client;
        $this->ac_cn    = $ac_cn;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->statement->Query();
        //filters

        if($id){
            $query->where('id',$id);
        }

        //filter by accountStatment
        if(request()->filled('filter_client_id')){
            $query->where('client_id',request('filter_client_id'));
        }

        //filter by date start
        if(request()->filled('filter_date_start')){
            $query->whereDate('created_at', '>=', request('filter_date_start'));
        }

        //filter by date end
        if(request()->filled('filter_date_end')){
            $query->whereDate('created_at', '<=', request('filter_date_end'));
        }

        //filter by status
        if(request()->filled('filter_status')){
            $query->where('status', request('filter_status'));
        }

        //with
        $query->with('jobs','client','credit_notes');

        //get
        return $query;
    }

    /**
     * create
     * @param string $type the type of the category
     * @return object
     */
    public function create(array $data){
        $accountStatement = $this->statement->create($data);
        if($accountStatement){
            return $accountStatement;
        }else{
            dd('log');
        }
    }

    public function update($id, array $data){
        //new object
        $statement = $this->statement->findOrFail($id);
        if($statement->update($data)){
            return $statement;
        }else{
            dd('log');
        }

    }

    public function attech_credit_notes($id, array $data){
        //new object
        $statement = $this->statement->findOrFail($id);
        foreach($data as $cn){
            $this->ac_cn->create([
                'account_statement_id' => $id,
                'credit_note_id' => $cn['credit_note_id'],
                'amount' => $cn['amount']
            ]);
        }
        if($statement->update($data)){
            return $statement;
        }else{
            dd('log');
        }

    }


}
