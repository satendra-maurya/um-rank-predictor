<?php

namespace App\Models;

use Database\Factories\PredictionResultFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PredictionResult extends Model
{
    /** @use HasFactory<PredictionResultFactory> */
    use HasFactory;

    protected $fillable = [
        'candidate_submission_id',
        'predicted_rank_overall',
        'predicted_rank_category',
        'percentile',
        'confidence_score',
        'metadata',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'predicted_rank_overall' => 'integer',
            'predicted_rank_category' => 'integer',
            'percentile' => 'decimal:2',
            'confidence_score' => 'decimal:2',
            'metadata' => 'array',
            'calculated_at' => 'datetime',
        ];
    }

    public function candidateSubmission(): BelongsTo
    {
        return $this->belongsTo(CandidateSubmission::class);
    }
}
