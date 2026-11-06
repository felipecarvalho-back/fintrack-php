<?php

declare(strict_types=1);

namespace App\Enum;

enum TipoCalculo: string
{
    case Simples = 'simples';
    case Multiplicador = 'multiplicador';
}
