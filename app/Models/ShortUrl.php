<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'original_url',
        'short_code',
    ];

    // Short URL belongs to a company

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Short URL belongs to its creator

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
