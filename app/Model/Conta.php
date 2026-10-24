<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Model;

class Conta extends Model
{
    protected string $tabela = 'contas';

    protected array $atributos = ['nome', 'numeroconta', 'senha'];

    protected string $chavePrimaria = 'codigo_conta';
}
