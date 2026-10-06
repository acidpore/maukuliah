<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;

class RegistrationService
{
    /**
     * @param  array{name: string, email: string, password: string, phone?: ?string}  $data
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => UserRole::Student,
        ]);
    }
}
