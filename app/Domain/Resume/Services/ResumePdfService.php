<?php

namespace App\Domain\Resume\Services;

use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\Resume\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfInstance;

class ResumePdfService
{
    public function render(Resume $resume, ResumeTemplate $template, ?CustomizedResume $customized = null): PdfInstance
    {
        $resume->loadMissing(['experiences', 'education', 'courses', 'skills', 'languages']);

        return Pdf::loadView($template->view(), [
            'resume' => $resume,
            'accentColor' => $resume->accent_color ?: '#16A34A',
            'customizedSummary' => $customized?->summary,
            'customizedExperiences' => $customized?->experience_descriptions ?? [],
            'customizedSkills' => $customized?->highlighted_skills,
        ])->setPaper('a4');
    }

    public function filename(Resume $resume): string
    {
        $slug = str($resume->full_name ?: 'curriculo')->slug();

        return "curriculo-{$slug}.pdf";
    }
}
