<?php declare(strict_types=1);
namespace App\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

final readonly class HomeController
{
    public function __construct(private Environment $twig)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write(
            $this->twig->render('index.twig', ['method' => $request->getMethod()])
        );
        return $response;
    }
}