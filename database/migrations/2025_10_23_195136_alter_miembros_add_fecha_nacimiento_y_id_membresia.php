<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            // Agregar fecha_nacimiento si no existe
            if (!Schema::hasColumn('miembros', 'fecha_nacimiento')) {
                $table->date('fecha_nacimiento')->nullable()->after('telefono');
            }

            // Agregar id_membresia + FK si no existe
            if (!Schema::hasColumn('miembros', 'id_membresia')) {
                $table->unsignedBigInteger('id_membresia')->nullable()->after('fecha_nacimiento');
                $table->foreign('id_membresia')
                      ->references('id')
                      ->on('membresias')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            // Quitar FK y columna id_membresia si existen
            if (Schema::hasColumn('miembros', 'id_membresia')) {
                // Si diera error por nombre de restricción, usa el nombre explícito:
                // $table->dropForeign('miembros_id_membresia_foreign');
                $table->dropForeign(['id_membresia']);
                $table->dropColumn('id_membresia');
            }

            // Quitar fecha_nacimiento si existe
            if (Schema::hasColumn('miembros', 'fecha_nacimiento')) {
                $table->dropColumn('fecha_nacimiento');
            }
        });
    }
};