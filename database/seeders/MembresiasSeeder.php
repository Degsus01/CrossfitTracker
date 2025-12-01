<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membresia;

class MembresiasSeeder extends Seeder
{
    public function run(): void
    {
        // Plan Diario
        Membresia::updateOrCreate(
            ['nombre' => 'Diaria'],
            [
                'duracion_dias' => 1,
                'precio' => 10000,
                'descripcion' => 'Acceso por un solo día al gimnasio.',
            ]
        );

        // Plan Mensual
        Membresia::updateOrCreate(
            ['nombre' => 'Mensual'],
            [
                'duracion_dias' => 30,
                'precio' => 80000,
                'descripcion' => 'Acceso completo durante 30 días.',
            ]
        );

        // Plan Anual
        Membresia::updateOrCreate(
            ['nombre' => 'Anual'],
            [
                'duracion_dias' => 365,
                'precio' => 800000,
                'descripcion' => 'Acceso ilimitado durante todo el año.',
            ]
        );
    }
}
