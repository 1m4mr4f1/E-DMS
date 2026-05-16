<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:50'],
            'label' => ['required', 'in:draft,fix'],
            'visibility' => ['required', 'in:private,public'],
            'file' => ['nullable', 'file', 'mimes:pdf,docx,xlsx,pptx,png,jpg,jpeg', 'max:102400'],
        ];
    }
}
