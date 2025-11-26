<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Agregar columna role_id (nullable para no romper datos existentes)
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                    ->nullable()               // importante al inicio
                    ->after('email')
                    ->constrained('roles')    // FK a tabla roles
                    ->nullOnDelete();
            }
        });

        // 2) Asignar rol por defecto a los usuarios que ya existan
        $defaultRoleId = DB::table('roles')
            ->where('slug', 'miembro')
            ->value('id');

        if ($defaultRoleId) {
            DB::table('users')
                ->whereNull('role_id')
                ->update(['role_id' => $defaultRoleId]);
        }

        // 3) Eliminar columna vieja 'rol' si existe
        if (Schema::hasColumn('users', 'rol')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('rol');
            });
        }

        // Ojo: NO usamos ->change() para poner NOT NULL porque con SQLite
        // es conflictivo. Puedes dejarlo nullable sin problema.
    }

    public function down(): void
    {
        // 1) Volver a crear la columna 'rol' si quieres recuperar el estado anterior
        if (! Schema::hasColumn('users', 'rol')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('rol')->nullable()->after('email');
            });
        }

        // 2) Quitar la foreign key y la columna role_id
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                // elimina la restricción y la columna
                $table->dropConstrainedForeignId('role_id');
            });
        }
    }
};

