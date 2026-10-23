<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use PDO;

abstract class Model
{
    protected string $tabela = '';
    protected array $atributos = [];

    public function __construct(
        private PDO $pdo
    ) {}

    public function select(mixed ...$colunas)
    {
        $sql = '';

        if (empty($colunas)) {
            $sql .= "SELECT * FROM {$this->tabela}";
        }

        if (count($colunas) > 0) {
            foreach ($colunas as $coluna) {
                if (!in_array($coluna, $this->atributos)) {
                    throw new Exception("O atributo {$coluna} não existe na tabela {$this->tabela}");
                }
            }

            $colunas = implode(', ', $colunas);
            $sql .= "SELECT {$colunas} FROM {$this->tabela}";
        }

        echo $sql;
    }
}
