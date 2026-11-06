<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Caixinha;
use App\Repository\CaixinhaRepository;
use InvalidArgumentException;

class CaixinhaService
{
    public function __construct(
        private CaixinhaRepository $repository
    ) {}

    /**
     * @return Caixinha[]
     */
    public function listar(int $contaId): array
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        return $this->repository->listarPorConta($contaId);
    }

    public function criar(int $contaId, array $dados): int
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        $name = trim((string) ($dados['name'] ?? ''));
        $targetAmount = $this->sanitizarValorMonetario($dados['target_amount'] ?? 0.0);
        $currentAmount = $this->sanitizarValorMonetario($dados['current_amount'] ?? 0.0);
        $color = trim((string) ($dados['color'] ?? '#0d6efd'));
        $icon = trim((string) ($dados['icon'] ?? 'bi-piggy-bank'));

        if (empty($name)) {
            throw new InvalidArgumentException("O nome da caixinha é obrigatório.");
        }

        if ($targetAmount <= 0.0) {
            throw new InvalidArgumentException("A meta financeira deve ser maior que zero.");
        }

        if ($currentAmount < 0.0) {
            throw new InvalidArgumentException("O depósito inicial não pode ser negativo.");
        }

        $caixinha = new Caixinha(
            id: null,
            contaId: $contaId,
            name: $name,
            targetAmount: $targetAmount,
            currentAmount: $currentAmount,
            color: $color,
            icon: $icon
        );

        return $this->repository->inserir($caixinha);
    }

    public function movimentar(int $contaId, int $caixinhaId, string $operacao, mixed $valorRaw): bool
    {
        if ($contaId <= 0 || $caixinhaId <= 0) {
            throw new InvalidArgumentException("Dados inválidos para movimentação.");
        }

        $valor = $this->sanitizarValorMonetario($valorRaw);
        if ($valor <= 0.0) {
            throw new InvalidArgumentException("Informe um valor válido maior que zero.");
        }

        $caixinha = $this->repository->buscarPorId($caixinhaId, $contaId);
        if (!$caixinha) {
            throw new InvalidArgumentException("Caixinha não encontrada ou você não tem permissão.");
        }

        $saldoAtual = $caixinha->getCurrentAmount();

        if ($operacao === 'resgatar') {
            if ($saldoAtual < $valor) {
                throw new InvalidArgumentException("Saldo insuficiente na caixinha para resgate. Saldo atual: R$ " . number_format($saldoAtual, 2, ',', '.'));
            }
            $novoSaldo = $saldoAtual - $valor;
        } else {
            // Operação 'guardar' / depósito
            $novoSaldo = $saldoAtual + $valor;
        }

        return $this->repository->atualizarSaldo($caixinhaId, $contaId, round($novoSaldo, 2));
    }

    public function excluir(int $id, int $contaId): bool
    {
        if ($id <= 0 || $contaId <= 0) {
            throw new InvalidArgumentException("Parâmetros inválidos para exclusão.");
        }

        $excluiu = $this->repository->excluir($id, $contaId);
        if (!$excluiu) {
            throw new InvalidArgumentException("Caixinha não encontrada ou você não tem permissão.");
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
