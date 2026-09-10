<?php

namespace App\Domain\AI\DTOs;

final readonly class ResumeExtractionResult
{
    public function __construct(
        public string $fullName,
        public ?string $email,
        public ?string $phone,
        public ?string $city,
        public ?string $state,
        public ?string $linkedinUrl,
        public ?string $githubUrl,
        public ?string $portfolioUrl,
        public ?string $professionalSummary,
        /** @var array<int, array{company: string, position: string, start_date: ?string, end_date: ?string, is_current: bool, description: ?string}> */
        public array $experiences,
        /** @var array<int, array{institution: string, course: string, degree: ?string, start_date: ?string, end_date: ?string}> */
        public array $education,
        /** @var array<int, array{name: string, institution: ?string}> */
        public array $courses,
        /** @var string[] */
        public array $skills,
        /** @var array<int, array{name: string, level: string}> */
        public array $languages,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            fullName: (string) ($data['full_name'] ?? ''),
            email: $data['email'] ?: null,
            phone: $data['phone'] ?: null,
            city: $data['city'] ?: null,
            state: $data['state'] ?: null,
            linkedinUrl: $data['linkedin_url'] ?: null,
            githubUrl: $data['github_url'] ?: null,
            portfolioUrl: $data['portfolio_url'] ?: null,
            professionalSummary: $data['professional_summary'] ?: null,
            experiences: array_values((array) ($data['experiences'] ?? [])),
            education: array_values((array) ($data['education'] ?? [])),
            courses: array_values((array) ($data['courses'] ?? [])),
            skills: array_values(array_filter((array) ($data['skills'] ?? []))),
            languages: array_values((array) ($data['languages'] ?? [])),
        );
    }
}
