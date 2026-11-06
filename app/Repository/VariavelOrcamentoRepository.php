<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\VariavelOrcamento;
use App\Enum\TipoVariavel;
use PDO;

class VariavelOrcamentoRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function inserir(VariavelOrcamento $variavel): int
    {
        $ordem = $variavel->getOrdem() > 0
            ? $variavel->getOrdem()
            : $this->proximaOrdem($variavel->getContaId(), $variavel->getTipo()->value);

        $sql = "INSERT INTO variaveis_orcamento (conta_id, nome, tipo, is_fixo, valor_padrao, ordem)
                VALUES (:conta_id, :nome, :tipo, :is_fixo, :valor_padrao, :ordem)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':conta_id'     => $variavel->getContaId(),
            ':nome'         => $variavel->getNome(),
            ':tipo'         => $variavel->getTipo()->value,
            ':is_fixo'      => $variavel->getIsFixo() ? 1 : 0,
            ':valor_padrao' => $variavel->getValorPadrao(),
            ':ordem'        => $ordem
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function buscarPorConta(int $contaId): array
    {
        $sql = "SELECT id, conta_id, nome, tipo, is_fixo, valor_padrao, ordem 
                FROM variaveis_orcamento 
                WHERE conta_id = :conta_id 
                ORDER BY tipo ASC, ordem ASC, id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':conta_id' => $contaId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];
        foreach ($rows as $row) {
            $resultado[] = new VariavelOrcamento(
                id: (int) $row['id'],
                contaId: (int) $row['conta_id'],
                nome: (string) $row['nome'],
                tipo: TipoVariavel::from((string) $row['tipo']),
                isFixo: (bool) $row['is_fixo'],
                valorPadrao: (float) $row['valor_padrao'],
                ordem: (int) $row['ordem']
            );
        }

        return $resultado;
    }

    public function excluir(int $id, int $contaId): bool
    {
        $sql = "DELETE FROM variaveis_orcamento WHERE id = :id AND conta_id = :conta_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $id,
            ':conta_id' => $contaId
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Cria conjunto inicial de variáveis para um novo usuário cadastrado.
     */
    public function inserirPadroes(int $contaId): void
    {
        $padroes = [
            ['nome' => 'Salário Líquido', 'tipo' => 'receita', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 1],
            ['nome' => 'Vale Refeição (VR)', 'tipo' => 'receita', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 2],
            ['nome' => 'Renda Extra / Freelance', 'tipo' => 'receita', 'is_fixo' => 0, 'valor' => 0.0, 'ordem' => 3],
            ['nome' => 'Aluguel / Condomínio', 'tipo' => 'despesa', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 1],
            ['nome' => 'Supermercado', 'tipo' => 'despesa', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 2],
            ['nome' => 'Luz & Água', 'tipo' => 'despesa', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 3],
            ['nome' => 'Internet Fibra', 'tipo' => 'despesa', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 4],
            ['nome' => 'Cartão de Crédito', 'tipo' => 'despesa', 'is_fixo' => 1, 'valor' => 0.0, 'ordem' => 5],
        ];

        $sql = "INSERT INTO variaveis_orcamento (conta_id, nome, tipo, is_fixo, valor_padrao, ordem) 
                VALUES (:conta_id, :nome, :tipo, :is_fixo, :valor_padrao, :ordem)";

        $stmt = $this->pdo->prepare($sql);

        foreach ($padroes as $item) {
            $stmt->execute([
                ':conta_id'     => $contaId,
                ':nome'         => $item['nome'],
                ':tipo'         => $item['tipo'],
                ':is_fixo'      => $item['is_fixo'],
                ':valor_padrao' => $item['valor'],
                ':ordem'        => $item['ordem'],
            ]);
        }
    }

    private function proximaOrdem(int $contaId, string $tipo): int
    {
        $sql = "SELECT COALESCE(MAX(ordem), 0) + 1 
                FROM variaveis_orcamento 
                WHERE conta_id = :conta_id AND tipo = :tipo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':conta_id' => $contaId,
            ':tipo'     => $tipo
        ]);

        return (int) $stmt->fetchColumn();
    }
}
