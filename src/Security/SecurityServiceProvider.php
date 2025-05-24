<?php declare(strict_types=1);

namespace Gravatalonga\Security;

use Gravatalonga\ServiceProvider\DefinitionServiceProvider;

use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
final class SecurityServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [
            Guard::class => \DI\create()
                ->constructor(
                    \DI\get(ResponseFactory::class)
                ),

            'csrf' => \DI\get(Guard::class),
        ];
    }
}