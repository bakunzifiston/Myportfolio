<?php

namespace App\Http\Requests\Admin;

use App\Concerns\NormalizesListInput;
use App\Models\Education;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EducationRequest extends FormRequest
{
    use NormalizesListInput;

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeBooleans(['is_published', 'confirm_awarded']);
    }

    public function rules(): array
    {
        return [
            'level' => ['required', Rule::in(array_keys(Education::LEVELS))],
            'field_of_study' => ['nullable', 'string', 'max:150'],
            'qualification_title' => ['nullable', 'string', 'max:150', 'required_if:status,awarded'],
            'institution' => ['nullable', 'string', 'max:200'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'start_year' => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'end_year' => ['nullable', 'integer', 'min:1950', 'max:2100', 'gte:start_year'],
            'status' => ['required', Rule::in(array_keys(Education::STATUSES))],
            // A deliberate extra confirmation before anything is shown publicly as an awarded qualification.
            'confirm_awarded' => ['boolean', Rule::when($this->input('status') === 'awarded', ['accepted'])],
            'description' => ['nullable', 'string', 'max:3000'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm_awarded.accepted' => 'Confirm that this qualification was completed and awarded.',
            'qualification_title.required_if' => 'Enter the official qualification title for an awarded qualification.',
        ];
    }
}
