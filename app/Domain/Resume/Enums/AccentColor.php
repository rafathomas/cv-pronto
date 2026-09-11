<?php

namespace App\Domain\Resume\Enums;

class AccentColor
{
    public const DEFAULT = '#16A34A';

    /**
     * Paleta de cores de destaque disponíveis para qualquer template.
     *
     * @var array<string, string>
     */
    public const PALETTE = [
        'Cinza' => '#64748B',
        'Vermelho' => '#DC2626',
        'Laranja' => '#EA580C',
        'Amarelo' => '#CA8A04',
        'Verde' => self::DEFAULT,
        'Turquesa' => '#0D9488',
        'Azul' => '#2563EB',
        'Roxo' => '#7C3AED',
    ];
}
