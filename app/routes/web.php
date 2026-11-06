<?php

use App\Controller\AuthController;
use App\Controller\CaixinhaController;
use App\Controller\HomeController;
use App\Controller\InvestimentoController;
use App\Controller\OrcamentoController;
use App\Controller\VariavelController;
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
    $app->get('/orcamento', [OrcamentoController::class, 'index'])->add(ValidaLoginMiddleware::class);
    $app->get('/orcamento/{ano:[0-9]{4}}/{mes:[0-9]{1,2}}', [OrcamentoController::class, 'index'])->add(ValidaLoginMiddleware::class);
    $app->post('/orcamento/salvar', [OrcamentoController::class, 'salvar'])->add(ValidaLoginMiddleware::class);

    // Caixinhas e Metas
    $app->get('/caixinhas', [CaixinhaController::class, 'index'])->add(ValidaLoginMiddleware::class);
    $app->post('/caixinhas', [CaixinhaController::class, 'salvar'])->add(ValidaLoginMiddleware::class);
    $app->post('/caixinhas/movimentar', [CaixinhaController::class, 'movimentar'])->add(ValidaLoginMiddleware::class);
    $app->post('/caixinhas/excluir/{id:[0-9]+}', [CaixinhaController::class, 'excluir'])->add(ValidaLoginMiddleware::class);

    // Investimentos
    $app->get('/investimentos', [InvestimentoController::class, 'index'])->add(ValidaLoginMiddleware::class);
    $app->post('/investimentos', [InvestimentoController::class, 'salvar'])->add(ValidaLoginMiddleware::class);
    $app->post('/investimentos/atualizar-saldo', [InvestimentoController::class, 'atualizarSaldo'])->add(ValidaLoginMiddleware::class);
    $app->post('/investimentos/excluir/{id:[0-9]+}', [InvestimentoController::class, 'excluir'])->add(ValidaLoginMiddleware::class);

    // Minhas Variáveis de Orçamento
    $app->get('/variaveis', [VariavelController::class, 'index'])->add(ValidaLoginMiddleware::class);
    $app->post('/variaveis/adicionar', [VariavelController::class, 'adicionar'])->add(ValidaLoginMiddleware::class);
    $app->post('/variaveis/excluir/{id:[0-9]+}', [VariavelController::class, 'excluir'])->add(ValidaLoginMiddleware::class);
};
