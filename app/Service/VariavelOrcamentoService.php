<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\VariavelOrcamento;
use App\Enum\TipoCalculo;
use App\Enum\TipoVariavel;
use App\Repository\VariavelOrcamentoRepository;
use InvalidArgumentException;
use ValueError;

class VariavelOrcamentoService
{
    public function __construct(
        private VariavelOrcamentoRepository $repository
    ) {}

    public function cadastrar(int $contaId, array $dados): int
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        $nome = trim((string) ($dados['nome'] ?? ''));
        $tipoRaw = strtolower(trim((string) ($dados['tipo'] ?? '')));
        $tipoCalculoRaw = strtolower(trim((string) ($dados['tipo_calculo'] ?? 'simples')));
        $labelMultiplicador = trim((string) ($dados['label_multiplicador'] ?? 'Dias'));
        $valorPadraoRaw = $dados['valor_padrao'] ?? 0.0;
        $isFixo = !empty($dados['is_fixo']);
        $ordem = (int) ($dados['ordem'] ?? 0);

        if (empty($nome)) {
            throw new InvalidArgumentException("O nome do campo é obrigatório.");
        }

        if (mb_strlen($nome) > 100) {
            throw new InvalidArgumentException("O nome do campo não pode ultrapassar 100 caracteres.");
        }

        try {
            $tipo = TipoVariavel::from($tipoRaw);
        } catch (ValueError) {
            throw new InvalidArgumentException("Tipo inválido. Escolha entre 'receita' ou 'despesa'.");
        }

        $tipoCalculo = TipoCalculo::tryFrom($tipoCalculoRaw) ?? TipoCalculo::Simples;

        $valorPadrao = $this->sanitizarValorMonetario($valorPadraoRaw);

        if ($valorPadrao < 0) {
            throw new InvalidArgumentException("O valor padrão sugerido não pode ser negativo.");
        }

        $variavel = new VariavelOrcamento(
            id: null,
            contaId: $contaId,
            nome: $nome,
            tipo: $tipo,
            isFixo: $isFixo,
            valorPadrao: $valorPadrao,
            ordem: $ordem,
            tipoCalculo: $tipoCalculo,
            labelMultiplicador: !empty($labelMultiplicador) ? $labelMultiplicador : 'Dias'
        );

        return $this->repository->inserir($variavel);
    }

    public function excluir(int $id, int $contaId): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("Identificador de variável inválido.");
        }

        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        $excluiu = $this->repository->excluir($id, $contaId);
        if (!$excluiu) {
            throw new InvalidArgumentException("Variável não encontrada ou você não tem permissão para excluí-la.");
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
