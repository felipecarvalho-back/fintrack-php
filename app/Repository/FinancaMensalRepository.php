<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FinancaMensal;
use App\Entity\ValorMensal;
use App\Enum\TipoVariavel;
use PDO;

class FinancaMensalRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * Busca o registro do mês para o usuário. Se ainda não existir, cria automaticamente.
     */
    public function obterOuCriar(int $contaId, int $mes, int $ano): FinancaMensal
    {
        $sql = "SELECT id, conta_id, mes, ano, observacoes 
                FROM financas_mensais 
                WHERE conta_id = :conta_id AND mes = :mes AND ano = :ano 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':conta_id' => $contaId,
            ':mes'      => $mes,
            ':ano'      => $ano,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new FinancaMensal(
                id: (int) $row['id'],
                contaId: (int) $row['conta_id'],
                mes: (int) $row['mes'],
                ano: (int) $row['ano'],
                observacoes: $row['observacoes'] ? (string) $row['observacoes'] : null
            );
        }

        // Se não existir, faz o INSERT inicial
        $insertSql = "INSERT INTO financas_mensais (conta_id, mes, ano) 
                      VALUES (:conta_id, :mes, :ano)";

        $insertStmt = $this->pdo->prepare($insertSql);
        $insertStmt->execute([
            ':conta_id' => $contaId,
            ':mes'      => $mes,
            ':ano'      => $ano,
        ]);

        $novoId = (int) $this->pdo->lastInsertId();

        return new FinancaMensal(
            id: $novoId,
            contaId: $contaId,
            mes: $mes,
            ano: $ano,
            observacoes: null
        );
    }

    /**
     * Retorna todos os valores preenchidos no mês com dados da variável vinculada.
     *
     * @return ValorMensal[]
     */
    public function buscarValoresDoMes(int $financaMensalId): array
    {
        $sql = "SELECT vm.id, vm.financa_mensal_id, vm.variavel_id, vm.valor,
                       vo.nome AS variavel_nome, vo.tipo AS variavel_tipo
                FROM valores_mensais vm
                INNER JOIN variaveis_orcamento vo ON vo.id = vm.variavel_id
                WHERE vm.financa_mensal_id = :financa_mensal_id
                ORDER BY vo.tipo ASC, vo.ordem ASC, vo.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':financa_mensal_id' => $financaMensalId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $valores = [];
        foreach ($rows as $row) {
            $valores[] = new ValorMensal(
                id: (int) $row['id'],
                financaMensalId: (int) $row['financa_mensal_id'],
                variavelId: (int) $row['variavel_id'],
                valor: (float) $row['valor'],
                variavelNome: (string) $row['variavel_nome'],
                variavelTipo: TipoVariavel::from((string) $row['variavel_tipo'])
            );
        }

        return $valores;
    }

    /**
     * Salva ou atualiza o valor de uma variável específica no mês (INSERT ... ON DUPLICATE KEY UPDATE).
     */
    public function salvarValorVariavel(int $financaMensalId, int $variavelId, float $valor): void
    {
        $sql = "INSERT INTO valores_mensais (financa_mensal_id, variavel_id, valor)
                VALUES (:financa_mensal_id, :variavel_id, :valor)
                ON DUPLICATE KEY UPDATE valor = VALUES(valor)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':financa_mensal_id' => $financaMensalId,
            ':variavel_id'       => $variavelId,
            ':valor'             => $valor,
        ]);
    }

    /**
     * Salva um lote de valores em uma transação segura.
     *
     * @param array<int, float> $valoresPorVariavel [variavel_id => valor]
     */
    public function salvarLoteValores(int $financaMensalId, array $valoresPorVariavel): void
    {
        $this->pdo->beginTransaction();

        try {
            $sql = "INSERT INTO valores_mensais (financa_mensal_id, variavel_id, valor)
                    VALUES (:financa_mensal_id, :variavel_id, :valor)
                    ON DUPLICATE KEY UPDATE valor = VALUES(valor)";

            $stmt = $this->pdo->prepare($sql);

            foreach ($valoresPorVariavel as $variavelId => $valor) {
                $stmt->execute([
                    ':financa_mensal_id' => $financaMensalId,
                    ':variavel_id'       => (int) $variavelId,
                    ':valor'             => (float) $valor,
                ]);
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
