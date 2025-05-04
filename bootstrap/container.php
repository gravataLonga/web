<?php declare(strict_types=1);

$builder = new DI\ContainerBuilder();

$builder->addDefinitions(ROOT_PATH . '/config/app.php');
$builder->addDefinitions(ROOT_PATH . '/config/assets.php');
$builder->addDefinitions(ROOT_PATH . '/config/services.php');
$builder->addDefinitions(ROOT_PATH . '/config/commands.php');
$builder->addDefinitions(ROOT_PATH . '/config/twig.php');


if ($_ENV['APP_ENV'] === 'production') {
    $builder->enableCompilation(ROOT_PATH . '/storage/framework/cache');
}

/** @var \Psr\Container\ContainerInterface */
return $builder->build();