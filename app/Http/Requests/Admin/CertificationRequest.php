<?php

namespace App\Http\Requests\Admin;

use App\Concerns\NormalizesListInput;
use Illuminate\Foundation\Http\FormRequest;

class CertificationRequest extends FormRequest
{
    use NormalizesListInput;

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeBooleans(['is_published']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'issuer' => ['nullable', 'string', 'max:150'],
            'issued_on' => ['nullable', 'date'],
            'expires_on' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'credential_id' => ['nullable', 'string', 'max:120'],
            'credential_url' => ['nullable', 'url:http,https', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ];
    }
}
