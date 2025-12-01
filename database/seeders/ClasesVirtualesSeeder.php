<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasesVirtualesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clases_virtuales')->insert([
            [
                'titulo' => 'Entrenamiento funcional en casa',
                'descripcion' => 'Clase grabada de 40 minutos con ejercicios de cuerpo completo.',
                'tipo_recurso' => 'video',
                'url_recurso' => 'https://example.com/funcional',
                'fecha_publicacion' => '2025-01-10',
                'entrenador_id' => 1, // 🔥 corregido
            ],
            [
                'titulo' => 'Yoga para principiantes',
                'descripcion' => 'Clase virtual de relajación y flexibilidad guiada por entrenador certificado.',
                'tipo_recurso' => 'video',
                'url_recurso' => 'https://example.com/yoga',
                'fecha_publicacion' => '2025-02-05',
                'entrenador_id' => 2, // 🔥 corregido
            ],
        ]);
    }
}
