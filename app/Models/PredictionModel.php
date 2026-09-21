<?php

namespace App\Models;

use Database\Factories\PredictionModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PredictionModel extends Model
{
    /** @use HasFactory<PredictionModelFactory> */
    use HasFactory;

    protected $fillable = [
        'exam_stage_id',
        'name',
        'version',
        'total_marks',
        'negative_marking_ratio',
        'formula_config',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_marks' => 'decimal:2',
            'negative_marking_ratio' => 'decimal:2',
            'formula_config' => 'array',
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
