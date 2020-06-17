<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserva_detalles', function (Blueprint $table) {
            $table->bigIncrements('idreserva_detalle');
            $table->integer('idreserva')->nullable();
            $table->integer('idarticulo')->nullable();
            $table->decimal('cantidad', 11,2)->nullable();
            $table->decimal('precio_venta', 11,2)->nullable();
            $table->string('descuento')->nullable();
            $table->string('estado')->nullable();
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
        Schema::dropIfExists('reserva_detalles');
    }
}
