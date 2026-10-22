<?php

namespace App\Http\Requests\Admin;

use App\Concerns\NormalizesListInput;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    use NormalizesListInput;

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->linesToArrays(['technologies', 'key_features']);
        $this->normalizeBooleans(['is_featured', 'remove_thumbnail']);
        $this->merge(['slug' => Str::slug($this->input('slug') ?: $this->input('title'))]);
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:170', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($project)],
            'project_category_id' => ['nullable', 'integer', 'exists:project_categories,id'],
            'short_description' => ['nullable', 'string', 'max:300'],
            'overview' => ['nullable', 'string', 'max:10000'],
            'role' => ['nullable', 'string', 'max:150'],
            'contribution' => ['nullable', 'string', 'max:10000'],
            'technologies' => ['nullable', 'array', 'max:40'],
            'technologies.*' => ['string', 'max:60'],
            'key_features' => ['nullable', 'array', 'max:40'],
            'key_features.*' => ['string', 'max:300'],
            'challenges' => ['nullable', 'string', 'max:10000'],
            'solutions' => ['nullable', 'string', 'max:10000'],
            'thumbnail_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:max_width=4000,max_height=4000'],
            'remove_thumbnail' => ['boolean'],
            'thumbnail_alt' => ['nullable', 'string', 'max:255'],
            'screenshot_uploads' => ['nullable', 'array', 'max:8'],
            'screenshot_uploads.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_screenshots' => ['nullable', 'array'],
            'remove_screenshots.*' => ['string'],
            'live_url' => ['nullable', 'url:http,https', 'max:255'],
            'repository_url' => ['nullable', 'url:http,https', 'max:255'],
            'is_featured' => ['boolean'],
            'status' => ['required', Rule::in(array_keys(Project::STATUSES))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ];
    }
}
