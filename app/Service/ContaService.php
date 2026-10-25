<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Conta;
use App\Repository\ContaRepository;
use InvalidArgumentException;

class ContaService
{
    public function __construct(
        private ContaRepository $contaRepository
    ) {}

    public function cadastrar(array $dados): int
    {
        $nomeCompleto   = trim((string) ($dados['nome_completo'] ?? ''));
        $email          = trim((string) ($dados['email'] ?? ''));
        $senha          = (string) ($dados['senha'] ?? '');
        $confirmarSenha = (string) ($dados['confirmar_senha'] ?? '');

        if (empty($nomeCompleto)) {
            throw new InvalidArgumentException("O nome completo é obrigatório.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Informe um e-mail válido.");
        }

        if (strlen($senha) < 8) {
            throw new InvalidArgumentException("A senha deve ter no mínimo 8 caracteres.");
        }

        if ($senha !== $confirmarSenha) {
            throw new InvalidArgumentException("As senhas informadas não coincidem.");
        }

        if ($this->contaRepository->findByEmail($email) !== null) {
            throw new InvalidArgumentException("Este e-mail já está em uso.");
        }

        $conta = new Conta(
            id: null,
            nomeCompleto: $nomeCompleto,
            email: $email,
            senha: password_hash($senha, PASSWORD_DEFAULT)
        );

        return $this->contaRepository->insert($conta);
    }
}
