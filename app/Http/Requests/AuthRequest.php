<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge(['email' => strtolower(trim($this->email))]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'form_type' => ['required', Rule::in(['login', 'signup'])],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];

        if ($this->input('form_type') === 'signup') {
            $rules['username'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:30'];
            $rules['email'][] = Rule::unique('users', 'email');
            $rules['password'][] = 'min:8';
        }

        return $rules;
    }
}
