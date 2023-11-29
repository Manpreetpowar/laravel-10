<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for credit_note
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use Illuminate\Http\Request;
use App\Models\Client;
use Log;

class CreditNoteRepository {

    /** CreditNote models */
    protected $credit_note;

    /** CreditNoteItem models */
    protected $credit_note_item;


    public function __construct(
        CreditNote $credit_note,
        CreditNoteItem $credit_note_item,
        ) {
        // set valirables
        $this->credit_note = $credit_note;
        $this->credit_note_item = $credit_note_item;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->credit_note->Query();

        //filters

        if($id){
            $query->where('id',$id);
        }

        // filter client
        if(request()->filled('client_id')){
            $query->where('client_id',request('client_id'));
        }

        // filter date start
        if (request()->filled('filter_date_start')) {
            $query->whereDate('created_at', '>=', request('filter_date_start'));
        }

        // filter date start
        if (request()->filled('filter_date_end')) {
            $query->whereDate('created_at', '<=', request('filter_date_start'));
        }

        // filter status
        if (request()->filled('filter_status')) {
            $query->where('status', request('filter_status'));
        }

        $query->orderBy('id', 'DESC');
        
        $query->with('client');

        //get
        return $query;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get_credit_note($id=null){

        //new object
        $query = $this->credit_note_item->Query();

        //filters

        if($id){
            $query->where('id',$id);
        }

        // filter credit_note
        if(request()->filled('credit_note_id')){
            $query->where('credit_note_id',request('credit_note_id'));
        }


        $query->with('credit_note');

        //get
        return $query;
    }

    /**
     * create
     * @param string $type the type of the category
     * @return object
     */
    public function create(){

        //new object
        $credit_note = new $this->credit_note;
        $credit_note->client_id = request('client_id');
        $credit_note->note_id = request('note_id');
        $credit_note->apply_gst = request('apply_gst');
        $credit_note->terms = request('terms');
        $credit_note->amount = request('amount');
        $credit_note->gst_percent = request('gst_percent');

        if($credit_note->save()){
            return $credit_note;
        }else{
            dd('log');
        }

    }

    /**
     * create_note_items
     * @param string $type the type of the category
     * @return object
     */
    public function create_note_items($note_id,$data){
        //new object
        foreach($data as $item){
            $credit_note_item = new $this->credit_note_item;
            $credit_note_item->credit_note_id = $note_id;
            $credit_note_item->item_code = $item['item_code'];
            $credit_note_item->quantity = $item['quantity'];
            $credit_note_item->unit_price = $item['unit_price'];
            $credit_note_item->total_price = $item['total_price'];
            $credit_note_item->save();
        }
        return true;
    }

    /**
     * update
     * @param string $type the type of the category
     * @return object
     */
    public function update($id){

        //new object
        $data = request()->only(['apply_gst', 'terms', 'amount','remark','status','gst_percent','partial_amount']);

        $credit_note = $this->credit_note->findOrFail($id);

        $credit_note->fill($data);

        if($credit_note->save()){
            return $credit_note;
        }else{
            dd('log');
        }

    }

    /**
     * update_note_items
     * @param string $type the type of the category
     * @return object
     */
    public function update_note_items($item_id,$data)
    {
        $credit_note_item = $this->credit_note_item->find($item_id);
        $credit_note_item->item_code = $data['item_code'];
        $credit_note_item->quantity = $data['quantity'];
        $credit_note_item->unit_price = $data['unit_price'];
        $credit_note_item->total_price = $data['total_price'];
        if($credit_note_item->save()){
            return $credit_note_item;
        }else{
            dd('log');
        }
    }

    /**
     * delete
     * @param string $type the type of the category
     * @return object
     */
    public function delete($id)
    {
        $credit_note = $this->credit_note->find($id);
        if($credit_note->delete()){
            return true;
        }
    }

    /**
     * delete item
     * @param string $type the type of the category
     * @return object
     */
    public function delete_item($id)
    {
        $credit_note_item = $this->credit_note_item->find($id);

        $cn = $this->get($credit_note_item->credit_note_id)->first();
        $amount = $cn ? ($cn->amount - $credit_note_item->total_price) : 0;
        request()->merge(['amount'=>$amount]);
        $this->update($credit_note_item->credit_note_id);

        if($credit_note_item->delete()){
            return true;
        }
    }

}
