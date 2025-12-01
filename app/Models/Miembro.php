<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Miembro extends Model
{
    use HasFactory;

    protected $table = 'miembros';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'fecha_nacimiento',
        'membresia_id',
        'notas',
        'membresia_expira_at',   // 🔥 NUEVO — así se puede asignar desde el servicio
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'membresia_expira_at' => 'date',  // 🔥 Para manejar fechas automáticamente
    ];

    /* ==========================
       RELACIONES
       ========================== */

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'membresia_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'miembro_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'miembro_id');
    }

    public function progresos()
    {
        return $this->hasMany(Progreso::class, 'miembro_id');
    }

    public function rutinas()
    {
        return $this->belongsToMany(
            Rutina::class,
            'asignaciones_rutinas',
            'miembro_id',
            'rutina_id'
        )->withPivot('fecha_asignacion')
         ->withTimestamps();
    }

    /* ==========================
       🔥 MÉTODOS PROFESIONALES
       PARA MEMBRESÍAS Y REPORTES
       ========================== */

    /**
     * Estado actual de la membresía:
     * - activa
     * - por_vencer (5 días o menos)
     * - vencida
     * - sin_membresia
     */
    public function getMembresiaEstadoAttribute(): string
    {
        if (!$this->membresia_expira_at) {
            return 'sin_membresia';
        }

        $hoy = Carbon::today();
        $vence = Carbon::parse($this->membresia_expira_at);

        if ($vence->isBefore($hoy)) {
            return 'vencida';
        }

        if ($vence->diffInDays($hoy) <= 5) {
            return 'por_vencer';
        }

        return 'activa';
    }

    /**
     * Días que faltan para vencerse.
     */
    public function getMembresiaDiasRestantesAttribute(): ?int
    {
        if (!$this->membresia_expira_at) {
            return null;
        }

        $hoy   = Carbon::today();
        $vence = Carbon::parse($this->membresia_expira_at);

        if ($vence->isBefore($hoy)) {
            return 0;
        }

        return $hoy->diffInDays($vence);
    }

    /**
     * 🔍 Scope: miembros que vencen pronto (por defecto 5 días).
     */
    public function scopeExpiranPronto($query, int $dias = 5)
    {
        $hoy = Carbon::today();
        $limite = $hoy->clone()->addDays($dias);

        return $query
            ->whereNotNull('membresia_expira_at')
            ->whereDate('membresia_expira_at', '>=', $hoy)
            ->whereDate('membresia_expira_at', '<=', $limite);
    }

    /**
     * 🔍 Scope: miembros con membresía YA vencida
     */
    public function scopeVencidos($query)
    {
        return $query->whereNotNull('membresia_expira_at')
            ->whereDate('membresia_expira_at', '<', Carbon::today());
    }
}
