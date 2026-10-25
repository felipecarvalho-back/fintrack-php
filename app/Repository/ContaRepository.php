<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Conta;
use PDO;

class ContaRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function insert(Conta $conta): int
    {
        $sql = "INSERT INTO contas (nome_completo, email, senha) VALUES (:nome_completo, :email, :senha)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':nome_completo' => $conta->getNomeCompleto(),
            ':email'         => $conta->getEmail(),
            ':senha'         => $conta->getSenha()
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findByEmail(string $email): ?Conta
    {
        $sql = "SELECT id, nome_completo, email, senha FROM contas WHERE email = :email LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Conta(
            id: (int) $row['id'],
            nomeCompleto: (string) $row['nome_completo'],
            email: (string) $row['email'],
            senha: (string) $row['senha']
        );
    }
}
