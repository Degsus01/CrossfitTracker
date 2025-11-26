<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // agrega 'presente' si no existe
            if (!Schema::hasColumn('asistencias', 'presente')) {
                $table->boolean('presente')->default(true)->after('fecha');
            }
        });

        // Si existía una columna 'estado', intenta migrar sus valores a 'presente'
        if (Schema::hasColumn('asistencias', 'estado')) {
            // ejemplo: si estado es 'Presente' => 1, cualquier otro => 0
            DB::table('asistencias')->update([
                'presente' => DB::raw("CASE WHEN estado IN ('Presente','1',1,'Si','Sí','si','sí') THEN 1 ELSE 0 END")
            ]);

            // opcional: elimina 'estado'
            Schema::table('asistencias', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }
    }

    public function down(): void
    {
        // revertir: eliminar 'presente' (no recuperamos 'estado')
        Schema::table('asistencias', function (Blueprint $table) {
            if (Schema::hasColumn('asistencias', 'presente')) {
                $table->dropColumn('presente');
            }
        });
    }
};

