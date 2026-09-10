<?php

namespace App\Domain\AI\DTOs;

final readonly class CoverLetterResult
{
    public function __construct(
        public string $content,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            content: (string) ($data['content'] ?? ''),
        );
    }
}
