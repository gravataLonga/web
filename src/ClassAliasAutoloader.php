<?php declare(strict_types=1);

namespace Gravatalonga;

use Psy\Shell;

final class ClassAliasAutoloader
{
    protected Shell $shell;

    protected array $includedClasses = [];

    public static function register(Shell $shell, array $includedAliases = [])
    {
        $loader = new static($shell, $includedAliases);
        spl_autoload_register([$loader, 'aliasClass']);
        return $loader;
    }

    public function __construct(Shell $shell, array $includedAliases = [])
    {
        $this->shell = $shell;

        foreach ($includedAliases as $class => $path) {

            $name = $this->getClassBaseName($class);

            if (! isset($this->includedClasses[$name])) {
                $this->includedClasses[$name] = $path;
            }
        }
    }

    public function aliasClass($class)
    {
        if (str_contains($class, '\\')) {
            return;
        }

        $fullName = $this->includedClasses[$class] ?? false;

        if (! $fullName) {
           return;
        }

        $this->shell->writeStdout("[!] Aliasing '{$class}' to '{$fullName}' for this Tinker session.\n");

        class_alias($fullName, $class);
    }

    public function unregister()
    {
        spl_autoload_unregister([$this, 'aliasClass']);
    }

    public function __destruct()
    {
        $this->unregister();
    }

    private function getClassBaseName(object|string $classOrObject)
    {
        $classOrObject = is_object($classOrObject) ? get_class($classOrObject) : $classOrObject;

        return basename(str_replace('\\', '/', $classOrObject));
    }
}