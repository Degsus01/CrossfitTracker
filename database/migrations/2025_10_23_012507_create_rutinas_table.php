<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rutinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('duracion_minutos')->nullable();
            $table->string('nivel')->nullable(); // principiantes, intermedio, avanzado
            $table->foreignId('entrenador_id')
                ->nullable()
                ->constrained('entrenadores')
                ->nullOnDelete();
            $table->integer('duracion_dias')->nullable(); // Duración estimada de la rutina
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutinas');
    }
};
