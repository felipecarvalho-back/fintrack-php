<?php

declare(strict_types=1);

namespace App\Entity;

class Conta
{
    public function __construct(
        private ?int $id,
        private string $nomeCompleto,
        private string $email,
        private string $senha
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomeCompleto(): string
    {
        return $this->nomeCompleto;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }
}
