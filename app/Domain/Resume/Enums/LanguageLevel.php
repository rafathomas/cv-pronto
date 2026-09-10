<?php

namespace App\Domain\Resume\Enums;

enum LanguageLevel: string
{
    case Basico = 'basico';
    case Intermediario = 'intermediario';
    case Avancado = 'avancado';
    case Fluente = 'fluente';
    case Nativo = 'nativo';

    public function label(): string
    {
        return match ($this) {
            self::Basico => 'Básico',
            self::Intermediario => 'Intermediário',
            self::Avancado => 'Avançado',
            self::Fluente => 'Fluente',
            self::Nativo => 'Nativo',
        };
    }
}
