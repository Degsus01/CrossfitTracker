<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- FECHA ---
        $hasFecha      = Schema::hasColumn('pagos', 'fecha');
        $hasFechaPago  = Schema::hasColumn('pagos', 'fecha_pago');

        if ($hasFechaPago && $hasFecha) {
            // Copia valores donde 'fecha' esté NULL
            DB::statement('UPDATE pagos SET fecha = COALESCE(fecha, fecha_pago) WHERE fecha IS NULL');
            Schema::table('pagos', function (Blueprint $table) {
                $table->dropColumn('fecha_pago');
            });
        } elseif ($hasFechaPago && ! $hasFecha) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->renameColumn('fecha_pago', 'fecha');
            });
        }

        // --- METODO ---
        $hasMetodo     = Schema::hasColumn('pagos', 'metodo');
        $hasMetodoPago = Schema::hasColumn('pagos', 'metodo_pago');

        if ($hasMetodoPago && $hasMetodo) {
            // Copia valores donde 'metodo' esté NULL
            DB::statement('UPDATE pagos SET metodo = COALESCE(metodo, metodo_pago) WHERE metodo IS NULL');
            Schema::table('pagos', function (Blueprint $table) {
                $table->dropColumn('metodo_pago');
            });
        } elseif ($hasMetodoPago && ! $hasMetodo) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->renameColumn('metodo_pago', 'metodo');
            });
        }

        // --- CAMPOS NUEVOS ---
        Schema::table('pagos', function (Blueprint $table) {
            if (! Schema::hasColumn('pagos', 'referencia')) {
                $table->string('referencia', 100)->nullable()->after('metodo');
            }
            if (! Schema::hasColumn('pagos', 'notas')) {
                $table->string('notas', 500)->nullable()->after('referencia');
            }
        });
    }

    public function down(): void
    {
        // Revertir nuevos campos
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'notas')) {
                $table->dropColumn('notas');
            }
            if (Schema::hasColumn('pagos', 'referencia')) {
                $table->dropColumn('referencia');
            }
        });

        // Renombrar de vuelta si aplica
        if (Schema::hasColumn('pagos', 'metodo') && ! Schema::hasColumn('pagos', 'metodo_pago')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->renameColumn('metodo', 'metodo_pago');
            });
        }
        if (Schema::hasColumn('pagos', 'fecha') && ! Schema::hasColumn('pagos', 'fecha_pago')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->renameColumn('fecha', 'fecha_pago');
            });
        }
    }
};
