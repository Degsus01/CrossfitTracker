<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgresosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('progresos')->insert([
            [
                'miembro_id' => 1,
                'fecha_registro' => '2025-02-01',
                'peso' => 75,
                'grasa_corporal' => 18.5,
                'masa_muscular' => 40.2,
                'observaciones' => 'Progreso notable en fuerza y resistencia.',
            ],
            [
                'miembro_id' => 2,
                'fecha_registro' => '2025-03-01',
                'peso' => 58,
                'grasa_corporal' => 22.1,
                'masa_muscular' => 36.8,
                'observaciones' => 'Ha mejorado su flexibilidad y tono muscular.',
            ],
        ]);
    }
}
