<?php

namespace App\Domain\AI\Enums;

enum AiOperation: string
{
    case ResumeAnalysis = 'resume_analysis';
    case ResumeImprovement = 'resume_improvement';
    case ResumeExtraction = 'resume_extraction';
    case JobAnalysis = 'job_analysis';
    case ResumeMatching = 'resume_matching';
    case ResumeCustomization = 'resume_customization';
    case CoverLetter = 'cover_letter';

    public function creditCost(): int
    {
        return (int) config('ai.credit_cost.'.$this->value, 1);
    }

    public function label(): string
    {
        return match ($this) {
            self::ResumeAnalysis => 'Análise de currículo',
            self::ResumeImprovement => 'Melhoria de currículo',
            self::ResumeExtraction => 'Extração de currículo importado',
            self::JobAnalysis => 'Análise de vaga',
            self::ResumeMatching => 'Compatibilidade com vaga',
            self::ResumeCustomization => 'Personalização para vaga',
            self::CoverLetter => 'Carta de apresentação',
        };
    }
}
