<?php

namespace App\Domain\AI\Providers;

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
use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InvalidAiResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class OpenAiProvider implements AiProviderInterface
{
    private ?AiUsageMeta $lastUsage = null;

    public function __construct(private readonly array $config) {}

    public function extractResume(string $rawText): ResumeExtractionResult
    {
        return ResumeExtractionResult::fromArray($this->completeJson('resume/extract.txt', [
            'raw_text' => $rawText,
        ]));
    }

    public function analyzeResume(ResumeContextData $resume): ResumeAnalysisResult
    {
        return ResumeAnalysisResult::fromArray($this->completeJson('resume/analyze.txt', [
            'resume_json' => json_encode($resume->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]));
    }

    public function improveResume(ResumeContextData $resume): ResumeImprovementResult
    {
        return ResumeImprovementResult::fromArray($this->completeJson('resume/improve.txt', [
            'resume_json' => json_encode($resume->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]));
    }

    public function analyzeJob(string $jobDescription): JobAnalysisResult
    {
        return JobAnalysisResult::fromArray($this->completeJson('job/analyze.txt', [
            'job_description' => $jobDescription,
        ]));
    }

    public function matchResumeWithJob(ResumeContextData $resume, string $jobDescription): JobMatchResult
    {
        return JobMatchResult::fromArray($this->completeJson('job/match.txt', [
            'resume_json' => json_encode($resume->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'job_description' => $jobDescription,
        ]));
    }

    public function customizeResume(ResumeContextData $resume, string $jobDescription): ResumeCustomizationResult
    {
        return ResumeCustomizationResult::fromArray($this->completeJson('resume/customize.txt', [
            'resume_json' => json_encode($resume->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'job_description' => $jobDescription,
        ]));
    }

    public function generateCoverLetter(ResumeContextData $resume, string $jobDescription, string $company, ?string $tone = null): CoverLetterResult
    {
        return CoverLetterResult::fromArray($this->completeJson('cover-letter/generate.txt', [
            'resume_json' => json_encode($resume->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'job_description' => $jobDescription,
            'company' => $company,
            'tone' => $tone ?? 'profissional e direto',
        ]));
    }

    public function lastUsage(): ?AiUsageMeta
    {
        return $this->lastUsage;
    }

    /**
     * Renderiza o prompt, chama o modelo com retry controlado e devolve o
     * JSON já decodificado como array associativo.
     */
    private function completeJson(string $promptFile, array $variables): array
    {
        $prompt = $this->renderPrompt($promptFile, $variables);

        $model = $this->config['model'];
        $maxAttempts = max(1, (int) config('ai.max_retries', 2));
        $backoffMs = (int) config('ai.retry_backoff_ms', 500);

        $lastException = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return $this->request($model, $prompt);
            } catch (InvalidAiResponseException $e) {
                // JSON inválido normalmente não se resolve com retry.
                throw $e;
            } catch (AiProviderException $e) {
                $lastException = $e;
                Log::warning('Falha ao chamar provedor de IA, tentando novamente', [
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);

                if ($attempt < $maxAttempts) {
                    usleep($backoffMs * 1000 * $attempt);
                }
            }
        }

        throw $lastException ?? new AiProviderException('Falha desconhecida ao chamar o provedor de IA.');
    }

    private function request(string $model, string $prompt): array
    {
        try {
            $response = Http::withToken($this->config['api_key'])
                ->when($this->config['organization'] ?? null, fn ($http, $org) => $http->withHeaders(['OpenAI-Organization' => $org]))
                ->timeout((int) ($this->config['timeout'] ?? 60))
                ->post(rtrim($this->config['base_url'], '/').'/chat/completions', [
                    'model' => $model,
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.4,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você responde estritamente em JSON válido, sem texto fora do JSON.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);
        } catch (Throwable $e) {
            throw new AiProviderException('Não foi possível conectar ao provedor de IA: '.$e->getMessage(), previous: $e);
        }

        if ($response->status() === 429) {
            throw new AiProviderException('Limite de requisições do provedor de IA atingido (rate limit).');
        }

        if ($response->failed()) {
            throw new AiProviderException('Provedor de IA retornou erro: '.$response->status().' '.$response->body());
        }

        $body = $response->json();
        $content = $body['choices'][0]['message']['content'] ?? null;

        if (! $content) {
            throw new InvalidAiResponseException('Resposta do provedor de IA veio vazia.');
        }

        $decoded = json_decode($content, associative: true);

        if (! is_array($decoded)) {
            throw new InvalidAiResponseException('Resposta do provedor de IA não é um JSON válido.');
        }

        $this->lastUsage = new AiUsageMeta(
            provider: 'openai',
            model: $body['model'] ?? $model,
            inputTokens: (int) ($body['usage']['prompt_tokens'] ?? 0),
            outputTokens: (int) ($body['usage']['completion_tokens'] ?? 0),
        );

        return $decoded;
    }

    private function renderPrompt(string $relativePath, array $variables): string
    {
        $path = resource_path("prompts/{$relativePath}");

        if (! is_file($path)) {
            throw new AiProviderException("Prompt não encontrado: {$relativePath}");
        }

        $template = file_get_contents($path);

        foreach ($variables as $key => $value) {
            $template = str_replace('{{'.$key.'}}', (string) $value, $template);
        }

        return $template;
    }
}
