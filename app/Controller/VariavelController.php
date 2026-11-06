<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\VariavelOrcamentoRepository;
use App\Service\VariavelOrcamentoService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class VariavelController
{
    public function __construct(
        private PhpRenderer $renderer,
        private VariavelOrcamentoService $service,
        private VariavelOrcamentoRepository $repository
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $variaveis = $this->repository->buscarPorConta($contaId);

        $dadosVariaveis = array_map(fn($v) => $v->toArray(), $variaveis);

        return $this->renderer->render($response, 'variaveis.php', [
            'titulo'    => 'Minhas Variáveis - FinTrack',
            'variaveis' => $dadosVariaveis
        ]);
    }

    public function adicionar(Request $request, Response $response): Response
    {
        $dados = (array) ($request->getParsedBody() ?? []);
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $origem = (string) ($dados['origem'] ?? '/variaveis');

        try {
            $novaId = $this->service->cadastrar($contaId, $dados);

            $mes = isset($dados['mes']) ? (int) $dados['mes'] : 0;
            $ano = isset($dados['ano']) ? (int) $dados['ano'] : 0;
            if ($mes > 0 && $ano > 0) {
                $this->service->vincularAoMes($contaId, $mes, $ano, $novaId, $dados);
            }

            $_SESSION['sucesso'] = "Variável adicionada com sucesso!";

            return $response->withHeader('Location', $origem)->withStatus(302);

        } catch (InvalidArgumentException $e) {
            $_SESSION['erro_modal_variavel'] = $e->getMessage();
            $_SESSION['dados_modal_variavel'] = $dados;

            return $response->withHeader('Location', $origem)->withStatus(302);
        }
    }

    public function excluir(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $contaId = (int) ($_SESSION['usuario']['id'] ?? 0);
        $origem = $request->getHeaderLine('Referer') ?: '/variaveis';

        try {
            $this->service->excluir($id, $contaId);
            $_SESSION['sucesso'] = "Variável excluída com sucesso!";
        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
        }

        return $response->withHeader('Location', $origem)->withStatus(302);
    }
}
