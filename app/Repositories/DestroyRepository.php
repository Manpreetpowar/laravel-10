<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for users
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\User;
use App\Models\Client;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Expense;
use App\Models\ServiceOrder;
use App\Models\AccountStatement;


use Illuminate\Http\Request;
use Log;

class DestroyRepository {

    public function deleteUser($id){
        $user = User::find($id);
        $user->delete();
        return true;
    }


    public function deleteClient($id){
        $client = Client::find($id);
        $client->delete();
        return true;
    }

    public function deleteProduct($id){
        $product = Product::find($id);
        $product->delete();
        return true;
    }

    public function deleteAccountStatement($id){
        $account_statement = AccountStatement::find($id);
        $account_statement->delete();
        return true;
    }

    public function deleteExpence($id){
        $expense = Expense::find($id);
        $expense->delete();
        return true;
    }

    public function deleteServiceOrder($id){
        $job = ServiceOrder::find($id);
        $job->delete();
        return true;
    }

}
