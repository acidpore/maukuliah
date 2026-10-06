<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class GoogleAuthService
{
    public function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'));
    }

    /**
     * Akun dicari lewat google_id, lalu lewat email agar pengguna lama tidak
     * mendapat akun ganda. Email dari Google sudah terverifikasi oleh Google.
     */
    public function findOrCreate(SocialiteUser $googleUser): User
    {
        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if ($user === null) {
            return $this->createFrom($googleUser);
        }

        if ($user->google_id === null) {
            $user->forceFill(['google_id' => $googleUser->getId()])->save();
        }

        return $user;
    }

    private function createFrom(SocialiteUser $googleUser): User
    {
        $user = User::create([
            'name' => $googleUser->getName() ?: $googleUser->getEmail(),
            'email' => $googleUser->getEmail(),
            'password' => Str::random(config('services.google.generated_password_length')),
            'role' => UserRole::Student,
            'google_id' => $googleUser->getId(),
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }
}
