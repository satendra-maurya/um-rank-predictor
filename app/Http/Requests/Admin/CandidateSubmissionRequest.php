<?php

namespace App\Http\Requests\Admin;

use App\Enums\SubmissionTrustStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'trust_status' => ['required', Rule::enum(SubmissionTrustStatus::class)],
            'risk_score' => 'required|integer|min:0',
        ];
    }
}
