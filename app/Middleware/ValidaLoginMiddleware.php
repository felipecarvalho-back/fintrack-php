<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class ValidaLoginMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        if (!isset($_SESSION['usuario'])) {
            $response = new Response();
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $response = $handler->handle($request);
        return $response->withHeader('X-Processed-By', 'ValidaLoginMiddleware');
    }
}