<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asignaciones_rutinas', function (Blueprint $table) {
            $table->id();

            // Relación correcta con clases_virtuales(id_clase)
            $table->unsignedBigInteger('clase_virtual_id')->nullable();
            $table->foreign('clase_virtual_id')
                  ->references('id_clase')
                  ->on('clases_virtuales')
                  ->nullOnDelete();

            $table->foreignId('miembro_id')
                  ->constrained('miembros')
                  ->onDelete('cascade');

            $table->foreignId('rutina_id')
                  ->constrained('rutinas')
                  ->onDelete('cascade');

            $table->date('fecha_asignacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_rutinas');
    }
};

