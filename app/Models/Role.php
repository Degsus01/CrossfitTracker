<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    // Si tu tabla se llama 'roles', no hace falta $table

    protected $fillable = [
        'name',
        'slug',   // si en la migración agregaste este campo
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
