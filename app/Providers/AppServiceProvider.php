<?php

namespace App\Providers;

use App\View\Composers\NavigationComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const APPLICATION_ATTEMPTS_PER_MINUTE = 5;

    public const LOGIN_ATTEMPTS_PER_MINUTE = 5;

    public const REGISTER_ATTEMPTS_PER_MINUTE = 10;

    public const PASSWORD_RESET_ATTEMPTS_PER_MINUTE = 5;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('applications', fn (Request $request) => Limit::perMinute(
            self::APPLICATION_ATTEMPTS_PER_MINUTE,
        )->by($request->ip()));

        // Kunci memuat email agar satu akun tidak bisa ditebak dari banyak IP sekaligus.
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(
            self::LOGIN_ATTEMPTS_PER_MINUTE,
        )->by(strtolower((string) $request->input('email')).'|'.$request->ip()));

        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(
            self::REGISTER_ATTEMPTS_PER_MINUTE,
        )->by($request->ip()));

        RateLimiter::for('password-reset', fn (Request $request) => Limit::perMinute(
            self::PASSWORD_RESET_ATTEMPTS_PER_MINUTE,
        )->by($request->ip()));

        View::composer('layouts.app', NavigationComposer::class);
    }
}
