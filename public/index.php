<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../app/config/database.php');
$containerBuilder->addDefinitions(__DIR__ . '/../app/config/template.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();

$isDev = ($_ENV['APP_ENV'] ?? 'production') === 'development';
$app->addErrorMiddleware($isDev, true, true);


(require __DIR__ . '/../app/routes/web.php')($app);

$app->run();
