<?php

declare(strict_types=1);

namespace App\Controller;

use InvalidArgumentException;
use App\Service\VariavelOrcamentoService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class HomeController
{
    public function __construct(
        private PhpRenderer $renderer,
        private VariavelOrcamentoService $variavelOrcamento
    ) {
    }

    public function home(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'home.php', [
            'titulo' => 'Dashboard - FinTrack',
        ]);
    }

    public function orcamento(Request $request, Response $response, array $args = []): Response
    {
        return $this->renderer->render($response, 'orcamento.php', [
            'titulo' => 'Orçamento Mensal - FinTrack',
            'ano' => (int) ($args['ano'] ?? date('Y')),
            'mes' => (int) ($args['mes'] ?? date('n')),
        ]);
    }

    public function caixinhas(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'caixinhas.php', [
            'titulo' => 'Caixinhas & Metas - FinTrack',
        ]);
    }

    public function investimentos(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'investimentos.php', [
            'titulo' => 'Investimentos - FinTrack',
        ]);
    }
}
