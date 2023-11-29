<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
			'username' => 'admin',
            'password' => Hash::make('123456'),
        ]); 

        $administrator_role = new Role();
		$administrator_role->slug = 'administrator';
		$administrator_role->name = 'Administrator';
		$administrator_role->user_id = $admin->id;
		$administrator_role->save();

        $admin->roles()->attach($administrator_role);

        $driver_role = new Role();
		$driver_role->slug = 'driver';
		$driver_role->name = 'Driver';
		$driver_role->user_id = $admin->id;
		$driver_role->save();

        $operator_role = new Role();
		$operator_role->slug = 'operator';
		$operator_role->name = 'Operator';
		$operator_role->user_id = $admin->id;
		$operator_role->save();

        $floor_manager_role = new Role();
		$floor_manager_role->slug = 'floor_manager';
		$floor_manager_role->name = 'Floor Manager';
		$floor_manager_role->user_id = $admin->id;
		$floor_manager_role->save();
		
        $account_role = new Role();
		$account_role->slug = 'account';
		$account_role->name = 'Account';
		$account_role->user_id = $admin->id;
		$account_role->save();

    }
}
