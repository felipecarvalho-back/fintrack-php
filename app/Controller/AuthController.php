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
    ) {
    }

    public function login(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'login.php', [
            'titulo' => 'Login'
        ]);
    }

    public function logout(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();
        }

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    public function validaLogin(Request $request, Response $response): Response
    {
        $data = (array) ($request->getParsedBody() ?? []);

        try {
            $conta = $this->contaService->login($data);
            session_regenerate_id(true);
            $_SESSION['usuario'] = $conta->toSessionArray();

            return $response
                ->withHeader('Location', '/home')
                ->withStatus(302);

        } catch (InvalidArgumentException $e) {
            $_SESSION['erro'] = $e->getMessage();

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }
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
                'email' => $data['email'] ?? '',
            ];

            return $response
                ->withHeader('Location', '/cadastro')
                ->withStatus(302);
        }
    }
}
