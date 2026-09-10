<?php

namespace App\Domain\AI\DTOs;

final readonly class ResumeAnalysisResult
{
    public function __construct(
        public int $score,
        public string $summary,
        /** @var string[] */
        public array $strengths,
        /** @var string[] */
        public array $weaknesses,
        /** @var string[] */
        public array $recommendations,
        /** @var array{structure: int, clarity: int, experience: int, keywords: int, ats: int} */
        public array $categories,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            score: (int) ($data['score'] ?? 0),
            summary: (string) ($data['summary'] ?? ''),
            strengths: array_values((array) ($data['strengths'] ?? [])),
            weaknesses: array_values((array) ($data['weaknesses'] ?? [])),
            recommendations: array_values((array) ($data['recommendations'] ?? [])),
            categories: (array) ($data['categories'] ?? []),
        );
    }

    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'summary' => $this->summary,
            'strengths' => $this->strengths,
            'weaknesses' => $this->weaknesses,
            'recommendations' => $this->recommendations,
            'categories' => $this->categories,
        ];
    }
}
