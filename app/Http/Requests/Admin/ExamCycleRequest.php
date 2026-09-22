<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExamCycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'exam_id' => 'required|exists:exams,id',
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'notification_date' => 'nullable|date',
            'application_start_date' => 'nullable|date',
            'application_end_date' => 'nullable|date|after_or_equal:application_start_date',
            'exam_start_date' => 'nullable|date',
            'exam_end_date' => 'nullable|date|after_or_equal:exam_start_date',
            'result_date' => 'nullable|date',
            'status' => 'required|in:UPCOMING,ONGOING,COMPLETED,ARCHIVED',
        ];
    }
}
