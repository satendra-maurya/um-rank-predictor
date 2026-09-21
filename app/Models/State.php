<?php

namespace App\Models;

use Database\Factories\StateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    /** @use HasFactory<StateFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'code',
        'status',
    ];

    public function examAuthorities(): HasMany
    {
        return $this->hasMany(ExamAuthority::class);
    }
}
