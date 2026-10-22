<?php

namespace App\Http\Requests\Admin;

use App\Support\Settings;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $rules = [];

        foreach (Settings::definitions() as $key => $def) {
            $rules[$key] = match ($def['type']) {
                'email' => ['nullable', 'email:rfc', 'max:190'],
                'url' => ['nullable', 'url:http,https', 'max:255'],
                'tel' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+\-\s().]+$/'],
                'textarea' => ['nullable', 'string', 'max:10000'],
                'file' => $def['accept'] === 'application/pdf'
                    ? ['nullable', 'file', 'mimes:pdf', 'max:5120']
                    : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                default => ['nullable', 'string', 'max:255'],
            };

            if ($def['type'] === 'file') {
                $rules['remove_'.$key] = ['nullable', 'boolean'];
            }
        }

        return $rules;
    }
}
