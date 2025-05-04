<?php declare(strict_types=1);

namespace Gravatalonga\Twig;

use Psr\Container\ContainerInterface;

final class Config
{
    public function __construct(private ContainerInterface $container, private array $allowConfig = [])
    {}

    public function get(string $key, ?string $default = null)
    {

        if (! in_array($key, $this->allowConfig)) {
            return $default;
        }

        if (! $this->container->has($key)) {
            return $default;
        }

        return $this->container->get($key);
    }
}