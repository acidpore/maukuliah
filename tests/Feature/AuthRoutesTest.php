<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\Support\StubsViews;
use Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    use RefreshDatabase;
    use StubsViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubViews(['auth.login', 'auth.register', 'auth.forgot-password', 'auth.reset-password']);
    }

    public function test_auth_pages_render_for_guests(): void
    {
        $this->get('/login')->assertOk()->assertViewIs('auth.login');
        $this->get('/register')->assertOk()->assertViewIs('auth.register');
        $this->get('/forgot-password')->assertOk()->assertViewIs('auth.forgot-password');
        $this->get('/reset-password/abc?email=a@example.test')
            ->assertOk()
            ->assertViewIs('auth.reset-password')
            ->assertViewHas('token', 'abc')
            ->assertViewHas('email', 'a@example.test');
    }

    public function test_register_creates_student_and_logs_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.test',
            'phone' => '081234567890',
            'password' => 'rahasia-banget',
            'password_confirmation' => 'rahasia-banget',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'siti@example.test', 'role' => 'student']);
    }

    public function test_register_validates_input(): void
    {
        User::factory()->create(['email' => 'dipakai@example.test']);

        $this->post('/register', [
            'name' => '',
            'email' => 'dipakai@example.test',
            'password' => 'pendek',
            'password_confirmation' => 'beda',
        ])->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
    }

    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'rahasia-banget']);

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia-banget'])
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $user = User::factory()->create(['password' => 'rahasia-banget']);

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_throttled(): void
    {
        $attempts = AppServiceProvider::LOGIN_ATTEMPTS_PER_MINUTE;

        foreach (range(1, $attempts) as $ignored) {
            $this->post('/login', ['email' => 'x@example.test', 'password' => 'salah']);
        }

        $this->post('/login', ['email' => 'x@example.test', 'password' => 'salah'])
            ->assertStatus(429);
    }

    public function test_authenticated_user_cannot_open_guest_pages(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/login')
            ->assertRedirect();
    }

    public function test_logout_ends_session(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_logout_requires_login(): void
    {
        $this->post('/logout')->assertRedirect(route('login'));
    }

    public function test_forgot_password_sends_notification_and_hides_unknown_emails(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHas('status');
        $knownStatus = session('status');

        $this->post('/forgot-password', ['email' => 'tidak-ada@example.test'])->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
        Notification::assertCount(1);
        $this->assertSame($knownStatus, session('status'));
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create(['password' => 'lama-banget-sandi']);
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'baru-banget-sandi',
            'password_confirmation' => 'baru-banget-sandi',
        ])->assertRedirect(route('login'));

        $this->post('/login', ['email' => $user->email, 'password' => 'baru-banget-sandi'])
            ->assertRedirect(route('home'));
    }

    public function test_password_reset_rejects_invalid_token(): void
    {
        $user = User::factory()->create();

        $this->post('/reset-password', [
            'token' => 'palsu',
            'email' => $user->email,
            'password' => 'baru-banget-sandi',
            'password_confirmation' => 'baru-banget-sandi',
        ])->assertSessionHasErrors('email');
    }
}
