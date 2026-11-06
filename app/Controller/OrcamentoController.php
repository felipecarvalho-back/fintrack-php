<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\OrcamentoService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class OrcamentoController
{
    public function __construct(
        private PhpRenderer $renderer,
        private OrcamentoService $service
    ) {}

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $mes = (int) ($args['mes'] ?? date('n'));
        $ano = (int) ($args['ano'] ?? date('Y'));

        $dadosMes = $this->service->obterDadosDoMes($contaId, $mes, $ano);

        return $this->renderer->render($response, 'orcamento.php', [
            'titulo'    => "Orçamento de {$mes}/{$ano} - FinTrack",
            'mes'       => $mes,
            'ano'       => $ano,
            'receitas'  => $dadosMes['receitas'],
            'despesas'  => $dadosMes['despesas'],
            'totais'    => [
                'receitas' => $dadosMes['total_receitas'],
                'despesas' => $dadosMes['total_despesas'],
                'saldo'    => $dadosMes['saldo'],
            ],
        ]);
    }

    public function salvar(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $dados = (array) ($request->getParsedBody() ?? []);

        $mes = (int) ($dados['mes'] ?? date('n'));
        $ano = (int) ($dados['ano'] ?? date('Y'));
        $valores = (array) ($dados['valores'] ?? []);

        try {
            $this->service->salvarValoresDoMes($contaId, $mes, $ano, $valores);
            $_SESSION['sucesso'] = "Orçamento de {$mes}/{$ano} salvo com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response
            ->withHeader('Location', "/orcamento/{$ano}/{$mes}")
            ->withStatus(302);
    }
}
