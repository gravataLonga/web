<?php declare(strict_types=1);

namespace Gravatalonga\Session;

use Gravatalonga\ServiceProvider\DefinitionServiceProvider;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;

final class SessionServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [
            'session.config' => [

                'name' => 'lumice',

                'lifetime' => 7200,

                'save_path' => null,

                'domain' => \DI\factory(function (string $appUrl) {
                    $parsedUrl = parse_url($appUrl);
                    return $parsedUrl['host'] ?? null;
                })->parameter('appUrl', \DI\get('app.url')),

                'secure' => \DI\get('app.is_production'),

                'httponly' => true,

                'cache_limiter' => 'nocache',
            ],

            SessionManagerInterface::class => \DI\factory(function (SessionInterface $session) {
                return $session;
            })->parameter("session", \DI\get(SessionInterface::class)),

            SessionInterface::class => \DI\factory(function (array $options) {
                return new PhpSession($options);
            })->parameter('options', \DI\get('session.config')),

            'session' => \DI\get(SessionManagerInterface::class),
        ];
    }
}