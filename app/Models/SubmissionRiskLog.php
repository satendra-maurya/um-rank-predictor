<?php

namespace App\Models;

use Database\Factories\SubmissionRiskLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionRiskLog extends Model
{
    /** @use HasFactory<SubmissionRiskLogFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'candidate_submission_id',
        'risk_factor',
        'risk_weight',
        'details',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'risk_weight' => 'integer',
            'details' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function candidateSubmission(): BelongsTo
    {
        return $this->belongsTo(CandidateSubmission::class);
    }
}
