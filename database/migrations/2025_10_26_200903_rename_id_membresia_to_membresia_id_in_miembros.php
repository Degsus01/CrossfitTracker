<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Esta migración renombraba id_membresia -> membresia_id
        // pero en el esquema actual la tabla ya se crea con membresia_id.
        // No hacemos nada para evitar el error de columna duplicada.
    }

    public function down(): void
    {
        // Nada que deshacer.
    }
};
