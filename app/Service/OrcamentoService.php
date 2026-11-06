<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\TipoCalculo;
use App\Enum\TipoVariavel;
use App\Repository\FinancaMensalRepository;
use App\Repository\VariavelOrcamentoRepository;
use InvalidArgumentException;

class OrcamentoService
{
    public function __construct(
        private FinancaMensalRepository $financaRepository,
        private VariavelOrcamentoRepository $variavelRepository
    ) {}

    /**
     * Retorna o resumo completo de receitas, despesas e saldo do mês com suporte a cálculos.
     */
    public function obterDadosDoMes(int $contaId, int $mes, int $ano): array
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        if ($mes < 1 || $mes > 12) {
            $mes = (int) date('n');
        }

        if ($ano < 2000 || $ano > 2100) {
            $ano = (int) date('Y');
        }

        // 1. Obtém ou cria o mês no banco
        $financa = $this->financaRepository->obterOuCriar($contaId, $mes, $ano);

        // 2. Busca todas as variáveis cadastradas pelo usuário
        $variaveis = $this->variavelRepository->buscarPorConta($contaId);

        // 3. Busca os valores já preenchidos neste mês
        $valoresSalvos = $this->financaRepository->buscarValoresDoMes((int) $financa->getId());
        $mapaValores = [];
        foreach ($valoresSalvos as $val) {
            $mapaValores[$val->getVariavelId()] = [
                'valor'          => $val->getValor(),
                'quantidade'     => $val->getQuantidade() ?? 1.0,
                'valor_unitario' => $val->getValorUnitario() ?? $val->getValor(),
            ];
        }

        // 4. Separa receitas e despesas com valores atuais
        $receitas = [];
        $despesas = [];
        $totalReceitas = 0.0;
        $totalDespesas = 0.0;

        foreach ($variaveis as $var) {
            $varId = (int) $var->getId();
            $tipoCalculo = $var->getTipoCalculo();
            $labelMultiplicador = $var->getLabelMultiplicador() ?? 'Dias';

            // Verifica se a variável se aplica a este mês
            $aplicaNesteMes = $var->aplicaNoMes($mes);

            if (isset($mapaValores[$varId])) {
                // Já existe lançamento salvo especificamente para este mês
                $salvo = $mapaValores[$varId];
                $quantidade = (float) $salvo['quantidade'];
                $valorUnitario = (float) $salvo['valor_unitario'];
                $valorAtual = (float) $salvo['valor'];
            } elseif ($aplicaNesteMes) {
                // Não tem lançamento salvo neste mês, mas a variável está programada para este mês:
                // Herda o último valor salvo mais recente ou o padrão cadastrado
                $ultimoSalvo = $this->financaRepository->buscarUltimoValorSalvo($contaId, $varId, $ano, $mes);
                if ($ultimoSalvo !== null) {
                    $quantidade = (float) $ultimoSalvo['quantidade'];
                    $valorUnitario = (float) $ultimoSalvo['valor_unitario'];
                    $valorAtual = ($tipoCalculo === TipoCalculo::Multiplicador)
                        ? ($quantidade * $valorUnitario)
                        : (float) $ultimoSalvo['valor'];
                } else {
                    $quantidade = 1.0;
                    $valorUnitario = $var->getValorPadrao();
                    $valorAtual = ($tipoCalculo === TipoCalculo::Multiplicador) 
                        ? ($quantidade * $valorUnitario) 
                        : $var->getValorPadrao();
                }
            } else {
                // Não se aplica a este mês e não tem lançamento salvo
                continue;
            }

            $item = [
                'id'                  => $varId,
                'nome'                => $var->getNome(),
                'tipo'                => $var->getTipo()->value,
                'tipo_calculo'        => $tipoCalculo->value,
                'label_multiplicador' => $labelMultiplicador,
                'valor_padrao'        => $var->getValorPadrao(),
                'quantidade'          => $quantidade,
                'valor_unitario'      => $valorUnitario,
                'valor'               => $valorAtual,
                'is_fixo'             => $var->isFixo(),
                'meses_aplicaveis'    => $var->getMesesAplicaveis(),
                'meses_formatados'    => $var->getMesesFormatados(),
                'meses_array'         => $var->getMesesArray(),
            ];

            if ($var->getTipo() === TipoVariavel::Receita) {
                $receitas[] = $item;
                $totalReceitas += $valorAtual;
            } else {
                $despesas[] = $item;
                $totalDespesas += $valorAtual;
            }
        }

        $saldo = $totalReceitas - $totalDespesas;

        return [
            'financa'        => $financa,
            'mes'            => $mes,
            'ano'            => $ano,
            'receitas'       => $receitas,
            'despesas'       => $despesas,
            'total_receitas' => round($totalReceitas, 2),
            'total_despesas' => round($totalDespesas, 2),
            'saldo'          => round($saldo, 2),
        ];
    }

    /**
     * Salva os valores informados pelo formulário do mês.
     *
     * @param array<int, mixed> $valoresFormulario [variavel_id => ['quantidade' => x, 'valor_unitario' => y, 'valor' => z] ou float]
     */
    public function salvarValoresDoMes(int $contaId, int $mes, int $ano, array $valoresFormulario): void
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        $financa = $this->financaRepository->obterOuCriar($contaId, $mes, $ano);

        $valoresSanitizados = [];
        foreach ($valoresFormulario as $variavelId => $dadosInput) {
            $id = (int) $variavelId;
            if ($id <= 0) {
                continue;
            }

            if (is_array($dadosInput)) {
                $qtd = isset($dadosInput['quantidade']) ? $this->sanitizarValorMonetario($dadosInput['quantidade']) : 1.0;
                $unit = isset($dadosInput['valor_unitario']) ? $this->sanitizarValorMonetario($dadosInput['valor_unitario']) : 0.0;
                $valorCalculado = $qtd * $unit;
                $valor = isset($dadosInput['valor']) ? $this->sanitizarValorMonetario($dadosInput['valor']) : $valorCalculado;

                // Se o modo for multiplicador, garante que o valor seja qtd * unit
                if (isset($dadosInput['quantidade']) && isset($dadosInput['valor_unitario'])) {
                    $valor = $valorCalculado;
                }

                $valoresSanitizados[$id] = [
                    'quantidade'     => $qtd,
                    'valor_unitario' => $unit,
                    'valor'          => round($valor, 2),
                ];
            } else {
                $valor = $this->sanitizarValorMonetario($dadosInput);
                $valoresSanitizados[$id] = [
                    'quantidade'     => 1.0,
                    'valor_unitario' => $valor,
                    'valor'          => round($valor, 2),
                ];
            }
        }

        $this->financaRepository->salvarLoteValores((int) $financa->getId(), $valoresSanitizados);
    }

    private function sanitizarValorMonetario(mixed $valor): float
    {
        if (is_numeric($valor)) {
            return max(0.0, (float) $valor);
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

            return max(0.0, (float) $limpo);
        }

        return 0.0;
    }
}
