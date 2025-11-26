<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->unsignedBigInteger('miembro_id');
            $table->unsignedBigInteger('entrenador_id')->nullable();
            $table->date('fecha');
            $table->string('estado')->default('presente');
            $table->timestamps();

            $table->foreign('miembro_id')->references('id')->on('miembros')->onDelete('cascade');
            $table->foreign('entrenador_id')->references('id')->on('entrenadores')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};

