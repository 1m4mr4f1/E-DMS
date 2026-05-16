<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            // PERBAIKAN: Ditambahkan 'nullable' agar kotak tag yang kosong tidak memicu error
            'tags.*' => ['nullable', 'string', 'max:50'],
            'label' => ['required', 'in:draft,fix'],
            'visibility' => ['required', 'in:private,public'],
            'file' => ['required', 'file', 'mimes:pdf,docx,xlsx,pptx,png,jpg,jpeg', 'max:102400'],
        ];
    }
}