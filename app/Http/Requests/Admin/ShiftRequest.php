<?php

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
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
            'shift_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }
}
