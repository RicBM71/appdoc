<?php

use App\Cliente;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

 		for ($i=0; $i < 10; $i++) {

            $cliente = new Cliente;
            $cliente->empresa_id = 1;
	        $cliente->nombre = $faker->name;
	        $cliente->razon = $faker->name;

	        $cliente->save();
	    }

    }
}
