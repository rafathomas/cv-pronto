<?php

namespace App\Domain\Resume\DTOs;

final readonly class ResumePersonalData
{
    public function __construct(
        public string $fullName,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $linkedinUrl = null,
        public ?string $githubUrl = null,
        public ?string $portfolioUrl = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            fullName: $data['full_name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            linkedinUrl: $data['linkedin_url'] ?? null,
            githubUrl: $data['github_url'] ?? null,
            portfolioUrl: $data['portfolio_url'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'state' => $this->state,
            'linkedin_url' => $this->linkedinUrl,
            'github_url' => $this->githubUrl,
            'portfolio_url' => $this->portfolioUrl,
        ];
    }
}
