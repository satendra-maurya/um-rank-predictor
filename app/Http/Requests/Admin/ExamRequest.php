<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'exam_authority_id' => 'required|exists:exam_authorities,id',
            'description' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ];
    }
}
