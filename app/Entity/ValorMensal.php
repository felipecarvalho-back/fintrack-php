<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TipoVariavel;

class ValorMensal
{
    public function __construct(
        private ?int $id,
        private int $financaMensalId,
        private int $variavelId,
        private float $valor,
        private ?string $variavelNome = null,
        private ?TipoVariavel $variavelTipo = null,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFinancaMensalId(): int
    {
        return $this->financaMensalId;
    }

    public function getVariavelId(): int
    {
        return $this->variavelId;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getVariavelNome(): ?string
    {
        return $this->variavelNome;
    }

    public function getVariavelTipo(): ?TipoVariavel
    {
        return $this->variavelTipo;
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'financaMensalId'  => $this->financaMensalId,
            'variavelId'       => $this->variavelId,
            'valor'            => $this->valor,
            'variavelNome'     => $this->variavelNome,
            'variavelTipo'     => $this->variavelTipo?->value,
        ];
    }
}
