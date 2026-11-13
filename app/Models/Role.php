<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    // Role has many permissions

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions'
        );
    }

    // Role has many users

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Check whether role has permission

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('slug', $permission)
            ->exists();
    }
}
