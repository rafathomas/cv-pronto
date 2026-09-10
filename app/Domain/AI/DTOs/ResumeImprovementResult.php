<?php

namespace App\Domain\AI\DTOs;

final readonly class ResumeImprovementResult
{
    public function __construct(
        public ?string $improvedSummary,
        /** @var array<int, string> chave = id da experiência */
        public array $experienceImprovements,
        /** @var string[] informações importantes que faltam e a IA não pode inventar */
        public array $missingInfoSuggestions,
    ) {}

    public static function fromArray(array $data): self
    {
        $experienceImprovements = [];
        foreach ((array) ($data['experience_improvements'] ?? []) as $item) {
            if (isset($item['id'], $item['description'])) {
                $experienceImprovements[(int) $item['id']] = (string) $item['description'];
            }
        }

        return new self(
            improvedSummary: $data['improved_summary'] ?? null,
            experienceImprovements: $experienceImprovements,
            missingInfoSuggestions: array_values((array) ($data['missing_info_suggestions'] ?? [])),
        );
    }
}
