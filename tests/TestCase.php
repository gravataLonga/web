<?php declare(strict_types=1);

namespace Tests;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected static ?App $app = null;

    public function createApp(): App
    {
        if (! defined('START_TIME')) {
            define('START_TIME', microtime(true));
        }

        /** @var \Slim\App $app */
        self::$app = require __DIR__ . '/../bootstrap/bootstrap.php';
        return self::$app;
    }

    public function getContainer(): ContainerInterface
    {
        if (! self::$app) {
            $this->createApp();
        }

        return self::$app->getContainer();
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = new StreamFactory()->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new Request($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}