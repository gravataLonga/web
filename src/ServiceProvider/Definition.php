<?php declare(strict_types=1);

namespace Gravatalonga\ServiceProvider;

use DI\Definition\Source\DefinitionArray;

final class Definition extends DefinitionArray
{
    private bool $initialized = false;

    public function __construct(private ServiceProviderInterface|string $provider)
    {
        parent::__construct();
    }

    public function getDefinition(string $name): ?\DI\Definition\Definition
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

        $class = $this->provider;
        if (is_string($class) && class_exists($class)) {
            $class = new $class();
        }

        if (! $class instanceof ServiceProviderInterface) {
            throw new \Exception("CommandServiceProvider provided is not instance of " . ServiceProviderInterface::class);
        }

        $definitions = $class->register();

        $this->addDefinitions($definitions);

        $this->initialized = true;
    }
}