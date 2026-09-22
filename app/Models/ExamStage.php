<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamStage extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_cycle_id',
        'name',
        'slug',
        'stage_order',
        'type',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'stage_order' => 'integer',
            'status' => ActiveStatus::class,
        ];
    }

    public function examCycle(): BelongsTo
    {
        return $this->belongsTo(ExamCycle::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function cutoffs(): HasMany
    {
        return $this->hasMany(Cutoff::class);
    }

    public function predictionModels(): HasMany
    {
        return $this->hasMany(PredictionModel::class);
    }

    public function candidateSubmissions(): HasMany
    {
        return $this->hasMany(CandidateSubmission::class);
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
