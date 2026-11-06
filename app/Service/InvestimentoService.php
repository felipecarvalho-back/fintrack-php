<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Investimento;
use App\Repository\InvestimentoRepository;
use InvalidArgumentException;

class InvestimentoService
{
    public function __construct(
        private InvestimentoRepository $repository
    ) {}

    /**
     * @return Investimento[]
     */
    public function listar(int $contaId): array
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        return $this->repository->listarPorConta($contaId);
    }

    public function obterResumo(int $contaId): array
    {
        $investimentos = $this->listar($contaId);

        $totalInvestido = 0.0;
        $patrimonioAtual = 0.0;

        foreach ($investimentos as $item) {
            $totalInvestido += $item->getAmountInvested();
            $patrimonioAtual += $item->getCurrentValue();
        }

        $lucroTotal = $patrimonioAtual - $totalInvestido;
        $rentabilidade = $totalInvestido > 0.0 ? ($lucroTotal / $totalInvestido) * 100 : 0.0;

        return [
            'investimentos'            => $investimentos,
            'total_investido'          => round($totalInvestido, 2),
            'patrimonio_atual'         => round($patrimonioAtual, 2),
            'lucro_total'              => round($lucroTotal, 2),
            'rentabilidade_percentual' => round($rentabilidade, 2),
        ];
    }

    public function criar(int $contaId, array $dados): int
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        $name = trim((string) ($dados['name'] ?? ''));
        $type = trim((string) ($dados['type'] ?? 'Renda Fixa'));
        $amountInvested = $this->sanitizarValorMonetario($dados['amount_invested'] ?? 0.0);
        $currentValue = $this->sanitizarValorMonetario($dados['current_value'] ?? $amountInvested);
        $institution = trim((string) ($dados['institution'] ?? ''));

        if (empty($name)) {
            throw new InvalidArgumentException("O nome do ativo de investimento é obrigatório.");
        }

        if ($amountInvested <= 0.0) {
            throw new InvalidArgumentException("O valor investido deve ser maior que zero.");
        }

        if ($currentValue < 0.0) {
            throw new InvalidArgumentException("O saldo atual não pode ser negativo.");
        }

        $investimento = new Investimento(
            id: null,
            contaId: $contaId,
            name: $name,
            type: $type,
            amountInvested: $amountInvested,
            currentValue: $currentValue,
            institution: !empty($institution) ? $institution : null
        );

        return $this->repository->inserir($investimento);
    }

    public function atualizarSaldo(int $contaId, int $investimentoId, mixed $novoValorRaw): bool
    {
        if ($contaId <= 0 || $investimentoId <= 0) {
            throw new InvalidArgumentException("Dados inválidos para atualização.");
        }

        $novoValor = $this->sanitizarValorMonetario($novoValorRaw);
        if ($novoValor < 0.0) {
            throw new InvalidArgumentException("O saldo do investimento não pode ser negativo.");
        }

        $investimento = $this->repository->buscarPorId($investimentoId, $contaId);
        if (!$investimento) {
            throw new InvalidArgumentException("Investimento não encontrado ou você não tem permissão.");
        }

        return $this->repository->atualizarSaldo($investimentoId, $contaId, $novoValor);
    }

    public function excluir(int $id, int $contaId): bool
    {
        if ($id <= 0 || $contaId <= 0) {
            throw new InvalidArgumentException("Parâmetros inválidos para exclusão.");
        }

        $excluiu = $this->repository->excluir($id, $contaId);
        if (!$excluiu) {
            throw new InvalidArgumentException("Investimento não encontrado ou você não tem permissão.");
        }

        return true;
    }

    private function sanitizarValorMonetario(mixed $valor): float
    {
        if (is_numeric($valor)) {
            return (float) $valor;
        }

        if (is_string($valor)) {
            $limpo = trim($valor);
            if (empty($limpo)) {
                return 0.0;
            }

            if (str_contains($limpo, ',')) {
                $limpo = str_replace('.', '', $limpo);
                $limpo = str_replace(',', '.', $limpo);
            }

            return (float) $limpo;
        }

        return 0.0;
    }
}
