<?php

declare(strict_types=1);

namespace App\Model;

class Conta extends Model
{
    protected string $tabela = 'contas';

    protected array $atributos = ['id', 'nome', 'numeroconta', 'senha'];
}
