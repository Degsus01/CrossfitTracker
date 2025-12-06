<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entrenador;

class EntrenadoresSeeder extends Seeder
{
    public function run(): void
    {
        $entrenadores = DB::table('roles')->where('slug','entrenador')->value('id');
        
        DB::table('users')->updateOrInsert(
            ['email' => 'entrenador@example.com'],
            [
                'name' => 'Jhon',
                'email' => 'entrenador@example.com',
                'password' => Hash::make('entrenador123'), // cámbialo
                'role_id' => $entrenadores,
            ]
        );
        
        
        
        
        
        
        
       
    }
}

