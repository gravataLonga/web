<?php declare(strict_types=1);

namespace Gravatalonga;

use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

final class Developer
{
    private ContainerInterface $container;

    private ?App $app;

    public function __construct()
    {
        if (defined('ROOT_PATH')) {
            require ROOT_PATH . '/vendor/autoload.php';
        }

        if (!defined('START_TIME')) {
            define('START_TIME', microtime(true));
        }

        $dotenv = Dotenv::createImmutable(ROOT_PATH);
        $dotenv->load();

        $this->app = $this->createApplication();
        $this->container = $this->app->getContainer();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->app->handle($request);
    }

    private function createApplication(): App
    {
        return require ROOT_PATH . '/bootstrap/app.php';
    }

    public function createRequest(
        string $method,
        string|UriInterface $uri,
        array $serverParams = []
    ): ServerRequestInterface
    {
        $factory = $this->container->get(ServerRequestFactory::class);

        return $factory->createServerRequest($method, $uri, $serverParams);
    }
}
