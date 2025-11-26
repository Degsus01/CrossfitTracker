<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clases_virtuales', function (Blueprint $table) {
            // 1) Crear la columna estándar si no existe
            if (!Schema::hasColumn('clases_virtuales', 'enlace_video')) {
                $table->string('enlace_video')->nullable()->after('descripcion');
            }
        });

        // 2) Migrar datos desde columnas antiguas si existen
        if (Schema::hasColumn('clases_virtuales', 'url')) {
            DB::statement('UPDATE clases_virtuales SET enlace_video = url WHERE enlace_video IS NULL');
            Schema::table('clases_virtuales', function (Blueprint $table) {
                $table->dropColumn('url');
            });
        }

        if (Schema::hasColumn('clases_virtuales', 'url_recurso')) {
            DB::statement('UPDATE clases_virtuales SET enlace_video = url_recurso WHERE enlace_video IS NULL');
            Schema::table('clases_virtuales', function (Blueprint $table) {
                $table->dropColumn('url_recurso');
            });
        }

        if (Schema::hasColumn('clases_virtuales', 'enlace')) {
            DB::statement('UPDATE clases_virtuales SET enlace_video = enlace WHERE enlace_video IS NULL');
            Schema::table('clases_virtuales', function (Blueprint $table) {
                $table->dropColumn('enlace');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clases_virtuales', function (Blueprint $table) {
            if (Schema::hasColumn('clases_virtuales', 'enlace_video')) {
                $table->dropColumn('enlace_video');
            }
        });
        // (No recreamos las columnas antiguas)
    }
};
