<?php

use App\Retencion;
use App\Iva;
use App\Carpeta;
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

        // DB::table('retenciones')->insert([
        //     'nombre' => 'IRPF 15',
        //     'importe' => 15
        // ]);

        $reg = new Retencion;
        $reg->nombre = "IRPF ";
        $reg->importe = 15;
        $reg->save();

        $reg = new Iva;
        $reg->nombre = "General ";
        $reg->importe = 21;
        $reg->save();

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

    }
}
