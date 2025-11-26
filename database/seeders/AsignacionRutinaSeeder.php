<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignacionRutinaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('asignaciones_rutinas')->insert([
    [
        'miembro_id' => 1,
        'rutina_id' => 1,
        'fecha_asignacion' => '2025-01-01',
    ],
    [
        'miembro_id' => 2,
        'rutina_id' => 2,
        'fecha_asignacion' => '2025-02-01',
    ],
]);

    }
}
