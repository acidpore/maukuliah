<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Campus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kata sandi demo dibaca dari env agar tidak tertanam di repositori.
        $password = env('SEED_USER_PASSWORD') ?: Str::random(24);

        User::updateOrCreate(
            ['email' => 'admin@maukuliah.test'],
            [
                'name' => 'Super Admin',
                'password' => $password,
                'role' => UserRole::SuperAdmin,
            ],
        );

        User::updateOrCreate(
            ['email' => 'admin.kampus@maukuliah.test'],
            [
                'name' => 'Admin Kampus Demo',
                'password' => $password,
                'role' => UserRole::CampusAdmin,
                'campus_id' => Campus::orderBy('id')->value('id'),
            ],
        );
    }
}
