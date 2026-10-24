<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$containerBuilder = new ContainerBuilder();
// $containerBuilder->addDefinitions(__DIR__ . '/../app/config/database.php');
$containerBuilder->addDefinitions(__DIR__ . '/../app/config/template.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->addErrorMiddleware(
    displayErrorDetails: true,
    logErrors: false,
    logErrorDetails: false
);

(require __DIR__ . '/../app/routes/web.php')($app);

$app->run();