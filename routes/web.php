<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Resume\Analyzer as ResumeAnalyzer;
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
    Route::get('analisar-curriculo/{resume?}', ResumeAnalyzer::class)->name('resume.analyze');
});

require __DIR__.'/auth.php';
