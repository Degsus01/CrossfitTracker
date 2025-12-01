<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si NO es sqlite o ya no existe la columna vieja, no hacemos nada aquí
        if (DB::getDriverName() !== 'sqlite' || !Schema::hasColumn('miembros', 'id_membresia')) {
            return;
        }

        // Desactivar FKs para poder reconstruir
        DB::statement('PRAGMA foreign_keys = OFF');

        // 1) Crear tabla nueva (sin id_membresia, SOLO con membresia_id)
        Schema::create('miembros_new', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('telefono')->nullable();
            $table->unsignedBigInteger('membresia_id')->nullable();
            $table->timestamps();
            $table->date('fecha_nacimiento')->nullable();
        });

        // 2) Copiar datos consolidando ambas columnas en membresia_id
        DB::statement('
            INSERT INTO miembros_new (id, nombre, apellido, correo, telefono, membresia_id, created_at, updated_at, fecha_nacimiento)
            SELECT id,
                   nombre,
                   apellido,
                   correo,
                   telefono,
                   COALESCE(membresia_id, id_membresia) AS membresia_id,
                   created_at,
                   updated_at,
                   fecha_nacimiento
            FROM miembros
        ');

        // 3) Reemplazar tabla
        Schema::drop('miembros');
        Schema::rename('miembros_new', 'miembros');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // Rollback: volver a tener id_membresia (no necesario si no lo usas, pero lo dejamos)
        if (DB::getDriverName() !== 'sqlite' || Schema::hasColumn('miembros', 'id_membresia')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::create('miembros_old', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('telefono')->nullable();
            $table->unsignedBigInteger('membresia_id')->nullable();
            $table->timestamps();
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedBigInteger('id_membresia')->nullable();
        });

        DB::statement('
            INSERT INTO miembros_old (id, nombre, apellido, correo, telefono, membresia_id, created_at, updated_at, fecha_nacimiento, id_membresia)
            SELECT id,
                   nombre,
                   apellido,
                   correo,
                   telefono,
                   membresia_id,
                   created_at,
                   updated_at,
                   fecha_nacimiento,
                   membresia_id
            FROM miembros
        ');

        Schema::drop('miembros');
        Schema::rename('miembros_old', 'miembros');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
