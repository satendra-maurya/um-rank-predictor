<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PredictionResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'predicted_rank_overall' => 'nullable|integer|min:1',
            'predicted_rank_category' => 'nullable|integer|min:1',
            'percentile' => 'nullable|numeric|min:0|max:100',
            'confidence_score' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
