<?php

use App\Http\Controllers\CampusController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/universities', [CampusController::class, 'index'])->name('campuses.index');
Route::get('/universities/{campus}', [CampusController::class, 'show'])->name('campuses.show');
Route::get('/majors', [MajorController::class, 'index'])->name('majors.index');
Route::get('/majors/{major}', [MajorController::class, 'show'])->name('majors.show');
Route::get('/search', SearchController::class)->name('search');

Route::get('/soon', fn () => view('soon'))->name('soon');
