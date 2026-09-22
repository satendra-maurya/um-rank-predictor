<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_authority_id',
        'name',
        'short_name',
        'slug',
        'description',
        'exam_type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
        ];
    }

    public function examAuthority(): BelongsTo
    {
        return $this->belongsTo(ExamAuthority::class);
    }

    public function examCycles(): HasMany
    {
        return $this->hasMany(ExamCycle::class);
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function importantLinks(): HasMany
    {
        return $this->hasMany(ImportantLink::class);
    }
}
