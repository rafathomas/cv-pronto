<?php

namespace App\Http\Controllers;

use App\Domain\AI\Services\AiCreditService;
use App\Domain\CoverLetter\Models\CoverLetter;
use App\Domain\JobMatch\Models\JobMatch;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\ResumeAnalysis\Models\ResumeAnalysis;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, AiCreditService $credits): View
    {
        $user = $request->user();
        $resume = $user->resumes()->latest()->first();
        $latestAnalysis = $resume?->latestAnalysis;

        $activity = collect()
            ->concat(ResumeAnalysis::where('user_id', $user->id)->latest('created_at')->limit(5)->get()->map(fn ($a) => [
                'icon' => 'analysis',
                'label' => "Análise de currículo concluída — nota {$a->score}",
                'at' => $a->created_at,
            ]))
            ->concat(JobMatch::where('user_id', $user->id)->latest('created_at')->limit(5)->get()->map(fn ($m) => [
                'icon' => 'match',
                'label' => "Comparação com vaga — {$m->match_score}% de compatibilidade",
                'at' => $m->created_at,
            ]))
            ->concat(CustomizedResume::where('user_id', $user->id)->latest('created_at')->limit(5)->get()->map(fn ($c) => [
                'icon' => 'customize',
                'label' => 'Currículo adaptado para uma vaga',
                'at' => $c->created_at,
            ]))
            ->concat(CoverLetter::where('user_id', $user->id)->latest('created_at')->limit(5)->get()->map(fn ($l) => [
                'icon' => 'letter',
                'label' => 'Carta de apresentação gerada',
                'at' => $l->created_at,
            ]))
            ->sortByDesc('at')
            ->take(5)
            ->values();

        return view('dashboard', [
            'resume' => $resume,
            'latestAnalysis' => $latestAnalysis,
            'creditsBalance' => $credits->balance($user),
            'activity' => $activity,
        ]);
    }
}
