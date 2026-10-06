<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array{email: string, password: string}
     */
    public function credentials(): array
    {
        return $this->only('email', 'password');
    }

    public function shouldRemember(): bool
    {
        return $this->boolean('remember');
    }
}
