<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Enums\ExamAuthorityLevel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAuthority extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'short_name',
        'slug',
        'level',
        'website_url',
        'logo',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'level' => ExamAuthorityLevel::class,
            'status' => ActiveStatus::class,
        ];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
