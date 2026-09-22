<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PredictionModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'exam_stage_id' => 'required|exists:exam_stages,id',
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'algorithm_type' => 'required|string|max:100',
            'config_json' => 'nullable|json',
            'is_active' => 'required|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
