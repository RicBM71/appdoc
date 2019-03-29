<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->String('razon', 50);
            $table->String('nombre', 50);
            $table->String('direccion',50)->nullable()->default(null);
            $table->String('cpostal',10)->nullable()->default(null);
            $table->String('poblacion', 50)->nullable()->default(null);
            $table->String('provincia', 30)->nullable()->default(null);
            $table->String('telefono1',15)->nullable()->default(null);
            $table->String('telefono2',15)->nullable()->default(null);
            $table->String('tfmovil',15)->nullable()->default(null);
            $table->String('email',50)->nullable()->default(null);
            $table->String('contacto',50)->nullable()->default(null);
            $table->String('tipodoc',1)->nullable()->default(null);
            $table->String('cif',20)->nullable()->default(null);
            $table->timestamp('fechaalta')->nullable()->default(null);
            $table->timestamp('fechabaja')->nullable()->default(null);
            $table->String('web',50)->nullable()->default(null);
            $table->unsignedInteger('carpeta_id')->nullable()->default(null);
            $table->String('patron',50)->nullable()->default(null);
            $table->String('notas1')->nullable()->default(null);
            $table->String('efact')->nullable()->default(null);
            $table->String('eusr')->nullable()->default(null);
            $table->String('epass')->nullable()->default(null);
            $table->String('egrupo')->nullable()->default(null);
            $table->unsignedInteger('fpago_id')->nullable()->default(null);
            $table->String('factusn',1)->nullable()->default(null);
            $table->String('iban',40)->nullable()->default(null);
            $table->String('ref19',15)->nullable()->default(null);
            $table->String('username',20)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
