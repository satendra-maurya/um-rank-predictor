<?php

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamAuthorityRequest extends FormRequest
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
            'code' => 'required|string|max:20',
            'level' => 'required|in:NATIONAL,STATE',
            'state_id' => 'nullable|required_if:level,STATE|exists:states,id',
            'website_url' => 'nullable|url',
            'description' => 'nullable|string',
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }
}
