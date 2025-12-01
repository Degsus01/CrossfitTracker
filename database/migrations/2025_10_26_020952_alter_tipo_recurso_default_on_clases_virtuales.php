<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Rellenar nulos existentes con 'video'
        DB::table('clases_virtuales')
            ->whereNull('tipo_recurso')
            ->update(['tipo_recurso' => 'video']);

        // 2) Poner DEFAULT 'video' en la columna (y mantenerla NOT NULL si ya lo está)
        Schema::table('clases_virtuales', function (Blueprint $table) {
            $table->string('tipo_recurso', 50)->default('video')->change();
        });
    }

    public function down(): void
    {
        // Revertir: quitar default (dejarla sin default)
        Schema::table('clases_virtuales', function (Blueprint $table) {
            $table->string('tipo_recurso', 50)->nullable(false)->default(null)->change();
            // Si tu motor no acepta default(null) en NOT NULL, puedes dejarla sin default:
            // $table->string('tipo_recurso', 50)->nullable(false)->change();
        });
    }
};
