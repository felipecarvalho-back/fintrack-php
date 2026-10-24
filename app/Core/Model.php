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
        private PDO $pdo
    ) {
        // Garante que o PDO vai lançar exceções caso ocorra erro no SQL
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function select(string ...$colunas): array
    {
        $sql = '';
        if (empty($colunas)) {
            $sql = "SELECT * FROM {$this->tabela}";
        } else {
            $camposValidos = array_merge($this->atributos, [$this->chavePrimaria]);

            foreach ($colunas as $coluna) {
                if (! in_array($coluna, $camposValidos, true)) {
                    throw new Exception("O atributo '{$coluna}' não existe na tabela '{$this->tabela}'");
                }
            }

            $colunasSql = implode(', ', $colunas);
            $sql = "SELECT {$colunasSql} FROM {$this->tabela}";
        }

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): string
    {
        foreach ($data as $indice => $valor) {
            if (! in_array($indice, $this->atributos, true)) {
                throw new Exception("O atributo '{$indice}' não existe na tabela '{$this->tabela}'");
            }
        }

        $colunas = array_keys($data);
        $placeholders = array_map(fn ($coluna) => ":{$coluna}", $colunas);

        $stringColunas = implode(', ', $colunas);
        $stringPlaceholders = implode(', ', $placeholders);

        $sql = "INSERT INTO {$this->tabela} ({$stringColunas}) VALUES ({$stringPlaceholders})";

        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $atributo => $valor) {
            $stmt->bindValue(":".$atributo, $valor);
        }

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function update(array $data, int $id): string
    {
        foreach ($data as $indice => $valor) {
            if (! in_array($indice, $this->atributos, true)) {
                throw new Exception("O atributo '{$indice}' não existe na tabela '{$this->tabela}'");
            }
        }

        $colunas = array_keys($data);
        $placeholders = array_map(fn ($coluna) => "{$coluna} = :{$coluna}", $colunas);

        $stringPlaceholders = implode(', ', $placeholders);
        $chavePrimariaPlaceholder = ':' . $this->chavePrimaria;

        $sql = "UPDATE {$this->tabela} SET {$stringPlaceholders} WHERE {$this->chavePrimaria} = {$chavePrimariaPlaceholder}";

        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $atributo => $valor) {
            $stmt->bindValue(":".$atributo, $valor);
        }

        $stmt->bindValue($chavePrimariaPlaceholder, $id, PDO::PARAM_INT);

        $stmt->execute();

        if($stmt->rowCount() == 0){
            return 'Não existe nenhum registro';
        }

        return 'Ok';
    }

    public function delete(int $id): string
    {
        $chavePrimariaPlaceholder = ':' . $this->chavePrimaria;
        $sql = "DELETE FROM {$this->tabela} WHERE {$this->chavePrimaria} = {$chavePrimariaPlaceholder}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue($chavePrimariaPlaceholder, $id, PDO::PARAM_INT);

        
        $stmt->execute();

        if($stmt->rowCount() == 0){
            return 'Não existe nenhum registro';
        }

        return 'Ok';
    }
}
