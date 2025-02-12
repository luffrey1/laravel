<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('direccion_envio');
            $table->string('estado_envio')->default('pendiente');
            $table->string('metodo_pago');
           
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};

