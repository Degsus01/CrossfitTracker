<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pagos')->insert([
            [
                'miembro_id' => 1, // ✅ corregido
                'monto' => 80000,
                'fecha_pago' => '2025-01-05',
                'metodo' => 'Efectivo',
            ],
            [
                'miembro_id' => 2, // ✅ corregido
                'monto' => 150000,
                'fecha_pago' => '2025-02-10',
                'metodo' => 'Transferencia',
            ],
        ]);
    }
}
