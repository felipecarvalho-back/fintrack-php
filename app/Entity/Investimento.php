<?php

declare(strict_types=1);

namespace App\Entity;

class Investimento
{
    public function __construct(
        private ?int $id,
        private int $contaId,
        private string $name,
        private string $type,
        private float $amountInvested,
        private float $currentValue,
        private ?string $institution = null,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContaId(): int
    {
        return $this->contaId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmountInvested(): float
    {
        return $this->amountInvested;
    }

    public function getCurrentValue(): float
    {
        return $this->currentValue;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function getRendimento(): float
    {
        return round($this->currentValue - $this->amountInvested, 2);
    }

    public function getRentabilidadePercentual(): float
    {
        if ($this->amountInvested <= 0.0) {
            return 0.0;
        }

        return round(($this->getRendimento() / $this->amountInvested) * 100, 2);
    }

    public function toArray(): array
    {
        return [
            'id'                       => $this->id,
            'contaId'                  => $this->contaId,
            'name'                     => $this->name,
            'type'                     => $this->type,
            'amount_invested'          => $this->amountInvested,
            'current_value'            => $this->currentValue,
            'institution'              => $this->institution,
            'rendimento'               => $this->getRendimento(),
            'rentabilidade_percentual' => $this->getRentabilidadePercentual(),
        ];
    }
}
