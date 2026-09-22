<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_stage_id',
        'name',
        'shift_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'shift_date' => 'date',
            'status' => ActiveStatus::class,
        ];
    }

    public function examStage(): BelongsTo
    {
        return $this->belongsTo(ExamStage::class);
    }

    public function candidateSubmissions(): HasMany
    {
        return $this->hasMany(CandidateSubmission::class);
    }
}
