<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceReport extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'total_asistencias',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
