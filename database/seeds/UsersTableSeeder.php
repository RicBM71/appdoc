<?php

use App\User;
use App\Avatar;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        Role::truncate();
        User::truncate();

        $rootRole = Role::create(['name'=>'Root']);
        $adminRole = Role::create(['name'=>'Admin']);
        $userRole = Role::create(['name'=>'Usuario']);
        $userRole = Role::create(['name'=>'Gestor']);

        //$verPost = Permission::create(['name'=>'Ver Posts']);
        // $actPost = Permission::create(['name'=>'Actualizar Posts']);
        // $crearPost = Permission::create(['name'=>'Crear Posts']);
        // $borrarPost = Permission::create(['name'=>'Borrar Posts']);

        $verUser = Permission::create(['name'=>'Usuarios']);
        Permission::create(['name'=>'Edita Clientes']);
        //$actUser = Permission::create(['name'=>'Actualizar Usuarios']);
        // $crearUser = Permission::create(['name'=>'Crear Usuarios']);
        // $borrarUser = Permission::create(['name'=>'Borrar Usuarios']);

        // $verRole = Permission::create(['name'=>'Ver Roles']);
        // $actRole = Permission::create(['name'=>'Actualizar Roles']);
        // $crearRole = Permission::create(['name'=>'Crear Roles']);
        // $borrarRole = Permission::create(['name'=>'Borrar Roles']);

        $user = new User;

        $user->name = "Ric";
		$user->email = "info@sanaval.com";
		$user->username = "ricardo.bm";
		$user->password = Hash::make('123');
		$user->save();
        $user->assignRole($rootRole);


        for ($i=2; $i <= 10  ; $i++) {

            $user = new User;

            $user->name = "User ".$i;
            $user->email = "user".$i."@rr.es";
            $user->username = "User".$i;
            $user->empresa_id = 1;
            $user->password = Hash::make('123');
            $user->save();
            $user->assignRole($userRole);
        }

    }
}
