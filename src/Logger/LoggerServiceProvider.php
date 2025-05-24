<?php declare(strict_types=1);

namespace Gravatalonga\Logger;

use Gravatalonga\ServiceProvider\DefinitionServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [
            LoggerInterface::class => \DI\factory(function (string $appName, string $logPath) {
                $logger = new Logger($appName);
                $logger->pushHandler(new StreamHandler($logPath, Level::Info));
                return $logger;
            })
                ->parameter('appName', \DI\get('app.name'))
                ->parameter('logPath', \DI\string('{path.root}/storage/logs/app.log')),

            'logger' => \DI\get(LoggerInterface::class),
        ];
    }
}