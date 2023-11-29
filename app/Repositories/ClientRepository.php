<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for clients
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Client;
use App\Models\Profile;
use Illuminate\Http\Request;
use Log;

class ClientRepository {

    /** Client models */
    protected $client;

    public function __construct(Client $client){
        // set valirables
        $this->client = $client;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->client->Query();

        //filters
        if($id){
            $query->where('id',$id);
        }

        //filter payment type
        if (request()->filled('filter_payment_type') && request('filter_payment_type') != 'all') {
            $query->where('payment_terms',request('filter_payment_type'));
        }
     
        //filter show all balance
        if (request()->filled('filter_show_all_balance') && request('filter_show_all_balance') != 'all') {
            $query->orderBy('outstanding', request('filter_show_all_balance'))->where('outstanding', '>', 0);
        }

        //get
        $query->with('invoice','notes');
         return $query;
    }

    /**
     * create
     * @param string $type the type of the category
     * @return object
     */
    public function create(){

        //new object
        $client = new $this->client;
        $client->client_id      = request('client_id');
        $client->client_name    = request('client_name');
        $client->client_email   = request('client_email');
        $client->poc_name       = request('poc_name');
        $client->poc_contact    = request('poc_contact');
        $client->client_address = request('client_address');
        $client->payment_terms  = request('payment_terms');
        $client->credit_limit   = request('credit_limit') ?? 0;

        if($client->save()){
            return $client;
        }else{
            dd('log');
        }

    }

    /**
     * update
     * @param string $type the type of the category
     * @return object
     */
    public function update($id){

        //new object
        $data = request()->only(['client_name', 'client_email', 'poc_name', 'poc_contact', 'client_address', 'payment_terms', 'credit_limit', 'credit_notes', 'auto_send_email', 'outstanding', 'discount', 'apply_discount','lifetime_revenue']);

        $client = $this->client->findOrFail($id);

        $client->fill($data);

        if($client->save()){
            return $client;
        }else{
            dd('log');
        }

    }

}
