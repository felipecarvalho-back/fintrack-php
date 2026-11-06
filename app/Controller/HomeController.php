<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CaixinhaService;
use App\Service\InvestimentoService;
use App\Service\OrcamentoService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class HomeController
{
    public function __construct(
        private PhpRenderer $renderer,
        private OrcamentoService $orcamentoService,
        private CaixinhaService $caixinhaService,
        private InvestimentoService $investimentoService,
    ) {}

    public function home(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $mesAtual = (int) date('n');
        $anoAtual = (int) date('Y');

        // Puxa dados consolidados do mês atual
        $dadosOrcamento = $this->orcamentoService->obterDadosDoMes($contaId, $mesAtual, $anoAtual);
        $caixinhas = $this->caixinhaService->listar($contaId);
        $resumoInvestimentos = $this->investimentoService->obterResumo($contaId);

        $totalGuardadoCaixinhas = 0.0;
        foreach ($caixinhas as $c) {
            $totalGuardadoCaixinhas += $c->getCurrentAmount();
        }

        return $this->renderer->render($response, 'home.php', [
            'titulo'             => 'Dashboard - FinTrack',
            'mesAtual'           => $mesAtual,
            'anoAtual'           => $anoAtual,
            'totalReceitas'      => $dadosOrcamento['total_receitas'],
            'totalDespesas'      => $dadosOrcamento['total_despesas'],
            'saldoMes'           => $dadosOrcamento['saldo'],
            'totalCaixinhas'     => $totalGuardadoCaixinhas,
            'patrimonioInvest'   => $resumoInvestimentos['patrimonio_atual'],
            'lucroInvestimentos' => $resumoInvestimentos['lucro_total'],
            'caixinhas'          => array_map(fn($c) => $c->toArray(), $caixinhas),
        ]);
    }
}
