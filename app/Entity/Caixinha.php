<?php

declare(strict_types=1);

namespace App\Entity;

class Caixinha
{
    public function __construct(
        private ?int $id,
        private int $contaId,
        private string $name,
        private float $targetAmount,
        private float $currentAmount = 0.0,
        private ?string $color = '#0d6efd',
        private ?string $icon = 'bi-piggy-bank',
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

    public function getTargetAmount(): float
    {
        return $this->targetAmount;
    }

    public function getCurrentAmount(): float
    {
        return $this->currentAmount;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getProgresso(): float
    {
        if ($this->targetAmount <= 0.0) {
            return 0.0;
        }

        return min(100.0, round(($this->currentAmount / $this->targetAmount) * 100, 2));
    }

    public function isConcluida(): bool
    {
        return $this->currentAmount >= $this->targetAmount && $this->targetAmount > 0;
    }

    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'contaId'        => $this->contaId,
            'name'           => $this->name,
            'target_amount'  => $this->targetAmount,
            'current_amount' => $this->currentAmount,
            'color'          => $this->color,
            'icon'           => $this->icon,
            'progresso'      => $this->getProgresso(),
            'concluida'      => $this->isConcluida(),
        ];
    }
}
