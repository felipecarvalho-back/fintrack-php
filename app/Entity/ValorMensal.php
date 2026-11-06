<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TipoCalculo;
use App\Enum\TipoVariavel;

class ValorMensal
{
    public function __construct(
        private ?int $id,
        private int $financaMensalId,
        private int $variavelId,
        private float $valor,
        private ?float $quantidade = null,
        private ?float $valorUnitario = null,
        private ?string $variavelNome = null,
        private ?TipoVariavel $variavelTipo = null,
        private ?TipoCalculo $tipoCalculo = null,
        private ?string $labelMultiplicador = null,
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

    public function getQuantidade(): ?float
    {
        return $this->quantidade;
    }

    public function getValorUnitario(): ?float
    {
        return $this->valorUnitario;
    }

    public function getVariavelNome(): ?string
    {
        return $this->variavelNome;
    }

    public function getVariavelTipo(): ?TipoVariavel
    {
        return $this->variavelTipo;
    }

    public function getTipoCalculo(): ?TipoCalculo
    {
        return $this->tipoCalculo;
    }

    public function getLabelMultiplicador(): ?string
    {
        return $this->labelMultiplicador;
    }

    public function toArray(): array
    {
        return [
            'id'                 => $this->id,
            'financaMensalId'    => $this->financaMensalId,
            'variavelId'         => $this->variavelId,
            'quantidade'         => $this->quantidade,
            'valorUnitario'      => $this->valorUnitario,
            'valor'              => $this->valor,
            'variavelNome'       => $this->variavelNome,
            'variavelTipo'       => $this->variavelTipo?->value,
            'tipoCalculo'        => $this->tipoCalculo?->value,
            'labelMultiplicador' => $this->labelMultiplicador,
        ];
    }
}
