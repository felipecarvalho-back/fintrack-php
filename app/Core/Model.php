<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use PDO;

abstract class Model
{
    protected string $tabela = '';
    protected array $atributos = [];
    protected string $chavePrimaria = 'id';

    public function __construct(
        // private PDO $pdo
    ) {}

    public function select(string ...$colunas): string
    {
        $sql = '';
        if (empty($colunas)) {
            return $sql .= "SELECT * FROM {$this->tabela}";
        }

        $camposValidos = array_merge($this->atributos, [$this->chavePrimaria]);

        foreach ($colunas as $coluna) {
            if (!in_array($coluna, $camposValidos, true)) {
                throw new Exception("O atributo '{$coluna}' não existe na tabela '{$this->tabela}'");
            }
        }

        $colunasSql = implode(', ', $colunas);
        return $sql .= "SELECT {$colunasSql} FROM {$this->tabela}";
    }

    public function create(array $data)
    {
        foreach ($data as $indice => $valor) {
            if (!in_array($indice, $this->atributos, true)) {
                throw new Exception("O atributo '{$indice}' não existe na tabela '{$this->tabela}'");
            }
        }

        $colunas = array_keys($data);
        $placeholders = array_map(fn($coluna) => ":{$coluna}", $colunas);

        $stringColunas = implode(', ', $colunas);
        $stringPlaceholders = implode(', ', $placeholders);

        $sql = "INSERT INTO {$this->tabela} ({$stringColunas}) VALUES ({$stringPlaceholders})";

        return $sql;
    }

    public function update(array $data, int $id)
    {
        foreach ($data as $indice => $valor) {
            if (!in_array($indice, $this->atributos, true)) {
                throw new Exception("O atributo '{$indice}' não existe na tabela '{$this->tabela}'");
            }
        }

        $colunas = array_keys($data);
        $placeholders = array_map(fn($coluna) => "{$coluna} = :{$coluna}", $colunas);

        $stringPlaceholders = implode(', ', $placeholders);

        $chavePrimariaPlaceholde = (string) ':' . $this->chavePrimaria;

        $sql = "UPDATE {$this->tabela} SET {$stringPlaceholders} WHERE {$this->chavePrimaria} = {$chavePrimariaPlaceholde}";

        return $sql;
    }

    public function delete(int $id)
    {
        $chavePrimariaPlaceholde = (string) ':' . $this->chavePrimaria;
        $sql = "DELETE FROM {$this->tabela} WHERE {$this->chavePrimaria} = {$chavePrimariaPlaceholde}";

        return $sql;
    }
}
