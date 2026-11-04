<?php

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Middleware\ValidaLoginMiddleware;
use Slim\App;

return function (App $app) {
    // Autenticação
    $app->get('/', [AuthController::class, 'login']);
    $app->post('/login', [AuthController::class, 'validaLogin']);
    $app->get('/logout', [AuthController::class, 'logout'])->add(ValidaLoginMiddleware::class);
    $app->get('/cadastro', [AuthController::class, 'cadastro']);
    $app->post('/cadastro', [AuthController::class, 'create']);
    
    // Dashboard Principal
    $app->get('/home', [HomeController::class, 'home'])->add(ValidaLoginMiddleware::class);

    // Orçamento Mensal
    $app->get('/orcamento', [HomeController::class, 'orcamento'])->add(ValidaLoginMiddleware::class);
    $app->get('/orcamento/{ano:[0-9]{4}}/{mes:[0-9]{1,2}}', [HomeController::class, 'orcamento'])->add(ValidaLoginMiddleware::class);

    // Caixinhas e Metas
    $app->get('/caixinhas', [HomeController::class, 'caixinhas'])->add(ValidaLoginMiddleware::class);

    // Investimentos
    $app->get('/investimentos', [HomeController::class, 'investimentos'])->add(ValidaLoginMiddleware::class);

    // Minhas Variáveis de Orçamento
    $app->get('/variaveis', [HomeController::class, 'variaveis'])->add(ValidaLoginMiddleware::class);
};
