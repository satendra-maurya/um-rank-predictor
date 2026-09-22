<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NoticeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_date' => 'required|date',
            'published_at' => 'nullable|date',
            'link_url' => 'nullable|url',
            'status' => 'required|in:DRAFT,ACTIVE,INACTIVE',
        ];
    }
}
