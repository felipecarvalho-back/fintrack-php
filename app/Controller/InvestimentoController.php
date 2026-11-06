<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\InvestimentoService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class InvestimentoController
{
    public function __construct(
        private PhpRenderer $renderer,
        private InvestimentoService $service
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $resumo = $this->service->obterResumo($contaId);

        $dadosInvestimentos = array_map(fn($i) => $i->toArray(), $resumo['investimentos']);

        return $this->renderer->render($response, 'investimentos.php', [
            'titulo'                   => 'Carteira de Investimentos - FinTrack',
            'investimentos'            => $dadosInvestimentos,
            'total_investido'          => $resumo['total_investido'],
            'patrimonio_atual'         => $resumo['patrimonio_atual'],
            'lucro_total'              => $resumo['lucro_total'],
            'rentabilidade_percentual' => $resumo['rentabilidade_percentual'],
        ]);
    }

    public function salvar(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $dados = (array) ($request->getParsedBody() ?? []);

        try {
            $this->service->criar($contaId, $dados);
            $_SESSION['sucesso'] = "Investimento cadastrado com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', '/investimentos')->withStatus(302);
    }

    public function atualizarSaldo(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $dados = (array) ($request->getParsedBody() ?? []);

        $investimentoId = (int) ($dados['investimento_id'] ?? 0);
        $currentValue = $dados['current_value'] ?? 0.0;

        try {
            $this->service->atualizarSaldo($contaId, $investimentoId, $currentValue);
            $_SESSION['sucesso'] = "Saldo do investimento atualizado com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', '/investimentos')->withStatus(302);
    }

    public function excluir(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);

        try {
            $this->service->excluir($id, $contaId);
            $_SESSION['sucesso'] = "Investimento excluído com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', '/investimentos')->withStatus(302);
    }
}
