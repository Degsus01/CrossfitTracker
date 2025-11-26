<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    use HasFactory;

    protected $table = 'rutinas';
    // PK por defecto es 'id'; no hace falta declararla.
    // protected $primaryKey = 'id';

    // La tabla tiene timestamps; no desactivarlos.
    // public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_minutos',
        'nivel',
        'tipo',     // 'Presencial' | 'Virtual'
        'categoria',         // 'Fuerza'| 'Hiit'...
        'entrenador_id',
        // Incluye esta si agregaste la relación con clases virtuales
        'clase_virtual_id',
    ];

    /* ==========================
       RELACIONES
       ========================== */

    // Una rutina pertenece a un entrenador
    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'entrenador_id', 'id');
    }

    // (Opcional) Si sumaste la FK a clases virtuales
    public function claseVirtual()
    {
        return $this->belongsTo(ClaseVirtual::class, 'clase_virtual_id');
    }

    // Muchos a muchos con miembros vía pivot 'asignaciones_rutinas'
    // Ajustado a las columnas que mostraste en el seeder: id_rutina, id_miembro
  public function miembros()
{
    return $this->belongsToMany(
        \App\Models\Miembro::class,
        'asignaciones_rutinas', // nombre de la tabla pivote
        'rutina_id',             // FK de Rutina en el pivote
        'miembro_id'             // FK de Miembro en el pivote
    )->withPivot('fecha_asignacion')
     ->withTimestamps();
}

    // Acceso directo a las asignaciones (pivot como modelo propio)
    public function asignaciones()
    {
        return $this->hasMany(AsignacionRutina::class, 'rutina_id');
    }

    /* ==========================
       SCOPES
       ========================== */

    public function scopeVirtual($query)
    {
        return $query->where('tipo', 'Virtual'); // coincide con tu enum
    }

    public function scopePresencial($query)
    {
        return $query->where('tipo', 'Presencial');
    }
}

