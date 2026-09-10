<?php

namespace App\Domain\AI\Contracts;

use App\Domain\AI\DTOs\AiUsageMeta;
use App\Domain\AI\DTOs\CoverLetterResult;
use App\Domain\AI\DTOs\JobAnalysisResult;
use App\Domain\AI\DTOs\JobMatchResult;
use App\Domain\AI\DTOs\ResumeAnalysisResult;
use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\DTOs\ResumeCustomizationResult;
use App\Domain\AI\DTOs\ResumeImprovementResult;

/**
 * Abstração para qualquer provedor de IA (OpenAI, Gemini, Groq, ...). O
 * restante do sistema nunca deve depender de uma implementação concreta.
 */
interface AiProviderInterface
{
    public function analyzeResume(ResumeContextData $resume): ResumeAnalysisResult;

    public function improveResume(ResumeContextData $resume): ResumeImprovementResult;

    public function analyzeJob(string $jobDescription): JobAnalysisResult;

    public function matchResumeWithJob(ResumeContextData $resume, string $jobDescription): JobMatchResult;

    public function customizeResume(ResumeContextData $resume, string $jobDescription): ResumeCustomizationResult;

    public function generateCoverLetter(ResumeContextData $resume, string $jobDescription, string $company, ?string $tone = null): CoverLetterResult;

    /**
     * Metadados (modelo, tokens) da última chamada feita a este provedor.
     * Usado pelo AiUsageService para registrar custo e consumo.
     */
    public function lastUsage(): ?AiUsageMeta;
}
