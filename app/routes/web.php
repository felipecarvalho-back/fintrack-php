<?php

use App\Controller\AuthController;
use App\Controller\HomeController;
use Slim\App;

return function (App $app) {
    $app->get('/', [AuthController::class, 'login']);
    $app->post('/login', [AuthController::class, 'validaLogin']);
    $app->get('/home', [HomeController::class, 'home']);
    $app->get('/cadastro', [AuthController::class, 'cadastro']);
    $app->post('/cadastro', [AuthController::class, 'create']);
};
