<?php

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportantLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'display_order' => 'required|integer|min:0',
            'is_open_in_new_tab' => 'required|boolean',
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }
}
