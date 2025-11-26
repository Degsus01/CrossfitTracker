<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clases_virtuales', function (Blueprint $table) {
            // Renombres a los nombres usados en las vistas/controlador
            if (Schema::hasColumn('clases_virtuales', 'fecha_publicacion') && !Schema::hasColumn('clases_virtuales', 'fecha')) {
                $table->renameColumn('fecha_publicacion', 'fecha');
            }
            if (Schema::hasColumn('clases_virtuales', 'enlace_video') && !Schema::hasColumn('clases_virtuales', 'enlace')) {
                $table->renameColumn('enlace_video', 'enlace');
            }
            if (Schema::hasColumn('clases_virtuales', 'tipo_recurso') && !Schema::hasColumn('clases_virtuales', 'plataforma')) {
                $table->renameColumn('tipo_recurso', 'plataforma');
            }

            // Altas (si no existen)
            if (!Schema::hasColumn('clases_virtuales', 'hora')) {
                $table->string('hora', 5)->nullable()->after('fecha'); // HH:MM
            }
            if (!Schema::hasColumn('clases_virtuales', 'duracion_min')) {
                $table->unsignedSmallInteger('duracion_min')->nullable()->after('hora');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clases_virtuales', function (Blueprint $table) {
            if (Schema::hasColumn('clases_virtuales', 'duracion_min')) $table->dropColumn('duracion_min');
            if (Schema::hasColumn('clases_virtuales', 'hora')) $table->dropColumn('hora');

            if (Schema::hasColumn('clases_virtuales', 'plataforma') && !Schema::hasColumn('clases_virtuales', 'tipo_recurso')) {
                $table->renameColumn('plataforma', 'tipo_recurso');
            }
            if (Schema::hasColumn('clases_virtuales', 'enlace') && !Schema::hasColumn('clases_virtuales', 'enlace_video')) {
                $table->renameColumn('enlace', 'enlace_video');
            }
            if (Schema::hasColumn('clases_virtuales', 'fecha') && !Schema::hasColumn('clases_virtuales', 'fecha_publicacion')) {
                $table->renameColumn('fecha', 'fecha_publicacion');
            }
        });
    }
};
