<?php

use App\Domain\Analytics\Services\AnalyticsService;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\BillingWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResumePdfController;
use App\Http\Controllers\Seo\SeoPageController;
use App\Livewire\Billing\Plans as BillingPlans;
use App\Livewire\CoverLetter\Generator as CoverLetterGenerator;
use App\Livewire\Job\Matcher as JobMatcher;
use App\Livewire\Resume\Analyzer as ResumeAnalyzer;
use App\Livewire\Resume\Builder as ResumeBuilder;
use App\Livewire\Resume\Customizer as ResumeCustomizer;
use App\Livewire\Resume\Importer as ResumeImporter;
use App\Livewire\Resume\TemplateSelector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request, AnalyticsService $analytics) {
    $analytics->track('landing_view', $request->user());

    return view('welcome');
});

Route::view('politica-de-privacidade', 'legal.privacy')->name('legal.privacy');
Route::view('termos-de-uso', 'legal.terms')->name('legal.terms');

Route::get('{slug}', [SeoPageController::class, 'show'])
    ->where('slug', 'criar-curriculo|curriculo-com-ia|curriculo-primeiro-emprego|curriculo-estagio|curriculo-programador|curriculo-administrativo|curriculo-vendedor')
    ->name('seo.page');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rotas com múltiplos segmentos fixos ou que precisam vir antes do
    // catch-all "curriculo/{resume?}" para não serem interpretadas como id.
    Route::middleware('throttle:ai')->group(function () {
        Route::get('curriculo/importar', ResumeImporter::class)->name('resume.import');
        Route::get('curriculo/adaptar/{jobDescription}', ResumeCustomizer::class)->name('resume.customize');
        Route::get('analisar-curriculo/{resume?}', ResumeAnalyzer::class)->name('resume.analyze');
        Route::get('analisar-vaga', JobMatcher::class)->name('job.analyze');
        Route::get('carta-apresentacao/{jobDescription}', CoverLetterGenerator::class)->name('resume.cover-letter');
    });

    Route::get('curriculo/{resume}/template', TemplateSelector::class)->name('resume.templates');
    Route::get('curriculo/{resume}/pdf', ResumePdfController::class)->name('resume.pdf.download');
    Route::get('curriculo/{resume?}', ResumeBuilder::class)->name('resume.builder');

    Route::get('planos', BillingPlans::class)->name('billing.plans');
    Route::view('assinatura/sucesso', 'billing.success')->name('billing.success');

    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('assinaturas', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::patch('assinaturas/{user}', [AdminSubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::delete('assinaturas/{user}', [AdminSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    });
});

Route::post('billing/webhook/{gateway}', BillingWebhookController::class)->name('billing.webhook');

require __DIR__.'/auth.php';
