<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'invited_by',
        'email',
        'role_id',
        'token',
        'status',
        'expires_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    // Invitation belongs to a company

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // User who sent invitation

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Role assigned to invited user

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
