<?php

declare(strict_types=1);

namespace App\Service;

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
     * Retorna o resumo completo de receitas, despesas e saldo do mês.
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
            $mapaValores[$val->getVariavelId()] = $val->getValor();
        }

        // 4. Separa receitas e despesas com valores atuais (ou o valor padrão se não preenchido)
        $receitas = [];
        $despesas = [];
        $totalReceitas = 0.0;
        $totalDespesas = 0.0;

        foreach ($variaveis as $var) {
            $varId = (int) $var->getId();
            // Se o usuário já salvou um valor no mês, usa ele; senão, usa o valor padrão sugerido
            $valorAtual = isset($mapaValores[$varId]) ? (float) $mapaValores[$varId] : $var->getValorPadrao();

            $item = [
                'id'           => $varId,
                'nome'         => $var->getNome(),
                'tipo'         => $var->getTipo()->value,
                'valor_padrao' => $var->getValorPadrao(),
                'valor'        => $valorAtual,
                'is_fixo'      => $var->isFixo(),
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
     * @param array<int, mixed> $valoresFormulario [variavel_id => valor]
     */
    public function salvarValoresDoMes(int $contaId, int $mes, int $ano, array $valoresFormulario): void
    {
        if ($contaId <= 0) {
            throw new InvalidArgumentException("Conta de usuário inválida.");
        }

        $financa = $this->financaRepository->obterOuCriar($contaId, $mes, $ano);

        $valoresSanitizados = [];
        foreach ($valoresFormulario as $variavelId => $valorRaw) {
            $id = (int) $variavelId;
            if ($id <= 0) {
                continue;
            }

            $valorNumerico = $this->sanitizarValorMonetario($valorRaw);
            $valoresSanitizados[$id] = $valorNumerico;
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
