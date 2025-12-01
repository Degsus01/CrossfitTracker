<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Miembro;

class MiembrosSeeder extends Seeder
{
    public function run(): void
    {
        $miembros = [
            [
                'nombre' => 'Juan',
                'apellido' => 'Rodríguez',
                'correo' => 'juan@example.com',
                'telefono' => '3015556677',
                'fecha_nacimiento' => '1995-06-10',
                'id_membresia' => 1, // relaciona con tabla membresías
            ],
            [
                'nombre' => 'Laura',
                'apellido' => 'Martínez',
                'correo' => 'laura@example.com',
                'telefono' => '3028889999',
                'fecha_nacimiento' => '1998-03-22',
                'id_membresia' => 2,
            ],
        ];

        foreach ($miembros as $data) {
            Miembro::updateOrCreate(
                ['correo' => $data['correo']], // evita duplicados
                $data
            );
        }
    }
}
