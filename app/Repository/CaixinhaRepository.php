<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Caixinha;
use PDO;

class CaixinhaRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * @return Caixinha[]
     */
    public function listarPorConta(int $contaId): array
    {
        $sql = "SELECT id, conta_id, name, target_amount, current_amount, color, icon 
                FROM caixinhas 
                WHERE conta_id = :conta_id 
                ORDER BY id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':conta_id' => $contaId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $caixinhas = [];
        foreach ($rows as $row) {
            $caixinhas[] = new Caixinha(
                id: (int) $row['id'],
                contaId: (int) $row['conta_id'],
                name: (string) $row['name'],
                targetAmount: (float) $row['target_amount'],
                currentAmount: (float) $row['current_amount'],
                color: $row['color'] ? (string) $row['color'] : '#0d6efd',
                icon: $row['icon'] ? (string) $row['icon'] : 'bi-piggy-bank'
            );
        }

        return $caixinhas;
    }

    public function buscarPorId(int $id, int $contaId): ?Caixinha
    {
        $sql = "SELECT id, conta_id, name, target_amount, current_amount, color, icon 
                FROM caixinhas 
                WHERE id = :id AND conta_id = :conta_id 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $id,
            ':conta_id' => $contaId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Caixinha(
            id: (int) $row['id'],
            contaId: (int) $row['conta_id'],
            name: (string) $row['name'],
            targetAmount: (float) $row['target_amount'],
            currentAmount: (float) $row['current_amount'],
            color: $row['color'] ? (string) $row['color'] : '#0d6efd',
            icon: $row['icon'] ? (string) $row['icon'] : 'bi-piggy-bank'
        );
    }

    public function inserir(Caixinha $caixinha): int
    {
        $sql = "INSERT INTO caixinhas (conta_id, name, target_amount, current_amount, color, icon) 
                VALUES (:conta_id, :name, :target_amount, :current_amount, :color, :icon)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':conta_id'       => $caixinha->getContaId(),
            ':name'           => $caixinha->getName(),
            ':target_amount'  => $caixinha->getTargetAmount(),
            ':current_amount' => $caixinha->getCurrentAmount(),
            ':color'          => $caixinha->getColor(),
            ':icon'           => $caixinha->getIcon(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function atualizarSaldo(int $id, int $contaId, float $novoSaldo): bool
    {
        $sql = "UPDATE caixinhas 
                SET current_amount = :current_amount 
                WHERE id = :id AND conta_id = :conta_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':current_amount' => $novoSaldo,
            ':id'             => $id,
            ':conta_id'       => $contaId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function excluir(int $id, int $contaId): bool
    {
        $sql = "DELETE FROM caixinhas WHERE id = :id AND conta_id = :conta_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $id,
            ':conta_id' => $contaId,
        ]);

        return $stmt->rowCount() > 0;
    }
}
