<?php

namespace App\Http\Requests\Admin;

use App\Concerns\NormalizesListInput;
use App\Models\Experience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExperienceRequest extends FormRequest
{
    use NormalizesListInput;

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->linesToArrays(['responsibilities', 'contributions', 'technologies']);
        $this->normalizeBooleans(['is_current', 'is_published']);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(Experience::TYPES))],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'organization' => ['nullable', 'string', 'max:150', 'required_if:type,employment'],
            'position' => ['nullable', 'string', 'max:150', 'required_if_accepted:is_published'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:150'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date', 'prohibited_if_accepted:is_current'],
            'is_current' => ['boolean'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'responsibilities' => ['nullable', 'array', 'max:30'],
            'responsibilities.*' => ['string', 'max:400'],
            'contributions' => ['nullable', 'array', 'max:30'],
            'contributions.*' => ['string', 'max:400'],
            'technologies' => ['nullable', 'array', 'max:40'],
            'technologies.*' => ['string', 'max:60'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ];
    }

    public function messages(): array
    {
        return [
            'position.required_if_accepted' => 'Add the confirmed position or role before publishing this entry.',
            'end_date.prohibited_if_accepted' => 'Leave the end date empty for a current role.',
        ];
    }
}
