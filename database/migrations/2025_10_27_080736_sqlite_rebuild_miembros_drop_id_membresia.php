<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Esta migración era un fix especial para SQLite.
        // En MySQL no hace falta hacer nada aquí.
    }

    public function down(): void
    {
        // Tampoco necesitamos revertir nada.
    }
};
