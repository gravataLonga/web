<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class LogHttpRequestMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        $this->logger->info($request->getMethod() . ' ' . $path);

        $response = $handler->handle($request);

        $this->logger->info($request->getMethod() . ' ' . $path, [
            'total_time' => microtime(true) - START_TIME
        ]);

        return $response;
    }
}