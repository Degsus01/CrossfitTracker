<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'entrenadores';

    // Clave primaria personalizada
    protected $primaryKey = 'id_entrenador';

    // Si tu tabla no usa timestamps (created_at, updated_at)
    public $timestamps = false;

    // Campos que pueden llenarse con asignación masiva
    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'especialidad',
        'fecha_contratacion',
    ];

    /* ==========================
       RELACIONES
       ========================== */

    // Un entrenador puede tener muchas rutinas
    public function rutinas()
    {
        return $this->hasMany(Rutina::class, 'id_entrenador');
    }
}
