<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CaixinhaService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class CaixinhaController
{
    public function __construct(
        private PhpRenderer $renderer,
        private CaixinhaService $service
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $caixinhas = $this->service->listar($contaId);

        $dadosCaixinhas = array_map(fn($c) => $c->toArray(), $caixinhas);

        return $this->renderer->render($response, 'caixinhas.php', [
            'titulo'    => 'Caixinhas & Metas - FinTrack',
            'caixinhas' => $dadosCaixinhas,
        ]);
    }

    public function salvar(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $dados = (array) ($request->getParsedBody() ?? []);

        try {
            $this->service->criar($contaId, $dados);
            $_SESSION['sucesso'] = "Caixinha criada com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', '/caixinhas')->withStatus(302);
    }

    public function movimentar(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $dados = (array) ($request->getParsedBody() ?? []);

        $caixinhaId = (int) ($dados['caixinha_id'] ?? 0);
        $operacao = (string) ($dados['operacao'] ?? 'guardar');
        $valor = $dados['valor'] ?? 0.0;

        try {
            $this->service->movimentar($contaId, $caixinhaId, $operacao, $valor);
            $msg = $operacao === 'resgatar' ? "Valor resgatado com sucesso!" : "Valor guardado com sucesso!";
            $_SESSION['sucesso'] = $msg;
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', '/caixinhas')->withStatus(302);
    }

    public function excluir(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);

        try {
            $this->service->excluir($id, $contaId);
            $_SESSION['sucesso'] = "Caixinha excluída com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', '/caixinhas')->withStatus(302);
    }
}
