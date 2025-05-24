<?php declare(strict_types=1);

namespace Gravatalonga\ServiceProvider;

use DI\Definition\Definition;
use DI\Definition\Source\DefinitionArray;

abstract class DefinitionServiceProvider extends DefinitionArray implements ServiceProviderInterface
{
    private bool $initialized = false;

    public function getDefinition(string $name): ?Definition
    {
        $this->initialize();

        return parent::getDefinition($name);
    }

    public function getDefinitions(): array
    {
        $this->initialize();

        return parent::getDefinitions();
    }

    private function initialize() : void
    {
        if ($this->initialized === true) {
            return;
        }

        $entries = $this->register();

        $this->addDefinitions($entries);

        $this->initialized = true;
    }
}