<?php

use App\Iva;
use App\Fpago;
use App\Cuenta;
use App\Carpeta;
use App\Empresa;
use App\Contador;
use App\Retencion;
use Carbon\Carbon;
use App\Vencimiento;
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
        Fpago::truncate();
        Vencimiento::truncate();

        Contador::truncate();

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
        $emp->razon = "Sanaval Tecnología SL";
        $emp->cif="B83667402";
        $emp->titulo = "Sanaval";
        $emp->logo = "logo.jpg";
        $emp->certificado = "sntfirma.crt";
        $emp->passwd_cer="delta00";
        $emp->save();

        $json = File::get("database/data/carpetas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Carpeta::create(array(
             'id' => $obj->id,
             'nombre' => $obj->nombre,
             'color' => $obj->color,
             'created_at'=> $obj->created_at,
             'updated_at'=> $obj->updated_at
           ));
        }

        DB::table('empresa_user')->insert(
            ['empresa_id' => 1, 'user_id' => '1']
        );

        $fp = new Fpago;
        $fp->nombre = "Transferencia";
        $fp->save();
        $fp = new Fpago;
        $fp->nombre = "Recibo Domiciliado";
        $fp->save();
        $fp = new Fpago;
        $fp->nombre = "Contado";
        $fp->save();


        $con = new Contador;
        $con->ejercicio = date('Y');
        $con->empresa_id = 1;
        $con->seriealb="ALB";
        $con->albaran=1000;
        $con->seriefac="F";
        $con->factura=1000;
        $con->serieabo="AB";
        $con->abono=1000;

        $con->save();

        $vto = new Vencimiento;
        $vto->nombre = "Fecha Factura";
        $vto->save();

        $cuenta = new Cuenta;
        $cuenta->empresa_id = 1;
        $cuenta->nombre = "Santander";
        $cuenta->iban="ES1500493102912114149064";
        $cuenta->bic="BSCHESMMXXX";
        $cuenta->sepa="ES35001B83667402";

        $cuenta->save();

    }
}
