<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entrenador;

class EntrenadoresSeeder extends Seeder
{
    public function run(): void
    {
        $entrenadores = [
            [
                'nombre' => 'Carlos',
                'apellido' => 'Pérez',
                'correo' => 'carlos@example.com',
                'telefono' => '3001112233',
                'especialidad' => 'Crossfit',
                'fecha_contratacion' => '2024-01-15',
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Gómez',
                'correo' => 'ana@example.com',
                'telefono' => '3004445566',
                'especialidad' => 'Entrenamiento funcional',
                'fecha_contratacion' => '2024-03-10',
            ],
        ];

        foreach ($entrenadores as $data) {
            Entrenador::updateOrCreate(
                ['correo' => $data['correo']], // clave única
                $data // datos a crear o actualizar
            );
        }
    }
}

