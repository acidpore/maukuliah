<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\GoogleAuthService;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registration,
        private readonly GoogleAuthService $google,
    ) {}

    public function create(): View
    {
        return view('auth.register', ['googleEnabled' => $this->google->isConfigured()]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        Auth::login($this->registration->register($request->validated()));

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
