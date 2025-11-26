<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_dias',
    ];

    public $timestamps = false;

    // Relación: una membresía tiene muchos miembros
    public function miembros()
    {
        return $this->hasMany(Miembro::class, 'id_membresia');
    }
}
