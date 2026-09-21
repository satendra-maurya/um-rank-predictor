<?php

namespace App\Models;

use App\Enums\SubmissionTrustStatus;
use Database\Factories\CandidateSubmissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CandidateSubmission extends Model
{
    /** @use HasFactory<CandidateSubmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'prediction_model_id',
        'exam_stage_id',
        'shift_id',
        'category_id',
        'user_id',
        'candidate_identifier',
        'total_attempted',
        'correct_answers',
        'incorrect_answers',
        'raw_score',
        'normalized_score',
        'session_token',
        'ip_hash',
        'user_agent_hash',
        'device_fingerprint',
        'risk_score',
        'trust_status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'total_attempted' => 'integer',
            'correct_answers' => 'integer',
            'incorrect_answers' => 'integer',
            'raw_score' => 'decimal:2',
            'normalized_score' => 'decimal:2',
            'risk_score' => 'integer',
            'trust_status' => SubmissionTrustStatus::class,
            'submitted_at' => 'datetime',
        ];
    }

    public function predictionModel(): BelongsTo
    {
        return $this->belongsTo(PredictionModel::class);
    }

    public function examStage(): BelongsTo
    {
        return $this->belongsTo(ExamStage::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function predictionResult(): HasOne
    {
        return $this->hasOne(PredictionResult::class);
    }

    public function riskLogs(): HasMany
    {
        return $this->hasMany(SubmissionRiskLog::class);
    }
}
