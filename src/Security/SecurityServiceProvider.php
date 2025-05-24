<?php declare(strict_types=1);

namespace Gravatalonga\Security;

use ArrayAccess;
use Gravatalonga\ServiceProvider\DefinitionServiceProvider;

use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
final class SecurityServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [

            'csrf.prefix' => 'csrf',

            'csrf.storage' => null,

            Guard::class => \DI\factory(function (ResponseFactory $responseFactory, string $prefix, ?array $storage) {
                return new Guard($responseFactory, $prefix, $storage);
            })
                ->parameter('prefix', \DI\get('csrf.prefix'))
                ->parameter('storage', \DI\get('csrf.storage')),

            'csrf' => \DI\get(Guard::class),
        ];
    }
}