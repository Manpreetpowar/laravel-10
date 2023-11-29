<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for credit_note
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\AdHocService;
use Illuminate\Http\Request;
use Log;

class AdHocServiceRepository {

    /** AdHocService models */
    protected $service;

    public function __construct(AdHocService $service) {
        // set valirables 
        $this->service = $service;
    }


    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->service->Query();

        //filters
        
        if($id){
            $query->where('id',$id);
        }

        // filter client
        if(request()->filled('client_id')){
            $query->where('client_id',request('client_id'));
        }

        // filter date start
        if (request()->filled('columns.1.search.value')) {
            $date = explode('|', request('columns.1.search.value'));
            $query->whereDate('created_at', '>=', $date[0]);
            if(isset($date[1])){
                $query->whereDate('created_at', '<=', $date[1]);
            }
        }

        $query->orderBy('id', 'DESC');
        
        $query->with('machine');

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
        $service = new $this->service;
        $service->machine_id = request('machine_id');
        $service->service_id = request('service_id');

        if(request()->filled('reminder_date')){
            $service->reminder_date = request('reminder_date');
        }

        $service->remark = request('remark');
        
        if($service->save()){
            return $service;
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
        $data = request()->only(['reminder_date', 'service_date', 'remark', 'document']);
        
        $service = $this->service->findOrFail($id);
    
        $service->fill($data);
    
        if($service->save()){
            return $service;
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


}