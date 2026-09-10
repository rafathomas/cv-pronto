<?php

namespace App\Domain\AI\DTOs;

final readonly class ResumeCustomizationResult
{
    public function __construct(
        public ?string $summary,
        /** @var array<int, string> chave = id da experiência */
        public array $experienceDescriptions,
        /** @var string[] habilidades reais do usuário priorizadas para a vaga */
        public array $highlightedSkills,
        /** @var string[] */
        public array $notes,
    ) {}

    public static function fromArray(array $data): self
    {
        $experienceDescriptions = [];
        foreach ((array) ($data['experience_descriptions'] ?? []) as $item) {
            if (isset($item['id'], $item['description'])) {
                $experienceDescriptions[(int) $item['id']] = (string) $item['description'];
            }
        }

        return new self(
            summary: $data['summary'] ?? null,
            experienceDescriptions: $experienceDescriptions,
            highlightedSkills: array_values((array) ($data['highlighted_skills'] ?? [])),
            notes: array_values((array) ($data['notes'] ?? [])),
        );
    }
}
