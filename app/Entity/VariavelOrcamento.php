<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TipoVariavel;

class VariavelOrcamento
{
    public function __construct(
        private ?int $id,
        private int $contaId,
        private string $nome,
        private TipoVariavel $tipo,
        private bool $isFixo,
        private float $valorPadrao,
        private int $ordem
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContaId(): int
    {
        return $this->contaId;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getTipo(): TipoVariavel
    {
        return $this->tipo;
    }

    public function isFixo(): bool
    {
        return $this->isFixo;
    }

    public function getIsFixo(): bool
    {
        return $this->isFixo;
    }

    public function getValorPadrao(): float
    {
        return $this->valorPadrao;
    }

    public function getOrdem(): int
    {
        return $this->ordem;
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'contaId'     => $this->contaId,
            'nome'        => $this->nome,
            'tipo'        => $this->tipo->value,
            'isFixo'      => $this->isFixo,
            'valorPadrao' => $this->valorPadrao,
            'ordem'       => $this->ordem,
        ];
    }
}
