<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('cliente_id');
            $table->date('fecha');
            $table->String('iban_cargo',24)->nullable()->default(null);
            $table->String('bic_cargo',11)->nullable()->default(null);
            $table->String('iban_abono',24)->nullable()->default(null);
            $table->String('bic_abono',11)->nullable()->default(null);
            $table->decimal('importe', 10, 2)->default(0);
            $table->String('concepto', 100);
            $table->boolean('enviada')->default(false);
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
        Schema::dropIfExists('transferencias');
    }
}
