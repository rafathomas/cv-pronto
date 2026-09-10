<?php

namespace App\Domain\AI\Exceptions;

use RuntimeException;

class InsufficientCreditsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Você não tem créditos de IA suficientes para esta operação.');
    }
}
