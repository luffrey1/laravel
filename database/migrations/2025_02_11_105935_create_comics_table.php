<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->unique();
            $table->string('titulo');
            $table->integer('precio');
            $table->integer('stock')->default(0); // Inicialmente 0
            $table->timestamps();
            // venta id y user id
        });
    }

    public function down()
    {
        Schema::dropIfExists('comics');
    }
};
