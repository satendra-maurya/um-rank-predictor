<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportantLink extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'exam_cycle_id',
        'exam_stage_id',
        'title',
        'url',
        'link_type',
        'is_external',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_external' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function examCycle(): BelongsTo
    {
        return $this->belongsTo(ExamCycle::class);
    }

    public function examStage(): BelongsTo
    {
        return $this->belongsTo(ExamStage::class);
    }
}
