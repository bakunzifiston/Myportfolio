<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Validator;

class ContactRequest extends FormRequest
{
    /** Submissions faster than this (seconds after the form was rendered) are treated as automated. */
    public const MIN_SECONDS = 3;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => trim((string) $this->input('email')),
            'subject' => trim((string) $this->input('subject')),
            'message' => trim((string) $this->input('message')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:190'],
            'subject' => ['required', 'string', 'min:3', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            // Honeypot: hidden from people, often filled in by bots.
            'website' => ['nullable', 'max:0'],
            'form_token' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.max' => 'Your message could not be sent. Please try again.',
            'form_token.required' => 'Your session has expired. Please reload the page and try again.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->has('form_token')) {
                    return;
                }

                try {
                    $renderedAt = (int) Crypt::decryptString((string) $this->input('form_token'));
                } catch (DecryptException) {
                    $validator->errors()->add('form_token', 'Your session has expired. Please reload the page and try again.');

                    return;
                }

                if (now()->timestamp - $renderedAt < self::MIN_SECONDS) {
                    $validator->errors()->add('message', 'That was very quick. Please take a moment and submit again.');
                }
            },
        ];
    }

    public static function freshToken(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }
}
