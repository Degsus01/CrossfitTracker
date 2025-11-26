<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clases_virtuales', function (Blueprint $table) {
            $table->id('id_clase'); // Clave primaria
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('url_recurso'); // Enlace o archivo
            $table->string('tipo_recurso')->default('video'); // Tipo: video, PDF, etc.
            $table->date('fecha_publicacion')->nullable();

            // 🔗 Clave foránea correcta hacia entrenadores(id)
            $table->unsignedBigInteger('entrenador_id')->nullable();
            $table->foreign('entrenador_id')
                ->references('id')
                ->on('entrenadores')
                ->nullOnDelete();

            $table->timestamps(); // Registra fecha de creación y actualización
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases_virtuales');
    }
};


