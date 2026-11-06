<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\VariavelOrcamento;
use App\Enum\TipoCalculo;
use App\Enum\TipoVariavel;
use App\Repository\FinancaMensalRepository;
use App\Repository\VariavelOrcamentoRepository;
use InvalidArgumentException;
use ValueError;

class VariavelOrcamentoService
{
    public function __construct(
        private VariavelOrcamentoRepository $repository,
        private ?FinancaMensalRepository $financaRepository = null
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
        $ordem = (int) ($dados['ordem'] ?? 0);

        // Processar meses aplicáveis (1 a 12)
        $mesesRecebidos = $dados['meses'] ?? null;
        $mesesArray = [];
        if (is_array($mesesRecebidos)) {
            foreach ($mesesRecebidos as $m) {
                $mInt = (int) $m;
                if ($mInt >= 1 && $mInt <= 12) {
                    $mesesArray[] = $mInt;
                }
            }
        } elseif (is_string($mesesRecebidos) && trim($mesesRecebidos) !== '') {
            foreach (explode(',', $mesesRecebidos) as $m) {
                $mInt = (int) trim($m);
                if ($mInt >= 1 && $mInt <= 12) {
                    $mesesArray[] = $mInt;
                }
            }
        }

        // Se nenhum mês foi marcado explicitamente nas checkboxes:
        if (empty($mesesArray)) {
            $mesContexto = isset($dados['mes']) ? (int) $dados['mes'] : 0;
            if ($mesContexto >= 1 && $mesContexto <= 12 && isset($dados['is_fixo']) && empty($dados['is_fixo'])) {
                $mesesArray = [$mesContexto];
            } else {
                $mesesArray = range(1, 12);
            }
        }

        sort($mesesArray);
        $mesesArray = array_values(array_unique($mesesArray));
        $mesesAplicaveis = implode(',', $mesesArray);
        $isFixo = (count($mesesArray) === 12);

        // Processar ano (null = recorrente em todos os anos; ou ano específico ex: 2026)
        $ano = null;
        $anoTipo = $dados['ano_tipo'] ?? 'todos'; // 'todos' ou 'especifico'
        if ($anoTipo === 'especifico' || !empty($dados['aplica_ano_especifico'])) {
            $anoVal = (int) ($dados['ano'] ?? date('Y'));
            if ($anoVal >= 2000 && $anoVal <= 2100) {
                $ano = $anoVal;
                // Se é restrito a um ano específico, não deve ser considerado estritamente fixo eterno
                $isFixo = false;
            }
        }

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
            labelMultiplicador: !empty($labelMultiplicador) ? $labelMultiplicador : 'Dias',
            mesesAplicaveis: $mesesAplicaveis,
            ano: $ano
        );

        return $this->repository->inserir($variavel);
    }

    public function vincularAoMes(int $contaId, int $mes, int $ano, int $variavelId, array $dados): void
    {
        if ($this->financaRepository === null || $mes < 1 || $mes > 12 || $ano < 2000 || $ano > 2100) {
            return;
        }

        $valorPadrao = $this->sanitizarValorMonetario($dados['valor_padrao'] ?? 0.0);
        $tipoCalculo = strtolower(trim((string) ($dados['tipo_calculo'] ?? 'simples')));
        $quantidade = ($tipoCalculo === 'multiplicador') ? 1.0 : 1.0;
        $valorUnitario = $valorPadrao;
        $valor = $valorPadrao;

        $this->financaRepository->vincularVariavelNoMes(
            $contaId,
            $mes,
            $ano,
            $variavelId,
            $quantidade,
            $valorUnitario,
            $valor
        );
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
