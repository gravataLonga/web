<?php declare(strict_types=1);

namespace Gravatalonga\Command;

use App\Command\InspireCommand;
use App\Command\TinkerCommand;
use Gravatalonga\ServiceProvider\DefinitionServiceProvider;
use Gravatalonga\ServiceProvider\ServiceProviderInterface;

final class CommandServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [
            'commands' => [
                'inspire' => InspireCommand::class,
                'tinker' => TinkerCommand::class,
            ],

            TinkerCommand::class => \DI\factory(function (array $tinker) {
                return new TinkerCommand($tinker);
            })->parameter('tinker', \DI\get('tinker.alias')),
        ];
    }
}