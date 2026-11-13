<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    // Company has many users

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Company has many short URLs

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    // Company has many invitations

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
