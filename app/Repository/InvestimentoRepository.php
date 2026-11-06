<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Investimento;
use PDO;

class InvestimentoRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * @return Investimento[]
     */
    public function listarPorConta(int $contaId): array
    {
        $sql = "SELECT id, conta_id, name, type, amount_invested, current_value, institution 
                FROM investments 
                WHERE conta_id = :conta_id 
                ORDER BY id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':conta_id' => $contaId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $investimentos = [];
        foreach ($rows as $row) {
            $investimentos[] = new Investimento(
                id: (int) $row['id'],
                contaId: (int) $row['conta_id'],
                name: (string) $row['name'],
                type: (string) $row['type'],
                amountInvested: (float) $row['amount_invested'],
                currentValue: (float) $row['current_value'],
                institution: $row['institution'] ? (string) $row['institution'] : null
            );
        }

        return $investimentos;
    }

    public function buscarPorId(int $id, int $contaId): ?Investimento
    {
        $sql = "SELECT id, conta_id, name, type, amount_invested, current_value, institution 
                FROM investments 
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

        return new Investimento(
            id: (int) $row['id'],
            contaId: (int) $row['conta_id'],
            name: (string) $row['name'],
            type: (string) $row['type'],
            amountInvested: (float) $row['amount_invested'],
            currentValue: (float) $row['current_value'],
            institution: $row['institution'] ? (string) $row['institution'] : null
        );
    }

    public function inserir(Investimento $investimento): int
    {
        $sql = "INSERT INTO investments (conta_id, name, type, amount_invested, current_value, institution) 
                VALUES (:conta_id, :name, :type, :amount_invested, :current_value, :institution)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':conta_id'        => $investimento->getContaId(),
            ':name'            => $investimento->getName(),
            ':type'            => $investimento->getType(),
            ':amount_invested' => $investimento->getAmountInvested(),
            ':current_value'   => $investimento->getCurrentValue(),
            ':institution'     => $investimento->getInstitution(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function atualizarSaldo(int $id, int $contaId, float $novoValor): bool
    {
        $sql = "UPDATE investments 
                SET current_value = :current_value 
                WHERE id = :id AND conta_id = :conta_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':current_value' => $novoValor,
            ':id'            => $id,
            ':conta_id'      => $contaId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function excluir(int $id, int $contaId): bool
    {
        $sql = "DELETE FROM investments WHERE id = :id AND conta_id = :conta_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $id,
            ':conta_id' => $contaId,
        ]);

        return $stmt->rowCount() > 0;
    }
}
