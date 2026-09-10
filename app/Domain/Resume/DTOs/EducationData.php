<?php

namespace App\Domain\Resume\DTOs;

final readonly class EducationData
{
    public function __construct(
        public string $institution,
        public string $course,
        public ?string $degree = null,
        public ?string $startDate = null,
        public ?string $endDate = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            institution: $data['institution'],
            course: $data['course'],
            degree: $data['degree'] ?? null,
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'institution' => $this->institution,
            'course' => $this->course,
            'degree' => $this->degree,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
    }
}
