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
            $table->string('genero');
            $table->year('anio');
            $table->string('titulo');
            $table->decimal('precio', 8, 2);
            $table->text('descripcion')->nullable();
            $table->integer('stock')->default(0);
            $table->foreignId('users_id')->constrained();
            $table->foreignId('ventas_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comics');
    }
};
