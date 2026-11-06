<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FinancaMensal;
use App\Entity\ValorMensal;
use App\Enum\TipoCalculo;
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
        $sql = "SELECT vm.id, vm.financa_mensal_id, vm.variavel_id, vm.valor, vm.quantidade, vm.valor_unitario,
                       vo.nome AS variavel_nome, vo.tipo AS variavel_tipo,
                       vo.tipo_calculo AS variavel_tipo_calculo, vo.label_multiplicador
                FROM valores_mensais vm
                INNER JOIN variaveis_orcamento vo ON vo.id = vm.variavel_id
                WHERE vm.financa_mensal_id = :financa_mensal_id
                ORDER BY vo.tipo ASC, vo.ordem ASC, vo.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':financa_mensal_id' => $financaMensalId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $valores = [];
        foreach ($rows as $row) {
            $tipoCalcRaw = (string) ($row['variavel_tipo_calculo'] ?? 'simples');
            $tipoCalculo = TipoCalculo::tryFrom($tipoCalcRaw) ?? TipoCalculo::Simples;

            $valores[] = new ValorMensal(
                id: (int) $row['id'],
                financaMensalId: (int) $row['financa_mensal_id'],
                variavelId: (int) $row['variavel_id'],
                valor: (float) $row['valor'],
                quantidade: isset($row['quantidade']) ? (float) $row['quantidade'] : null,
                valorUnitario: isset($row['valor_unitario']) ? (float) $row['valor_unitario'] : null,
                variavelNome: (string) $row['variavel_nome'],
                variavelTipo: TipoVariavel::from((string) $row['variavel_tipo']),
                tipoCalculo: $tipoCalculo,
                labelMultiplicador: (string) ($row['label_multiplicador'] ?? 'Dias')
            );
        }

        return $valores;
    }

    /**
     * Busca o último valor preenchido/salvo de uma variável em meses anteriores.
     *
     * @return array{quantidade: float, valor_unitario: float, valor: float}|null
     */
    public function buscarUltimoValorSalvo(int $contaId, int $variavelId, int $ano, int $mes): ?array
    {
        $sql = "SELECT vm.quantidade, vm.valor_unitario, vm.valor
                FROM valores_mensais vm
                INNER JOIN financas_mensais fm ON fm.id = vm.financa_mensal_id
                WHERE fm.conta_id = :conta_id 
                  AND vm.variavel_id = :variavel_id
                  AND (fm.ano < :ano OR (fm.ano = :ano AND fm.mes < :mes))
                ORDER BY fm.ano DESC, fm.mes DESC
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':conta_id'    => $contaId,
            ':variavel_id' => $variavelId,
            ':ano'         => $ano,
            ':mes'         => $mes,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return [
            'quantidade'     => isset($row['quantidade']) ? (float) $row['quantidade'] : 1.0,
            'valor_unitario' => isset($row['valor_unitario']) ? (float) $row['valor_unitario'] : (float) $row['valor'],
            'valor'          => (float) $row['valor'],
        ];
    }

    /**
     * Vincula ou atualiza o valor de uma variável em um mês específico.
     */
    public function vincularVariavelNoMes(int $contaId, int $mes, int $ano, int $variavelId, float $quantidade, float $valorUnitario, float $valor): void
    {
        $financa = $this->obterOuCriar($contaId, $mes, $ano);

        $sql = "INSERT INTO valores_mensais (financa_mensal_id, variavel_id, quantidade, valor_unitario, valor)
                VALUES (:financa_mensal_id, :variavel_id, :quantidade, :valor_unitario, :valor)
                ON DUPLICATE KEY UPDATE 
                    quantidade = VALUES(quantidade),
                    valor_unitario = VALUES(valor_unitario),
                    valor = VALUES(valor)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':financa_mensal_id' => (int) $financa->getId(),
            ':variavel_id'       => $variavelId,
            ':quantidade'        => $quantidade,
            ':valor_unitario'    => $valorUnitario,
            ':valor'             => $valor,
        ]);
    }

    /**
     * Salva ou atualiza um lote de valores no mês com suporte a multiplicador (quantidade e unitário).
     *
     * @param array<int, array|float> $itensPorVariavel [variavel_id => ['quantidade' => 20, 'unitario' => 25, 'valor' => 500] ou float]
     */
    public function salvarLoteValores(int $financaMensalId, array $itensPorVariavel): void
    {
        $this->pdo->beginTransaction();

        try {
            $sql = "INSERT INTO valores_mensais (financa_mensal_id, variavel_id, quantidade, valor_unitario, valor)
                    VALUES (:financa_mensal_id, :variavel_id, :quantidade, :valor_unitario, :valor)
                    ON DUPLICATE KEY UPDATE 
                        quantidade = VALUES(quantidade),
                        valor_unitario = VALUES(valor_unitario),
                        valor = VALUES(valor)";

            $stmt = $this->pdo->prepare($sql);

            foreach ($itensPorVariavel as $variavelId => $dados) {
                if (is_array($dados)) {
                    $qtd = isset($dados['quantidade']) ? (float) $dados['quantidade'] : 1.0;
                    $unit = isset($dados['valor_unitario']) ? (float) $dados['valor_unitario'] : (float) ($dados['valor'] ?? 0.0);
                    $valorFinal = (float) ($dados['valor'] ?? ($qtd * $unit));
                } else {
                    $qtd = 1.0;
                    $unit = (float) $dados;
                    $valorFinal = (float) $dados;
                }

                $stmt->execute([
                    ':financa_mensal_id' => $financaMensalId,
                    ':variavel_id'       => (int) $variavelId,
                    ':quantidade'        => $qtd,
                    ':valor_unitario'    => $unit,
                    ':valor'             => $valorFinal,
                ]);
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
