<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class AuthController
{
    public function __construct(
        private PhpRenderer $renderer,
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
}
