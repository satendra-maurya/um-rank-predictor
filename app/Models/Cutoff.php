<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cutoff extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_stage_id',
        'category_id',
        'post_name',
        'cutoff_marks',
        'cutoff_rank',
    ];

    protected function casts(): array
    {
        return [
            'cutoff_marks' => 'decimal:2',
            'cutoff_rank' => 'integer',
        ];
    }

    public function examStage(): BelongsTo
    {
        return $this->belongsTo(ExamStage::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
