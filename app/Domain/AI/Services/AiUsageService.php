<?php

namespace App\Domain\AI\Services;

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\DTOs\CoverLetterResult;
use App\Domain\AI\DTOs\JobAnalysisResult;
use App\Domain\AI\DTOs\JobMatchResult;
use App\Domain\AI\DTOs\ResumeAnalysisResult;
use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\DTOs\ResumeCustomizationResult;
use App\Domain\AI\DTOs\ResumeImprovementResult;
use App\Domain\AI\Enums\AiOperation;
use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\AI\Models\AiUsage;
use App\Models\User;
use Closure;

/**
 * Ponto único de passagem para qualquer chamada de IA: verifica créditos,
 * executa a operação, registra uso/custo e desconta o crédito. Nenhum
 * outro lugar do sistema deve chamar o AiProviderInterface diretamente.
 */
class AiUsageService
{
    public function __construct(
        private readonly AiProviderInterface $provider,
        private readonly AiCreditService $credits,
    ) {}

    public function analyzeResume(User $user, ResumeContextData $resume): ResumeAnalysisResult
    {
        return $this->execute($user, AiOperation::ResumeAnalysis, fn () => $this->provider->analyzeResume($resume));
    }

    public function improveResume(User $user, ResumeContextData $resume): ResumeImprovementResult
    {
        return $this->execute($user, AiOperation::ResumeImprovement, fn () => $this->provider->improveResume($resume));
    }

    public function analyzeJob(User $user, string $jobDescription): JobAnalysisResult
    {
        return $this->execute($user, AiOperation::JobAnalysis, fn () => $this->provider->analyzeJob($jobDescription));
    }

    public function matchResumeWithJob(User $user, ResumeContextData $resume, string $jobDescription): JobMatchResult
    {
        return $this->execute($user, AiOperation::ResumeMatching, fn () => $this->provider->matchResumeWithJob($resume, $jobDescription));
    }

    public function customizeResume(User $user, ResumeContextData $resume, string $jobDescription): ResumeCustomizationResult
    {
        return $this->execute($user, AiOperation::ResumeCustomization, fn () => $this->provider->customizeResume($resume, $jobDescription));
    }

    public function generateCoverLetter(User $user, ResumeContextData $resume, string $jobDescription, string $company, ?string $tone = null): CoverLetterResult
    {
        return $this->execute($user, AiOperation::CoverLetter, fn () => $this->provider->generateCoverLetter($resume, $jobDescription, $company, $tone));
    }

    /**
     * @template T
     *
     * @param  Closure(): T  $callback
     * @return T
     */
    private function execute(User $user, AiOperation $operation, Closure $callback): mixed
    {
        if (! $this->credits->hasEnoughCredits($user, $operation)) {
            throw new InsufficientCreditsException;
        }

        try {
            $result = $callback();
        } catch (AiProviderException $e) {
            throw $e;
        }

        $usage = $this->provider->lastUsage();

        AiUsage::create([
            'user_id' => $user->id,
            'operation' => $operation,
            'provider' => $usage?->provider ?? config('ai.provider'),
            'model' => $usage?->model ?? config('ai.model'),
            'input_tokens' => $usage?->inputTokens ?? 0,
            'output_tokens' => $usage?->outputTokens ?? 0,
            'estimated_cost' => $usage?->estimatedCostUsd() ?? 0,
        ]);

        $this->credits->consume($user, $operation);

        return $result;
    }
}
