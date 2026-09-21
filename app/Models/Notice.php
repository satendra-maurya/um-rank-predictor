<?php

namespace App\Models;

use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    /** @use HasFactory<NoticeFactory> */
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'exam_cycle_id',
        'exam_stage_id',
        'title',
        'slug',
        'notice_type',
        'notice_date',
        'official_url',
        'attachment_url',
        'content',
        'is_important',
        'published_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'notice_date' => 'date',
            'is_important' => 'boolean',
            'published_at' => 'datetime',
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
