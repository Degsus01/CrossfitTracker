<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar SOLO columnas obsoletas (sin FK)
        Schema::table('miembros', function (Blueprint $table) {
            if (Schema::hasColumn('miembros', 'fecha_inicio')) {
                $table->dropColumn('fecha_inicio');
            }
            if (Schema::hasColumn('miembros', 'fecha_fin')) {
                $table->dropColumn('fecha_fin');
            }
        });
    }

    public function down(): void
    {
        // Restaurar SOLO columnas obsoletas si hicieras rollback
        Schema::table('miembros', function (Blueprint $table) {
            if (!Schema::hasColumn('miembros', 'fecha_inicio')) {
                $table->date('fecha_inicio')->nullable();
            }
            if (!Schema::hasColumn('miembros', 'fecha_fin')) {
                $table->date('fecha_fin')->nullable();
            }
        });
    }
};
