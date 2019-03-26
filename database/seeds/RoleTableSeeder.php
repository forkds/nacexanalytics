<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
	    $role_administrador = new Role();
	    $role_administrador->name = 'admin';
	    $role_administrador->description = 'Un administrador';
	    $role_administrador->save();

	    $role_manager = new Role();
	    $role_manager->name = 'manager';
	    $role_manager->description = 'Un manager';
	    $role_manager->save();
	    
	    $role_usuario = new Role();
	    $role_usuario->name = 'user';
	    $role_usuario->description = 'Un usuario';
	    $role_usuario->save();	    
    }
}
