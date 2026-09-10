<?php

namespace App\Http\Controllers;

use App\Domain\Analytics\Services\AnalyticsService;
use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\Resume\Models\Resume;
use App\Domain\Resume\Services\ResumePdfService;
use App\Domain\Subscription\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ResumePdfController extends Controller
{
    public function __invoke(Request $request, Resume $resume, ResumePdfService $service, SubscriptionService $subscriptions, AnalyticsService $analytics): Response
    {
        Gate::authorize('view', $resume);

        $template = ResumeTemplate::tryFrom($request->query('template', $resume->template)) ?? ResumeTemplate::Classico;

        if ($template->isPremium() && ! $subscriptions->currentPlan($request->user())->hasFeature('premium_templates')) {
            $template = ResumeTemplate::Classico;
        }

        $customized = null;
        if ($request->filled('customized_resume_id')) {
            $customized = CustomizedResume::where('id', $request->query('customized_resume_id'))
                ->where('resume_id', $resume->id)
                ->where('user_id', $request->user()->id)
                ->first();
        }

        $pdf = $service->render($resume, $template, $customized);

        if (! $request->boolean('inline')) {
            $analytics->track('pdf_generated', $request->user(), ['template' => $template->value]);
        }

        return $request->boolean('inline')
            ? $pdf->stream($service->filename($resume))
            : $pdf->download($service->filename($resume));
    }
}
