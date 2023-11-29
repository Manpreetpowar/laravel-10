<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for users
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Log;

class UserRepository {

    /** User models */
    protected $user;

    /**Profile Model */
    protected $profile;

    public function __construct(User $user, Profile $profile){

        // set valirables 
        $this->user = $user;
        $this->profile = $profile;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->user->Query();

        //filters
        $query->whereDoesntHave('roles', function ($where){
            $where->where('slug','administrator');
        });
        
        if($id){
            $query->where('id',$id);
        }
        
        // filter role
        if (request()->filled('filter_user_role_type')) {
            $roleSlug = request('filter_user_role_type');
            $query->whereHas('roles', function ($roleQuery) use ($roleSlug) {
                $roleQuery->where('slug', $roleSlug);
            });
        }
        
        // filter status
        if (request()->filled('filter_user_status')) {
            $status = request('filter_user_status');
            $query->where('status', $status);
        }

        $query->with('roles','profile');
        
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
        $user = new $this->user;
        $user->username = request('username');
        $user->name = request('name');
        $user->email = request('email');
        $user->password = bcrypt(request('password'));
        $user->status = request('status');

        if($user->save()){
            return $user;
        }else{
            dd('log');
        }

    }

     /**
     * create
     * @param string $type the type of the category
     * @return object
     */
    public function create_profile($user){

        //new object
        $profile = new $this->profile;
        $profile->user_id = $user->id;
        $profile->phone = request('phone');
        $profile->em_contact_name = request('em_contact_name');
        $profile->em_contact_number = request('em_contact_number');

        if(request()->filled('driver_vehicle_number')){
            $profile->driver_vehicle_number = request('driver_vehicle_number');
        }

        if($profile->save()){
            return $profile;
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
        $data = request()->only(['name', 'username', 'email','status']);
        if(!empty(request('password'))){
            $data['password'] = bcrypt(request('password'));
        }
        
        $user = $this->user->findOrFail($id);
    
        $user->fill($data);
    
        if($user->save()){
            return $user;
        }else{
            dd('log');
        }

    }


    /**
     * update profile
     * @param string $type the type of the category
     * @return object
     */
    public function update_profile($id){

        //new object
        $data = request()->except(['_token', '_method', 'name', 'username', 'email','password','role']);
        
        $user = $this->profile->where('user_id',$id)->firstOrFail();

        $user->fill($data);
    
        if($user->save()){
            return $user;
        }else{
            dd('log');
        }

    }

}