<?php

use App\Controller\AuthController;
use Slim\App;

return function (App $app) {
    $app->get('/', [AuthController::class, 'login']);
    $app->get('/cadastro', [AuthController::class, 'cadastro']);
};
