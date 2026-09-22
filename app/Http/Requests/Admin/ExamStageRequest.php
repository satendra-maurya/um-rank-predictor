<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExamStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'exam_cycle_id' => 'required|exists:exam_cycles,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'stage_order' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'negative_marking_ratio' => 'nullable|numeric|min:0|max:1',
            'description' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ];
    }
}
