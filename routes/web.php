<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Resume\Builder as ResumeBuilder;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('curriculo/{resume?}', ResumeBuilder::class)->name('resume.builder');
});

require __DIR__.'/auth.php';
