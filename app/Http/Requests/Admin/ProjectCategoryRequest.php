<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => Str::slug($this->input('slug') ?: $this->input('name'))]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('project_categories', 'slug')->ignore($this->route('project_category'))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ];
    }
}
