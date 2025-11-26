<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progreso extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'progresos';

    // Clave primaria personalizada
    protected $primaryKey = 'id_progreso';

    // Si no usas created_at y updated_at
    public $timestamps = false;

    // Campos que se pueden llenar (según tu estructura)
    protected $fillable = [
        'peso',
        'repeticiones',
        'rendimiento',
        'fecha_registro',
        'id_miembro',
    ];

    /* ==========================
       RELACIONES
       ========================== */

    // Un progreso pertenece a un miembro
    public function miembro()
    {
        return $this->belongsTo(Miembro::class, 'id_miembro');
    }
}
