<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for machine
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\User;
use App\Models\Machine;
use App\Models\AdHocService;
use Illuminate\Http\Request;
use Log;

class MachineRepository {

    /** User models */
    protected $service;

    /**Machine Model */
    protected $machine;

    public function __construct(AdHocService $service, Machine $machine){

        // set valirables
        $this->service = $service;
        $this->machine = $machine;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->machine->Query();

        //filters
        if($id){
            $query->where('id',$id);
        }

        //filter by customer
        // if(request()->filled('filter_client_id')){
        //     $query->where('client_id',request('filter_client_id'));
        // }

        $query->with('order_items.operator','order_items.service_order.client');

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
        $machine = new $this->machine;
        $machine->machine_id = request('machine_id');
        $machine->machine_name = request('machine_name');
        $machine->brand_name = request('brand_name');
        $machine->model = request('model');

        if($machine->save()){
            return $machine;
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
        $data = request()->only(['machine_name', 'operator_id', 'brand_name', 'model', 'total_mileage','current_mileage','mileage_servicing_reminder']);

        $machine = $this->machine->findOrFail($id);

        $machine->fill($data);

        if($machine->save()){
            return $machine;
        }else{
            dd('log');
        }

    }

    /**
     * increase mileage
     * @param string $type the type of the category
     * @return object
     */
    public function increaseMileage($id,$mileage){

        //new object
        $machine = $this->machine->findOrFail($id);

        $machine->total_mileage += $mileage;
        $machine->current_mileage += $mileage;

        if($machine->save()){
            return $machine;
        }else{
            dd('log');
        }
    }

    /**
     * decrease mileage
     * @param string $type the type of the category
     * @return object
     */
    public function decreaseMileage($id,$mileage){

        //new object
        $machine = $this->machine->findOrFail($id);

        $machine->total_mileage = max(0, $machine->total_mileage - $mileage);
        $machine->current_mileage = max(0, $machine->current_mileage - $mileage);

        if($machine->save()){
            return $machine;
        }else{
            dd('log');
        }
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get_services($id=null){

        //new object
        $query = $this->service->Query();

        //filters

        if($id){
            $query->where('id',$id);
        }

        // filter client
        if(request()->filled('machine_id')){
            $query->where('machine_id',request('machine_id'));
        }

        //get
        return $query;
    }

}
