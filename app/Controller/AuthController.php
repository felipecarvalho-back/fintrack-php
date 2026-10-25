<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ContaService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class AuthController
{
    public function __construct(
        private PhpRenderer $renderer,
        private ContaService $contaService
    ) {}

    public function login(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'login.php', [
            'titulo' => 'Login'
        ]);
    }

    public function cadastro(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'cadastro.php', [
            'titulo' => 'Criar Conta'
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = (array) ($request->getParsedBody() ?? []);

        try {
            $this->contaService->cadastrar($data);

            $_SESSION['sucesso'] = "Conta criada com sucesso! Faça login para continuar.";
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);

        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();
            $_SESSION['dados_antigos'] = [
                'nome_completo' => $data['nome_completo'] ?? '',
                'email'         => $data['email'] ?? '',
            ];

            return $response
                ->withHeader('Location', '/cadastro')
                ->withStatus(302);
        }
    }
}
