<?php declare(strict_types=1);

use Gravatalonga\Manifest;
use Gravatalonga\TwigExtension;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;

return [

    LoggerInterface::class => DI\factory(function (string $appName, string $logPath) {
        $logger = new Logger($appName);
        $logger->pushHandler(new StreamHandler($logPath, Level::Info));
        return $logger;
    })
        ->parameter('appName', DI\get('app.name'))
        ->parameter('logPath', DI\string('{path.root}/storage/logs/app.log')),

    Manifest::class => DI\create()->constructor(
        DI\get('vite.manifest'),
        DI\get('app.url')
    ),

    TwigExtension::class => DI\create()->constructor(DI\get(ContainerInterface::class)),

    // Session
    'session.config' => [
        'name' => DI\get('app.name'),
        'lifetime' => 7200,
        'save_path' => null,
        'domain' => null,
        'secure' => false,
        'httponly' => true,
        'cache_limiter' => 'nocache',
    ],

    SessionManagerInterface::class => DI\factory(function (SessionInterface $session) {
        return $session;
    })->parameter("session", DI\get(SessionInterface::class)),

    SessionInterface::class => DI\factory(function (array $options) {
        return new PhpSession($options);
    })->parameter('options', DI\get('session.config')),

    'session' => DI\get(SessionManagerInterface::class),

    // CSRF Adding to Container
    Guard::class => DI\create()
        ->constructor(DI\get(ResponseFactory::class)),

    'csrf' => DI\get(Guard::class),

];
