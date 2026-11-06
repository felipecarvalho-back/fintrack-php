<?php

declare(strict_types=1);

namespace App\Entity;

class FinancaMensal
{
    public function __construct(
        private ?int $id,
        private int $contaId,
        private int $mes,
        private int $ano,
        private ?string $observacoes = null,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContaId(): int
    {
        return $this->contaId;
    }

    public function getMes(): int
    {
        return $this->mes;
    }

    public function getAno(): int
    {
        return $this->ano;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'contaId'     => $this->contaId,
            'mes'         => $this->mes,
            'ano'         => $this->ano,
            'observacoes' => $this->observacoes,
        ];
    }
}
