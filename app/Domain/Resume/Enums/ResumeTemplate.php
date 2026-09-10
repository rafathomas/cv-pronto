<?php

namespace App\Domain\Resume\Enums;

enum ResumeTemplate: string
{
    case Classico = 'classico';
    case Moderno = 'moderno';
    case Minimalista = 'minimalista';
    case Executivo = 'executivo';
    case Tecnologia = 'tecnologia';
    case Elegante = 'elegante';
    case Criativo = 'criativo';

    public function label(): string
    {
        return match ($this) {
            self::Classico => 'Clássico',
            self::Moderno => 'Moderno',
            self::Minimalista => 'Minimalista',
            self::Executivo => 'Executivo',
            self::Tecnologia => 'Tecnologia',
            self::Elegante => 'Elegante',
            self::Criativo => 'Criativo',
        };
    }

    public function view(): string
    {
        return "pdf.resume.{$this->value}";
    }

    public function isPremium(): bool
    {
        return $this !== self::Classico;
    }
}
