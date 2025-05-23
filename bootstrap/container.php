<?php declare(strict_types=1);

use Gravatalonga\Command\CommandServiceProvider;
use Gravatalonga\ServiceProvider\Definition;
use Gravatalonga\Twig\TwigServiceProvider;

$builder = new DI\ContainerBuilder();

$builder->addDefinitions(ROOT_PATH . '/config/app.php');
$builder->addDefinitions(ROOT_PATH . '/config/assets.php');
$builder->addDefinitions(ROOT_PATH . '/config/services.php');
$builder->addDefinitions(new Definition(new CommandServiceProvider()));
$builder->addDefinitions(new Definition(new TwigServiceProvider()));


if ($_ENV['APP_ENV'] === 'production') {
    $builder->enableCompilation(ROOT_PATH . '/storage/framework/cache');
}

/** @var \Psr\Container\ContainerInterface */
return $builder->build();