<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    private const MIN_PASSWORD_LENGTH = 8;

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
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(self::MIN_PASSWORD_LENGTH)],
        ];
    }

    /**
     * @return array{email: string, password: string, password_confirmation: string, token: string}
     */
    public function resetData(): array
    {
        return $this->only('email', 'password', 'password_confirmation', 'token');
    }
}
