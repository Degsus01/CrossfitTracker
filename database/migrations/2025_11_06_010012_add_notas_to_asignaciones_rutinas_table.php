<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asignaciones_rutinas', function (Blueprint $table) {
            if (! Schema::hasColumn('asignaciones_rutinas', 'notas')) {
                $table->string('notas', 500)->nullable()->after('fecha_asignacion');
            }
            // Previene duplicados exactos (opcional pero recomendado)
            if (! $this->hasIndex('asignaciones_rutinas', 'asig_unique_mrf')) {
                $table->unique(['miembro_id','rutina_id','fecha_asignacion'], 'asig_unique_mrf');
            }
        });
    }

    public function down(): void
    {
        Schema::table('asignaciones_rutinas', function (Blueprint $table) {
            if (Schema::hasColumn('asignaciones_rutinas', 'notas')) {
                $table->dropColumn('notas');
            }
            if ($this->hasIndex('asignaciones_rutinas', 'asig_unique_mrf')) {
                $table->dropUnique('asig_unique_mrf');
            }
        });
    }

    // Helpers para SQLite/MySQL sin romperse
    private function hasIndex(string $table, string $index): bool
    {
        try {
            return collect(Schema::getIndexes($table))->pluck('name')->contains($index);
        } catch (\Throwable $e) {
            return false;
        }
    }
};
