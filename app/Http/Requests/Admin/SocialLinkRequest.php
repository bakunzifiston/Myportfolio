<?php

namespace App\Http\Requests\Admin;

use App\Concerns\NormalizesListInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialLinkRequest extends FormRequest
{
    use NormalizesListInput;

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeBooleans(['is_visible']);
        $this->merge(['platform' => strtolower(trim((string) $this->input('platform')))]);
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'alpha_dash', 'max:50', Rule::unique('social_links', 'platform')->ignore($this->route('social_link'))],
            'label' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url:http,https', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50', 'regex:/^[a-z0-9\-]+$/'],
            'is_visible' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ];
    }
}
