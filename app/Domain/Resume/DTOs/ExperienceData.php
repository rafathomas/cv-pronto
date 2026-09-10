<?php

namespace App\Domain\Resume\DTOs;

final readonly class ExperienceData
{
    public function __construct(
        public string $company,
        public string $position,
        public string $startDate,
        public ?string $endDate,
        public bool $isCurrent,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            company: $data['company'],
            position: $data['position'],
            startDate: $data['start_date'],
            endDate: $data['is_current'] ?? false ? null : ($data['end_date'] ?? null),
            isCurrent: (bool) ($data['is_current'] ?? false),
            description: $data['description'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'company' => $this->company,
            'position' => $this->position,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'is_current' => $this->isCurrent,
            'description' => $this->description,
        ];
    }
}
