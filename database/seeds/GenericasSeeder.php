<?php

use App\Iva;
use App\Carpeta;
use App\Empresa;
use App\Retencion;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;


class GenericasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Retencion::truncate();
        Iva::truncate();
        Carpeta::truncate();
        Empresa::truncate();

        DB::table('empresa_user')->truncate();


        $reg = new Retencion;
        $reg->nombre = "IRPF ";
        $reg->importe = 15;
        $reg->save();

        $reg = new Iva;
        $reg->nombre = "General ";
        $reg->importe = 21;
        $reg->save();

        $emp = new Empresa;
        $emp->nombre = "Sanaval";
        $emp->razon = "Sanaval";
        $emp->titulo = "Sanaval";
        $emp->save();

        $json = File::get("database/data/carpetas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Carpeta::create(array(
             'id' => $obj->id,
             'empresa' => $obj->empresa,
             'nombre' => $obj->nombre,
             'color' => $obj->color,
             'created_at'=> $obj->created_at,
             'updated_at'=> $obj->updated_at
           ));
        }

        DB::table('empresa_user')->insert(
            ['empresa_id' => 1, 'user_id' => '1']
        );

    }
}
