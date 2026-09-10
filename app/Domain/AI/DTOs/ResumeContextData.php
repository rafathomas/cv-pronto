<?php

namespace App\Domain\AI\DTOs;

use App\Domain\Resume\Models\Resume;

/**
 * Retrato somente-leitura das informações reais do usuário, usado como
 * entrada para todas as operações de IA. A IA nunca deve receber (nem
 * portanto inventar) nada além do que está aqui.
 */
final readonly class ResumeContextData
{
    public function __construct(
        public string $fullName,
        public ?string $professionalSummary,
        /** @var array<int, array{id: int, company: string, position: string, start_date: string, end_date: ?string, is_current: bool, description: ?string}> */
        public array $experiences,
        /** @var array<int, array{institution: string, course: string, degree: ?string}> */
        public array $education,
        /** @var string[] */
        public array $courses,
        /** @var string[] */
        public array $skills,
        /** @var array<int, array{name: string, level: string}> */
        public array $languages,
    ) {}

    public static function fromModel(Resume $resume): self
    {
        $resume->loadMissing(['experiences', 'education', 'courses', 'skills', 'languages']);

        return new self(
            fullName: $resume->full_name,
            professionalSummary: $resume->professional_summary,
            experiences: $resume->experiences->map(fn ($e) => [
                'id' => $e->id,
                'company' => $e->company,
                'position' => $e->position,
                'start_date' => $e->start_date->toDateString(),
                'end_date' => $e->end_date?->toDateString(),
                'is_current' => $e->is_current,
                'description' => $e->description,
            ])->all(),
            education: $resume->education->map(fn ($e) => [
                'institution' => $e->institution,
                'course' => $e->course,
                'degree' => $e->degree,
            ])->all(),
            courses: $resume->courses->pluck('name')->all(),
            skills: $resume->skills->pluck('name')->all(),
            languages: $resume->languages->map(fn ($l) => [
                'name' => $l->name,
                'level' => $l->level->value,
            ])->all(),
        );
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'professional_summary' => $this->professionalSummary,
            'experiences' => $this->experiences,
            'education' => $this->education,
            'courses' => $this->courses,
            'skills' => $this->skills,
            'languages' => $this->languages,
        ];
    }
}
