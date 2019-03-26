<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Office;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
	    $role_manager = Role::where('name', 'manager')->first();
		$office_0000  = Office::where('code', '0000')->first();
	    
	    $manager = new User();
	    $manager->name = 'AlexG';
	    $manager->email = 'alex.furro@gmail.com';
	    $manager->password = bcrypt('1@Bsc66PM');
	    $manager->save();
	    $manager->roles()->attach($role_manager);        
	    $manager->offices()->attach($office_0000);
    }
}
