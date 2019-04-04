<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbacabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albacabs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->Integer('ejercicio')->default(0);
            $table->Integer('albaran')->default(0);
            $table->String('serie', 3)->nullable();
            $table->date('fecha_alb')->nullable();
            $table->unsignedInteger('cliente_id');
            $table->Integer('ejefac')->nullable()->default(0);
            $table->String('factura', 10)->nullable();
            $table->date('fecha_fac')->nullable();
            $table->unsignedInteger('fpago_id');
            $table->unsignedInteger('vencimiento_id');
            $table->boolean('notificado')->default(false);
            $table->String('notas')->nullable();
            $table->String('username', 20)->nullable();
            $table->timestamps();
            $table->unique(['empresa_id','ejercicio', 'albaran', 'serie']);
            $table->unique(['empresa_id','ejefac', 'factura']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albacabs');
    }
}
