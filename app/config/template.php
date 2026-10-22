<?php

use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;

return [
    PhpRenderer::class => function (ContainerInterface $container) {
        $templatePath = dirname(__DIR__).'/view/';

        return new PhpRenderer($templatePath);
    },
];