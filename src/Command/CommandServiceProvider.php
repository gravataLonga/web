<?php declare(strict_types=1);

namespace Gravatalonga\Command;

use App\Command\TinkerCommand;
use Gravatalonga\ServiceProvider\DefinitionServiceProvider;

final class CommandServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [
            'commands' => require ROOT_PATH . '/app/command.php',

            TinkerCommand::class => \DI\factory(function (array $tinker) {
                return new TinkerCommand($tinker);
            })->parameter('tinker', \DI\get('tinker.alias')),
        ];
    }
}