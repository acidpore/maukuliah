<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Mockery;
use Tests\Support\StubsViews;
use Tests\TestCase;

class GoogleAuthRoutesTest extends TestCase
{
    use RefreshDatabase;
    use StubsViews;

    private function enableGoogle(): void
    {
        config([
            'services.google.client_id' => 'id-uji',
            'services.google.client_secret' => 'rahasia-uji',
        ]);
    }

    private function googleReturns(string $id, string $email, string $name): void
    {
        $googleUser = (new GoogleUser)->map(['id' => $id, 'email' => $email, 'name' => $name]);

        $provider = Mockery::mock();
        $provider->shouldReceive('user')->andReturn($googleUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_routes_are_not_found_when_google_is_not_configured(): void
    {
        config(['services.google.client_id' => null, 'services.google.client_secret' => null]);

        $this->get('/auth/google')->assertNotFound();
        $this->get('/auth/google/callback')->assertNotFound();
    }

    public function test_auth_pages_tell_whether_google_is_enabled(): void
    {
        $this->stubViews(['auth.login', 'auth.register']);

        $this->get('/login')->assertViewHas('googleEnabled', false);

        $this->enableGoogle();

        $this->get('/login')->assertViewHas('googleEnabled', true);
        $this->get('/register')->assertViewHas('googleEnabled', true);
    }

    public function test_redirect_goes_to_google(): void
    {
        $this->enableGoogle();

        $this->get('/auth/google')
            ->assertRedirectContains('accounts.google.com');
    }

    public function test_callback_creates_new_student_and_logs_in(): void
    {
        $this->enableGoogle();
        $this->googleReturns('g-123', 'baru@example.test', 'Budi Baru');

        $this->get('/auth/google/callback')->assertRedirect(route('home'));

        $user = User::where('email', 'baru@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('g-123', $user->google_id);
        $this->assertSame('student', $user->role->value);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_callback_links_existing_account_by_email(): void
    {
        $this->enableGoogle();
        $existing = User::factory()->create(['email' => 'lama@example.test']);
        $this->googleReturns('g-456', 'lama@example.test', 'Nama Lain');

        $this->get('/auth/google/callback')->assertRedirect(route('home'));

        $this->assertSame(1, User::count());
        $this->assertSame('g-456', $existing->fresh()->google_id);
        $this->assertAuthenticatedAs($existing);
    }

    public function test_callback_failure_returns_to_login_with_error(): void
    {
        $this->enableGoogle();
        Socialite::shouldReceive('driver->user')->andThrow(new \RuntimeException('ditolak'));

        $this->get('/auth/google/callback')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
