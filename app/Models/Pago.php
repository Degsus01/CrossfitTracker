<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    // La tabla se llama 'pagos' (Laravel ya lo deduce, pero lo dejamos explícito)
    protected $table = 'pagos';

    // PK por defecto es 'id' → no hace falta definir $primaryKey
    // protected $primaryKey = 'id';

    // La tabla SÍ tiene timestamps (created_at, updated_at)
    // así que dejamos el valor por defecto (true) y no definimos $timestamps = false
    // public $timestamps = true;

    // Campos rellenables según tu esquema real
    protected $fillable = ['miembro_id','fecha','monto','metodo','referencia','notas'];
protected $casts = ['fecha' => 'date'];

    // Relaciones
    public function miembro()
    {
        // La FK real es 'miembro_id'
        return $this->belongsTo(Miembro::class, 'miembro_id');
    }
}
