<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'exam_cycle_id',
        'category_id',
        'post_name',
        'total_vacancies',
    ];

    protected function casts(): array
    {
        return [
            'total_vacancies' => 'integer',
        ];
    }

    public function examCycle(): BelongsTo
    {
        return $this->belongsTo(ExamCycle::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
