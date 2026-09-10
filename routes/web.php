<?php

use App\Http\Controllers\BillingWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResumePdfController;
use App\Livewire\Billing\Plans as BillingPlans;
use App\Livewire\CoverLetter\Generator as CoverLetterGenerator;
use App\Livewire\Job\Matcher as JobMatcher;
use App\Livewire\Resume\Analyzer as ResumeAnalyzer;
use App\Livewire\Resume\Builder as ResumeBuilder;
use App\Livewire\Resume\Customizer as ResumeCustomizer;
use App\Livewire\Resume\Importer as ResumeImporter;
use App\Livewire\Resume\TemplateSelector;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('politica-de-privacidade', 'legal.privacy')->name('legal.privacy');
Route::view('termos-de-uso', 'legal.terms')->name('legal.terms');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('curriculo/importar', ResumeImporter::class)->name('resume.import');
    Route::get('curriculo/adaptar/{jobDescription}', ResumeCustomizer::class)->name('resume.customize');
    Route::get('curriculo/{resume}/template', TemplateSelector::class)->name('resume.templates');
    Route::get('curriculo/{resume}/pdf', ResumePdfController::class)->name('resume.pdf.download');
    Route::get('curriculo/{resume?}', ResumeBuilder::class)->name('resume.builder');
    Route::get('analisar-curriculo/{resume?}', ResumeAnalyzer::class)->name('resume.analyze');
    Route::get('analisar-vaga', JobMatcher::class)->name('job.analyze');
    Route::get('carta-apresentacao/{jobDescription}', CoverLetterGenerator::class)->name('resume.cover-letter');

    Route::get('planos', BillingPlans::class)->name('billing.plans');
    Route::view('assinatura/sucesso', 'billing.success')->name('billing.success');
});

Route::post('billing/webhook/{gateway}', BillingWebhookController::class)->name('billing.webhook');

require __DIR__.'/auth.php';
