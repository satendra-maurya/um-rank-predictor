<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CandidateSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'trust_status' => 'required|in:TRUSTED,FLAGGED,BLOCKED',
            'risk_score' => 'required|integer|min:0',
        ];
    }
}
