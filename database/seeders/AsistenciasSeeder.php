<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenciasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('asistencias')->insert([
            [
                'miembro_id' => 1,
                'entrenador_id' => 1,
                'fecha' => '2025-01-06',
                'presente' => 'presente', // 👈 cambia aquí
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'miembro_id' => 2,
                'entrenador_id' => 1,
                'fecha' => '2025-01-07',
                'presente' => 'ausente', // 👈 también puedes usar otro valor
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
