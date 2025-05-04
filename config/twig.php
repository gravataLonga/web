<?php declare(strict_types=1);

use Gravatalonga\TwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    'twig.template' => [
        DI\string('{path.root}/resources/views')
    ],

    'twig.options' => [
        'debug' => DI\get('app.debug'),
        'cache' => DI\factory(function (string $pathRoot) {
            if ($_ENV['APP_ENV'] !== 'production') {
                return false;
            }
            return $pathRoot . '/storage/framework/views';
        })->parameter('pathRoot', DI\get('path.root')),
        'charset' => 'utf-8',
        'auto_reload' => DI\get('app.debug'),
        'strict_variables' => false,
    ],

    FilesystemLoader::class => DI\create()
        ->constructor(DI\get('twig.template')),

    Environment::class => DI\factory(function (
        TwigExtension $extension
        , FilesystemLoader $loader
        , array $options
    ) {
        $env = new Environment($loader, $options);
        $env->addExtension($extension);
        return $env;
    })
        ->parameter('options', DI\get('twig.options')),

    'twig' => DI\get(Environment::class),
];
