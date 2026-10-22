<?php

namespace App\Http\Requests\Admin;

use App\Concerns\NormalizesListInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SkillCategoryRequest extends FormRequest
{
    use NormalizesListInput;

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => Str::slug($this->input('slug') ?: $this->input('name'))]);
        $this->linesToArrays(['skills']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('skill_categories', 'slug')->ignore($this->route('skill_category'))],
            'icon' => ['nullable', 'string', 'max:50', 'regex:/^[a-z0-9\-]+$/'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
            'skills' => ['nullable', 'array', 'max:60'],
            'skills.*' => ['string', 'max:100', 'distinct:ignore_case'],
        ];
    }

    public function messages(): array
    {
        return ['icon.regex' => 'Use a Lucide icon name such as "database" or "code-xml".'];
    }
}
