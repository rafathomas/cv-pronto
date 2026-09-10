<?php

namespace App\Domain\AI\DTOs;

final readonly class JobMatchResult
{
    public function __construct(
        public int $matchScore,
        /** @var string[] */
        public array $matchedSkills,
        /** @var string[] */
        public array $partialSkills,
        /** @var string[] */
        public array $missingSkills,
        /** @var string[] */
        public array $keywords,
        /** @var string[] */
        public array $recommendations,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            matchScore: (int) ($data['match_score'] ?? 0),
            matchedSkills: array_values((array) ($data['matched_skills'] ?? [])),
            partialSkills: array_values((array) ($data['partial_skills'] ?? [])),
            missingSkills: array_values((array) ($data['missing_skills'] ?? [])),
            keywords: array_values((array) ($data['keywords'] ?? [])),
            recommendations: array_values((array) ($data['recommendations'] ?? [])),
        );
    }
}
