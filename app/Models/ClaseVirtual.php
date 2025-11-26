<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaseVirtual extends Model
{
    use HasFactory;

    protected $table = 'clases_virtuales';
    protected $primaryKey = 'id_clase'; // sigues usando tu PK personalizada

    protected $fillable = [
        'titulo',
        'descripcion',
        'enlace',         // antes enlace_video
        'plataforma',     // antes tipo_recurso
        'fecha',          // antes fecha_publicacion
        'hora',
        'duracion_min',
        'entrenador_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'entrenador_id');
    }

    public function rutinas()
    {
        // Si la FK en rutinas apunta a id_clase:
        return $this->hasMany(Rutina::class, 'clase_virtual_id', 'id_clase');
    }
}
