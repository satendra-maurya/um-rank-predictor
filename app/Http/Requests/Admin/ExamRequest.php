<?php

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }
}
