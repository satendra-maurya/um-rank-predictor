<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CutoffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'exam_stage_id' => 'required|exists:exam_stages,id',
            'category_id' => 'required|exists:categories,id',
            'year' => 'required|integer|min:2000|max:2100',
            'cutoff_marks' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
