<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// si quitaste Sanctum, comenta esta línea
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // si NO usas Sanctum:
    use HasFactory, Notifiable;

    // si sí usas Sanctum:
    // use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',   // <<--- IMPORTANTE
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
public function role()
{
    return $this->belongsTo(Role::class);
}

public function getRolAttribute()
{
    return $this->role->slug ?? null;
}
public function entrenador()
{
    return $this->hasOne(\App\Models\Entrenador::class, 'user_id');
}

    // ✅ Helpers para usar en middleware y blades
    public function roleSlug(): ?string
    {
        return optional($this->role)->slug;
    }

    public function hasRole(string $slug): bool
    {
        return $this->roleSlug() === $slug;
    }

    public function hasAnyRole(array $slugs): bool
    {
        return in_array($this->roleSlug(), $slugs, true);
    }
}
