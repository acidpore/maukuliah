<?php

use App\Http\Controllers\Admin\CampusVerificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadStatusController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BrochureController;
use App\Http\Controllers\CampusCollectionController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/universities', [CampusController::class, 'index'])->name('campuses.index');
Route::get('/universities/{campus}', [CampusController::class, 'show'])->name('campuses.show');
Route::get('/majors', [MajorController::class, 'index'])->name('majors.index');
Route::get('/majors/{major}', [MajorController::class, 'show'])->name('majors.show');
Route::get('/jobs', [CareerController::class, 'index'])->name('careers.index');
Route::get('/jobs/{career}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
Route::get('/scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarships.show');
Route::get('/search', SearchController::class)->name('search');

Route::get('/jadwal-kuliah/{schedule}/{region?}', [CampusCollectionController::class, 'schedule'])
    ->name('campuses.by-schedule');
Route::get('/program-kuliah/{programType}', [CampusCollectionController::class, 'programType'])
    ->name('campuses.by-program');
Route::get('/metode-belajar/{method}/{region?}', [CampusCollectionController::class, 'method'])
    ->name('campuses.by-method');

Route::get('/daftar-kuliah', [ApplicationController::class, 'create'])->name('applications.create');
Route::post('/daftar-kuliah', [ApplicationController::class, 'store'])
    ->middleware('throttle:applications')
    ->name('applications.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:register');

    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->middleware('throttle:password-reset')
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])
        ->middleware('throttle:password-reset')
        ->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('test')->name('tests.')->group(function () {
    $testKeys = implode('|', array_keys(config('potential_tests.tests')));

    Route::get('/', [TestController::class, 'index'])->name('index');

    Route::middleware('auth')->group(function () {
        Route::get('/history', [TestController::class, 'history'])->name('history');
        Route::get('/resume', [TestController::class, 'resume'])->name('resume');
        Route::get('/results/{testResult}', [TestController::class, 'result'])->name('result');
    });

    Route::get('/{type}', [TestController::class, 'start'])->where('type', $testKeys)->name('start');
    Route::post('/{type}', [TestController::class, 'submit'])->where('type', $testKeys)->name('submit');
});

Route::redirect('/riasec', '/test/riasec');

Route::middleware('auth')->group(function () {
    Route::get('/favourites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/universities/{campus}/brochures/{brochure}', [BrochureController::class, 'download'])
        ->scopeBindings()
        ->name('brochures.download');

    Route::post('/affiliate/register', [AffiliateController::class, 'register'])->name('affiliate.register');
    Route::get('/affiliate/dashboard', [AffiliateController::class, 'dashboard'])->name('affiliate.dashboard');
});

Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:super_admin,campus_admin'])
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/leads/{lead}/status', [LeadStatusController::class, 'update'])->name('leads.status');

        Route::get('/campuses', [CampusVerificationController::class, 'index'])->name('campuses.index');
        Route::post('/campuses/{campus}/submit', [CampusVerificationController::class, 'submit'])->name('campuses.submit');
        Route::post('/campuses/{campus}/approve', [CampusVerificationController::class, 'approve'])->name('campuses.approve');
        Route::post('/campuses/{campus}/reject', [CampusVerificationController::class, 'reject'])->name('campuses.reject');
    });

Route::get('/soon', fn () => view('soon'))->name('soon');
