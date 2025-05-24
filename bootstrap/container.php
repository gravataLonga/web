<?php declare(strict_types=1);

use Gravatalonga\Command\CommandServiceProvider;
use Gravatalonga\Logger\LoggerServiceProvider;
use Gravatalonga\Security\SecurityServiceProvider;
use Gravatalonga\Session\SessionServiceProvider;
use Gravatalonga\Twig\TwigServiceProvider;

$builder = new DI\ContainerBuilder();

$builder->addDefinitions(ROOT_PATH . '/config/app.php');
$builder->addDefinitions(ROOT_PATH . '/config/assets.php');

$builder->addDefinitions(new LoggerServiceProvider());
$builder->addDefinitions(new SecurityServiceProvider());
$builder->addDefinitions(new SessionServiceProvider());
$builder->addDefinitions(new CommandServiceProvider());
$builder->addDefinitions(new TwigServiceProvider());

if ($_ENV['APP_ENV'] === 'production') {
    $builder->enableCompilation(ROOT_PATH . '/storage/framework/cache');
}

/** @var \Psr\Container\ContainerInterface */
return $builder->build();