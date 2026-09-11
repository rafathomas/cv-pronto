<?php

namespace App\Domain\Resume\Services;

use App\Domain\Resume\Enums\AccentColor;
use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\Resume\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class ResumePdfService
{
    private const CACHE_TTL_SECONDS = 86400;

    /**
     * Renderiza o PDF do currículo, em bytes. Cacheado por currículo/template/
     * versão adaptada — a chave inclui `updated_at` (que `$touches` propaga a
     * partir das relations do currículo) para invalidar automaticamente
     * quando qualquer dado do currículo muda.
     */
    public function render(Resume $resume, ResumeTemplate $template, ?CustomizedResume $customized = null): string
    {
        $key = $this->cacheKey($resume, $template, $customized);

        return Cache::remember($key, self::CACHE_TTL_SECONDS, function () use ($resume, $template, $customized) {
            $resume->loadMissing(['experiences', 'education', 'courses', 'skills', 'languages']);

            return Pdf::loadView($template->view(), [
                'resume' => $resume,
                'accentColor' => $resume->accent_color ?: AccentColor::DEFAULT,
                'customizedSummary' => $customized?->summary,
                'customizedExperiences' => $customized?->experience_descriptions ?? [],
                'customizedSkills' => $customized?->highlighted_skills,
            ])->setPaper('a4')->output();
        });
    }

    private function cacheKey(Resume $resume, ResumeTemplate $template, ?CustomizedResume $customized): string
    {
        return sprintf(
            'resume-pdf:%d:%s:%s:%s',
            $resume->id,
            $template->value,
            $resume->updated_at?->timestamp,
            $customized ? "{$customized->id}:{$customized->updated_at?->timestamp}" : 'none',
        );
    }

    public function filename(Resume $resume): string
    {
        $slug = str($resume->full_name ?: 'curriculo')->slug();

        return "curriculo-{$slug}.pdf";
    }
}
