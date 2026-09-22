<?php

namespace App\Models;

use App\Enums\ExamCycleStatus;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamCycle extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'year',
        'title',
        'notification_date',
        'application_start_date',
        'application_end_date',
        'exam_start_date',
        'exam_end_date',
        'result_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'notification_date' => 'date',
            'application_start_date' => 'date',
            'application_end_date' => 'date',
            'exam_start_date' => 'date',
            'exam_end_date' => 'date',
            'result_date' => 'date',
            'status' => ExamCycleStatus::class,
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function examStages(): HasMany
    {
        return $this->hasMany(ExamStage::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
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
