<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'exam_cycle_id' => 'required|exists:exam_cycles,id',
            'category_id' => 'required|exists:categories,id',
            'post_name' => 'nullable|string|max:255',
            'vacancy_count' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ];
    }
}
