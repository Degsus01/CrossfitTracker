<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->date('membresia_expira_at')->nullable()->after('membresia_id');
        });
    }

    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->dropColumn('membresia_expira_at');
        });
    }
};
