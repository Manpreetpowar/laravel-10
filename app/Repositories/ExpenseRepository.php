<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for expence
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Log;

class ExpenseRepository {

    /** User models */
    protected $service;

    /**Expense Model */
    protected $expense;

    public function __construct(Expense $expense){
        $this->expense = $expense;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->expense->Query();

        //filters

        if($id){
            $query->where('id',$id);
        }


        //filter by date start
        if(request()->filled('filter_date_start')){
            $query->whereDate('created_at', '>=', request('filter_date_start'));
       }

       //filter by date end
       if(request()->filled('filter_date_end')){
           $query->whereDate('created_at', '<=', request('filter_date_end'));
       }

       $query->orderBy('id', 'DESC');
       
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
        $expense = new $this->expense;
        $expense->expense_id = request('expense_id');
        $expense->expense_name = request('expense_name');
        $expense->category = request('category');
        $expense->amount = request('amount');
        $expense->remarks = request('remarks');

        if($expense->save()){
            return $expense;
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
        $data = request()->only(['expense_name', 'category', 'amount', 'remarks']);

        $expense = $this->expense->findOrFail($id);

        $expense->fill($data);

        if($expense->save()){
            return $expense;
        }else{
            dd('log');
        }

    }

}
