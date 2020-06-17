<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->bigIncrements('idreserva');
            $table->integer('iduser')->nullable();
            $table->dateTime('fecha_pedido')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->string('estado')->nullable();
            $table->integer('idcliente')->nullable();
            $table->string('tipo_comprobante')->nullable();
            $table->string('num_comprobante')->nullable();
            $table->decimal('total_venta',11 , 2)->nullable();
            $table->decimal('pago_efectivo',11 , 2)->nullable();
            $table->decimal('pago_debito',11 , 2)->nullable();
            $table->decimal('pago_credito',11 , 2)->nullable();
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
        Schema::dropIfExists('reservas');
    }
}
