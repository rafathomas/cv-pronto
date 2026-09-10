<?php

namespace App\Domain\AI\DTOs;

final readonly class JobAnalysisResult
{
    public function __construct(
        public string $summary,
        /** @var string[] */
        public array $requiredSkills,
        /** @var string[] */
        public array $niceToHaveSkills,
        /** @var string[] */
        public array $keywords,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            summary: (string) ($data['summary'] ?? ''),
            requiredSkills: array_values((array) ($data['required_skills'] ?? [])),
            niceToHaveSkills: array_values((array) ($data['nice_to_have_skills'] ?? [])),
            keywords: array_values((array) ($data['keywords'] ?? [])),
        );
    }
}
