<?php

namespace Tests\Support;

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\DTOs\AiUsageMeta;
use App\Domain\AI\DTOs\CoverLetterResult;
use App\Domain\AI\DTOs\JobAnalysisResult;
use App\Domain\AI\DTOs\JobMatchResult;
use App\Domain\AI\DTOs\ResumeAnalysisResult;
use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\DTOs\ResumeCustomizationResult;
use App\Domain\AI\DTOs\ResumeExtractionResult;
use App\Domain\AI\DTOs\ResumeImprovementResult;

class FakeAiProvider implements AiProviderInterface
{
    public ?ResumeImprovementResult $improvementResult = null;

    public ?ResumeExtractionResult $extractionResult = null;

    public function extractResume(string $rawText): ResumeExtractionResult
    {
        return $this->extractionResult ?? new ResumeExtractionResult(
            fullName: 'Rafael Souza',
            email: null,
            phone: null,
            city: null,
            state: null,
            linkedinUrl: null,
            githubUrl: null,
            portfolioUrl: null,
            professionalSummary: null,
            experiences: [],
            education: [],
            courses: [],
            skills: [],
            languages: [],
        );
    }

    public function analyzeResume(ResumeContextData $resume): ResumeAnalysisResult
    {
        return new ResumeAnalysisResult(80, 'Currículo sólido.', [], [], [], [
            'structure' => 80, 'clarity' => 80, 'experience' => 80, 'keywords' => 80, 'ats' => 80,
        ]);
    }

    public function improveResume(ResumeContextData $resume): ResumeImprovementResult
    {
        return $this->improvementResult ?? new ResumeImprovementResult(
            improvedSummary: 'Resumo melhorado pela IA de teste.',
            experienceImprovements: [],
            missingInfoSuggestions: [],
        );
    }

    public function analyzeJob(string $jobDescription): JobAnalysisResult
    {
        return new JobAnalysisResult('Vaga de teste.', [], [], []);
    }

    public function matchResumeWithJob(ResumeContextData $resume, string $jobDescription): JobMatchResult
    {
        return new JobMatchResult(75, [], [], [], [], []);
    }

    public function customizeResume(ResumeContextData $resume, string $jobDescription): ResumeCustomizationResult
    {
        return new ResumeCustomizationResult(null, [], [], []);
    }

    public function generateCoverLetter(ResumeContextData $resume, string $jobDescription, string $company, ?string $tone = null): CoverLetterResult
    {
        return new CoverLetterResult('Carta de teste.');
    }

    public function lastUsage(): ?AiUsageMeta
    {
        return new AiUsageMeta('fake', 'fake-model', 100, 50);
    }
}
