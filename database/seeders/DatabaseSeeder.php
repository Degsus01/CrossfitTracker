<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders principales de la base de datos.
     */
    public function run(): void
{
    $this->call([
        RolesTableSeeder::class,
        AdminUserSeeder::class, // opcional
    ]);
}

}


