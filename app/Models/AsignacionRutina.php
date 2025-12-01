<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionRutina extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'asignacion_rutina';

    // Clave primaria personalizada
    protected $primaryKey = 'id_asignacion';

    // Si no usas created_at y updated_at
    public $timestamps = false;

    // Campos que pueden llenarse
    protected $fillable = [
        'id_miembro',
        'id_rutina',
        'fecha_asignacion',
    ];

    /* ==========================
       RELACIONES
       ========================== */

    // Una asignación pertenece a un miembro
    public function miembro()
    {
        return $this->belongsTo(Miembro::class, 'id_miembro');
    }

    // Una asignación pertenece a una rutina
    public function rutina()
    {
        return $this->belongsTo(Rutina::class, 'id_rutina');
    }
}
