<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Pesan selalu sama agar penyerang tidak bisa menebak email yang terdaftar.
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        Password::sendResetLink($request->only('email'));

        return back()->with(
            'status',
            'Bila email terdaftar, tautan untuk mengatur ulang kata sandi sudah dikirim.',
        );
    }
}
