<?php

namespace App\Domain\Job\Enums;

enum ApplicationStatus: string
{
    case Saved = 'saved';
    case Applied = 'applied';
    case Interview = 'interview';
    case Offer = 'offer';
    case Rejected = 'rejected';
    case Hired = 'hired';

    public function label(): string
    {
        return match ($this) {
            self::Saved => 'Salva',
            self::Applied => 'Candidatura enviada',
            self::Interview => 'Entrevista',
            self::Offer => 'Proposta',
            self::Rejected => 'Recusada',
            self::Hired => 'Contratado',
        };
    }
}
