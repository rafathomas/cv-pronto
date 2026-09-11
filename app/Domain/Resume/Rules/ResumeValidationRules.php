<?php

namespace App\Domain\Resume\Rules;

/**
 * Regras de validação do currículo compartilhadas entre os atributos
 * `#[Validate]` das propriedades do Builder e as chamadas `$this->validate()`
 * dos seus métodos de ação — evita que as duas cópias divirjam.
 */
class ResumeValidationRules
{
    /**
     * @return array<string, string>
     */
    public static function personalData(): array
    {
        return [
            'fullName' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'linkedinUrl' => 'nullable|url|max:255',
            'githubUrl' => 'nullable|url|max:255',
            'portfolioUrl' => 'nullable|url|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function professionalSummary(): array
    {
        return [
            'professionalSummary' => 'nullable|string|max:1200',
        ];
    }
}
