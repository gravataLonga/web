<?php declare(strict_types=1);

namespace Gravatalonga\Twig;

use Gravatalonga\Manifest;
use Gravatalonga\ServiceProvider\DefinitionServiceProvider;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigServiceProvider extends DefinitionServiceProvider
{
    public function register(): array
    {
        return [

            Manifest::class => \DI\create()->constructor(
                \DI\get('vite.manifest'),
                \DI\get('app.url')
            ),

            'twig.template' => [
                \DI\string('{path.root}/resources/views')
            ],

            'twig.options' => [
                'debug' => \DI\get('app.debug'),
                'cache' => \DI\factory(function (string $pathRoot) {
                    if ($_ENV['APP_ENV'] !== 'production') {
                        return false;
                    }
                    return $pathRoot . '/storage/framework/views';
                })->parameter('pathRoot', \DI\get('path.root')),
                'charset' => 'utf-8',
                'auto_reload' => \DI\get('app.debug'),
                'strict_variables' => false,
            ],

            FilesystemLoader::class => \DI\create()
                ->constructor(\DI\get('twig.template')),

            TwigExtension::class => \DI\create()->constructor(\DI\get(ContainerInterface::class)),

            Environment::class => \DI\factory(function (
                TwigExtension $extension
                , FilesystemLoader $loader
                , array $options
            ) {
                $env = new Environment($loader, $options);
                $env->addExtension($extension);
                return $env;
            })
                ->parameter('options', \DI\get('twig.options')),

            'twig' => \DI\get(Environment::class),
        ];
    }
}