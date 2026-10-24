<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Conta;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class HomeController
{
    public function __construct(
        private PhpRenderer $renderer,
        private Conta $conta
    ) {}

    public function home(Request $request, Response $response): Response
    {
        // var_dump($this->conta->select());
        // echo $this->conta->create([
        //     'nome' => 'Felipe',
        //     'numeroconta' => '123456',
        //     'senha' => 'kira'
        // ]);
        // echo $this->conta->update([
        //     'nome' => 'Test12',
        //     'numeroconta' => '1212',
        //     'senha' => 'felipe'
        // ], 2);
        echo $this->conta->delete(1);

        return $this->renderer->render($response, 'home.php', [
            'titulo' => 'Olha',
        ]);
    }
}
