<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Services\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_student_with_hashed_password(): void
    {
        $user = (new RegistrationService)->register([
            'name' => 'Siswa Contoh',
            'email' => 'siswa@example.test',
            'phone' => '081234567890',
            'password' => 'rahasia-panjang',
        ]);

        $this->assertSame(UserRole::Student, $user->role);
        $this->assertSame('081234567890', $user->phone);
        $this->assertTrue(Hash::check('rahasia-panjang', $user->password));
    }

    public function test_register_allows_missing_phone(): void
    {
        $user = (new RegistrationService)->register([
            'name' => 'Tanpa Telepon',
            'email' => 'tanpa@example.test',
            'password' => 'rahasia-panjang',
        ]);

        $this->assertNull($user->phone);
    }
}
