<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'company_id',
        'role_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // JWT Methods

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // User belongs to a company

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // User belongs to a role

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // User-created short URLs

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    // Invitations sent by this user

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    // Check whether user has a permission

    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('slug', $permission)
            ->exists();
    }

    // Check multiple permissions

    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->whereIn('slug', $permissions)
            ->exists();
    }
}
