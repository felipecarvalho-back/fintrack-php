<?php

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Middleware\ValidaLoginMiddleware;
use Slim\App;

return function (App $app) {
    $app->get('/', [AuthController::class, 'login']);
    $app->post('/login', [AuthController::class, 'validaLogin']);
    $app->get('/logout', [AuthController::class, 'logout'])->add(ValidaLoginMiddleware::class);
    $app->get('/cadastro', [AuthController::class, 'cadastro']);
    $app->post('/cadastro', [AuthController::class, 'create']);
    
    $app->get('/home', [HomeController::class, 'home'])->add(ValidaLoginMiddleware::class);
};
