<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'code',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
        ];
    }

    public function examAuthorities(): HasMany
    {
        return $this->hasMany(ExamAuthority::class);
    }
}
