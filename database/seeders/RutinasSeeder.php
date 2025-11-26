<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RutinasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rutinas')->insert([
            ['nombre' => 'Fuerza básica', 'descripcion' => 'Rutina para mejorar fuerza general', 'duracion_dias' => 30],
            ['nombre' => 'Cardio intensivo', 'descripcion' => 'Rutina enfocada en resistencia cardiovascular', 'duracion_dias' => 45],
        ]);
    }
}
