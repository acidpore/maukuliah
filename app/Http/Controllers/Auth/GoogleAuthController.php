<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function __construct(private readonly GoogleAuthService $google) {}

    /**
     * Tanpa kredensial dari env, fitur dianggap tidak ada. Tidak ada login palsu.
     */
    public function redirect(): RedirectResponse
    {
        abort_unless($this->google->isConfigured(), 404);

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        abort_unless($this->google->isConfigured(), 404);

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Masuk dengan Google gagal. Silakan coba lagi.']);
        }

        Auth::login($this->google->findOrCreate($googleUser), true);

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
