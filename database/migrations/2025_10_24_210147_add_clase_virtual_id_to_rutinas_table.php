<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            $table->unsignedBigInteger('clase_virtual_id')->nullable()->after('id');

            $table->foreign('clase_virtual_id')
                ->references('id_clase') 
                ->on('clases_virtuales')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            $table->dropForeign(['clase_virtual_id']);
            $table->dropColumn('clase_virtual_id');
        });
    }
};
