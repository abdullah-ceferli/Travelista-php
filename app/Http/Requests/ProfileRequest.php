<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'between:1,120'],
            'location' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'about_me' => ['nullable', 'string', 'max:2000'],
            'profile_img' => [$this->user()->profile_img ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ];
    }
}
